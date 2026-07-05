<?php

class CartController extends ApiController
{
    /**
     * View the shopping cart.
     */
    public function index()
    {
        if (class_exists('App') && !App::$isApi) {
            if (!isset($_SESSION['user_id'])) {
                header('Location: ' . URLROOT . '/auth/login');
                exit();
            }

            $cartItems = $this->getCartDetails();
            $total = $this->getCartTotal($cartItems);

            View::load('cart/index', [
                'cartItems' => $cartItems,
                'total'     => $total
            ]);
            return;
        }

        // API mode
        $cartItems = $this->getCartDetails();
        $total = $this->getCartTotal($cartItems);
        $this->json([
            'status' => true,
            'cart'   => $cartItems,
            'total'  => $total
        ]);
    }

    /**
     * Add a product to the cart.
     */
    public function add($id)
    {
        if (!isset($_SESSION['user_id'])) {
            if (class_exists('App') && !App::$isApi) {
                header('Location: ' . URLROOT . '/auth/login');
                exit();
            }
            $this->json(['status' => false, 'message' => 'Unauthorized'], 401);
            return;
        }

        $productModel = new Product();
        $product = $productModel->getProductById($id);

        if (!$product) {
            if (class_exists('App') && !App::$isApi) {
                View::load('errors/404');
                return;
            }
            $this->json(['status' => false, 'message' => 'Product not found'], 404);
            return;
        }

        // Initialize cart session if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $currentQty = $_SESSION['cart'][$id] ?? 0;
        
        if ($currentQty + 1 > $product['qty']) {
            if (class_exists('App') && !App::$isApi) {
                $_SESSION['error'] = 'لا يوجد مخزون كافٍ لهذا المنتج!';
                header('Location: ' . URLROOT . '/product');
                exit();
            }
            $this->json(['status' => false, 'message' => 'Insufficient stock'], 400);
            return;
        }

        $_SESSION['cart'][$id] = $currentQty + 1;

        if (class_exists('App') && !App::$isApi) {
            $_SESSION['success'] = 'تم إضافة المنتج إلى السلة بنجاح!';
            header('Location: ' . URLROOT . '/product');
            exit();
        }

        $this->json(['status' => true, 'message' => 'Added to cart successfully']);
    }

    /**
     * Remove a product from the cart.
     */
    public function remove($id)
    {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }

        if (class_exists('App') && !App::$isApi) {
            $_SESSION['success'] = 'تم إزالة المنتج من السلة!';
            header('Location: ' . URLROOT . '/cart');
            exit();
        }

        $this->json(['status' => true, 'message' => 'Removed from cart successfully']);
    }

    /**
     * Update quantity of a product in the cart.
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->body();
            $qty = isset($data['qty']) ? intval($data['qty']) : 1;

            $productModel = new Product();
            $product = $productModel->getProductById($id);

            if ($product && $qty > 0) {
                if ($qty <= $product['qty']) {
                    $_SESSION['cart'][$id] = $qty;
                    $_SESSION['success'] = 'تم تحديث الكمية!';
                } else {
                    $_SESSION['error'] = 'الكمية المطلوبة تتعدى المخزون المتوفر!';
                }
            }
        }

        if (class_exists('App') && !App::$isApi) {
            header('Location: ' . URLROOT . '/cart');
            exit();
        }

        $this->json(['status' => true, 'message' => 'Updated quantity']);
    }

    /**
     * Checkout and place the order.
     */
    public function checkout()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }

        $cartItems = $this->getCartDetails();
        
        if (empty($cartItems)) {
            $_SESSION['error'] = 'السلة فارغة حالياً!';
            header('Location: ' . URLROOT . '/cart');
            exit();
        }

        // التحقق من توفر المخزون قبل إتمام الطلب
        $productModel = new Product();
        foreach ($cartItems as $item) {
            $freshProduct = $productModel->getProductById($item['id']);
            if (!$freshProduct || $item['qty_in_cart'] > $freshProduct['qty']) {
                $_SESSION['error'] = 'عذراً، الكمية المطلوبة من "' . $item['name'] . '" غير متوفرة في المخزون!';
                header('Location: ' . URLROOT . '/cart');
                exit();
            }
        }

        // تحضير عناصر الطلب بالكمية الصحيحة (المطلوبة وليس المخزون)
        $orderItems = array_map(function($item) {
            return [
                'id'    => $item['id'],
                'price' => $item['price'],
                'qty'   => $item['qty_in_cart'], // الكمية التي طلبها المستخدم فعلياً
            ];
        }, $cartItems);

        $total = $this->getCartTotal($cartItems);
        $orderModel = new Order();

        $orderId = $orderModel->createOrder($_SESSION['user_id'], $total, $orderItems);

        if ($orderId) {
            // Clear cart
            unset($_SESSION['cart']);
            $_SESSION['success'] = 'تم إتمام الطلب بنجاح! شكراً لك.';
            header('Location: ' . URLROOT . '/orders');
            exit();
        } else {
            $_SESSION['error'] = 'حدث خطأ ما أثناء إتمام الطلب، يرجى المحاولة لاحقاً.';
            header('Location: ' . URLROOT . '/cart');
            exit();
        }
    }

    /**
     * Helper to get full cart details from session ids.
     */
    private function getCartDetails()
    {
        $cartItems = [];
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $productModel = new Product();
            foreach ($_SESSION['cart'] as $id => $qty) {
                $product = $productModel->getProductById($id);
                if ($product) {
                    $product['qty_in_cart'] = $qty;
                    $product['subtotal'] = $product['price'] * $qty;
                    $cartItems[] = $product;
                }
            }
        }
        return $cartItems;
    }

    /**
     * Helper to compute total cart cost.
     */
    private function getCartTotal($cartItems)
    {
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }
}

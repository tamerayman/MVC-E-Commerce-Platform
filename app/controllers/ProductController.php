<?php

class ProductController extends ApiController
{
    /**
     * List all products (with optional filtering by search and category).
     */
    public function index()
    {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $categoryId = isset($_GET['category_id']) ? trim($_GET['category_id']) : '';

        $productModel = new Product();
        $products = $productModel->getFilteredProducts($search, $categoryId);

        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();

        if (class_exists('App') && !App::$isApi) {
            if (!isset($_SESSION['user_id'])) {
                header('Location: ' . URLROOT . '/auth/login');
                exit();
            }

            View::load('product/index', [
                'products'    => $products,
                'categories'  => $categories,
                'search'      => $search,
                'category_id' => $categoryId
            ]);
            return;
        }

        // API mode
        $this->json([
            'status' => true,
            'data'   => $products
        ]);
    }

    /**
     * Show a single product details.
     */
    public function show($id)
    {
        $productModel = new Product();
        $product = $productModel->getProductById($id);

        if ($product) {
            $this->json([
                'status' => true,
                'data'   => $product
            ]);
        } else {
            $this->json([
                'status'  => false,
                'message' => 'Product not found'
            ], 404);
        }
    }

    /**
     * API store endpoint (Admin only).
     */
    public function store()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->json([
                'status'  => false,
                'message' => 'Forbidden: Admins only'
            ], 403);
            return;
        }

        $data = $this->body();
        $validator = new Validator();
        $errors = $validator->validate($data);

        if (!empty($errors)) {
            $this->json([
                'status' => false,
                'errors' => $errors
            ], 422);
            return;
        }

        $productModel = new Product();

        if ($productModel->addProduct($data)) {
            $this->json([
                'status'  => true,
                'message' => 'Created'
            ], 201);
        } else {
            $this->json([
                'status'  => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    /**
     * API update endpoint (Admin only).
     */
    public function update($id)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->json([
                'status'  => false,
                'message' => 'Forbidden: Admins only'
            ], 403);
            return;
        }

        $data = $this->body();
        $data['id'] = $id;
        $validator = new Validator();
        $errors = $validator->validate($data);

        if (!empty($errors)) {
            $this->json([
                'status' => false,
                'errors' => $errors
            ], 422);
            return;
        }

        $productModel = new Product();

        if ($productModel->updateProduct($data)) {
            $this->json([
                'status'  => true,
                'message' => 'Product updated successfully'
            ]);
        } else {
            $this->json([
                'status'  => false,
                'message' => 'Update failed'
            ], 500);
        }
    }

    /**
     * API destroy endpoint (Admin only).
     */
    public function destroy($id)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->json([
                'status'  => false,
                'message' => 'Forbidden: Admins only'
            ], 403);
            return;
        }

        $productModel = new Product();

        if ($productModel->deleteProduct($id)) {
            $this->json([
                'status'  => true,
                'message' => 'Product deleted successfully'
            ]);
        } else {
            $this->json([
                'status'  => false,
                'message' => 'Delete failed'
            ], 500);
        }
    }

    // --- Web Exclusive Methods (Admin Only checks apply where mutative/restricted) ---

    /**
     * Add a product form / save.
     */
    public function add()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }

        // Role authorization check
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = 'غير مسموح لك بإضافة منتجات. هذا الإجراء للمسؤولين فقط!';
            header('Location: ' . URLROOT . '/product');
            exit();
        }

        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->body();
            
            $validator = new Validator();
            $errors = $validator->validate($data);

            // Handle Product Image Upload
            $imageName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['image']['tmp_name'];
                $fileName = $_FILES['image']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (in_array($fileExtension, $allowedExtensions)) {
                    $imageName = md5(time() . $fileName) . '.' . $fileExtension;
                    $uploadDir = APPROOT . '/../public/uploads/';
                    
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $destPath = $uploadDir . $imageName;
                    move_uploaded_file($fileTmpPath, $destPath);
                } else {
                    $errors['image'] = 'صيغة الصورة غير مدعومة';
                }
            }

            if (!empty($errors)) {
                View::load('product/add', [
                    'errors'     => $errors,
                    'product'    => $data,
                    'categories' => $categories
                ]);
                return;
            }

            $data['image'] = $imageName;

            $productModel = new Product();
            if ($productModel->addProduct($data)) {
                $_SESSION['success'] = 'تم إضافة المنتج بنجاح!';
                header('Location: ' . URLROOT . '/product');
                exit();
            } else {
                View::load('product/add', [
                    'error'      => 'حدث خطأ ما أثناء حفظ المنتج',
                    'product'    => $data,
                    'categories' => $categories
                ]);
            }
        } else {
            View::load('product/add', ['categories' => $categories]);
        }
    }

    /**
     * Edit a product form / save.
     */
    public function edit($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }

        // Role authorization check
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = 'غير مسموح لك بتعديل المنتجات. هذا الإجراء للمسؤولين فقط!';
            header('Location: ' . URLROOT . '/product');
            exit();
        }

        $productModel = new Product();
        $product = $productModel->getProductById($id);

        if (!$product) {
            http_response_code(404);
            View::load('errors/404');
            return;
        }

        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->body();
            $data['id'] = $id;

            $validator = new Validator();
            $errors = $validator->validate($data);

            // Handle Product Image Upload
            $imageName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['image']['tmp_name'];
                $fileName = $_FILES['image']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (in_array($fileExtension, $allowedExtensions)) {
                    $imageName = md5(time() . $fileName) . '.' . $fileExtension;
                    $uploadDir = APPROOT . '/../public/uploads/';
                    
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $destPath = $uploadDir . $imageName;
                    move_uploaded_file($fileTmpPath, $destPath);
                } else {
                    $errors['image'] = 'صيغة الصورة غير مدعومة';
                }
            }

            if (!empty($errors)) {
                View::load('product/add', [
                    'errors'     => $errors,
                    'product'    => $data,
                    'categories' => $categories
                ]);
                return;
            }

            if ($imageName !== null) {
                $data['image'] = $imageName;
            } else {
                $data['image'] = null; // updateProduct handles null keeping the old one
            }

            if ($productModel->updateProduct($data)) {
                $_SESSION['success'] = 'تم تحديث المنتج بنجاح!';
                header('Location: ' . URLROOT . '/product');
                exit();
            } else {
                View::load('product/add', [
                    'error'      => 'حدث خطأ ما أثناء تحديث المنتج',
                    'product'    => $data,
                    'categories' => $categories
                ]);
            }
        } else {
            View::load('product/add', [
                'product'    => $product,
                'categories' => $categories
            ]);
        }
    }

    /**
     * Delete a product.
     */
    public function delete($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }

        // Role authorization check
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = 'غير مسموح لك بحذف المنتجات. هذا الإجراء للمسؤولين فقط!';
            header('Location: ' . URLROOT . '/product');
            exit();
        }

        $productModel = new Product();
        $productModel->deleteProduct($id);
        $_SESSION['success'] = 'تم حذف المنتج بنجاح!';
        header('Location: ' . URLROOT . '/product');
        exit();
    }
}
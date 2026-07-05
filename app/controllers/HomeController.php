<?php

class HomeController extends ApiController
{
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            if (class_exists('App') && !App::$isApi) {
                header('Location: ' . URLROOT . '/auth/login');
                exit();
            }
            $this->json([
                'status'  => false,
                'message' => 'Unauthorized'
            ], 401);
            return;
        }

        $userModel = new User();
        $user = $userModel->getUserById($_SESSION['user_id']);

        $productModel = new Product();
        $orderModel = new Order();

        $stats = [];
        if ($user['role'] === 'admin') {
            // Admin stats
            $allProducts = $productModel->getAllProducts();
            $allOrders = $orderModel->getAllOrders();
            $allUsers = $userModel->query("SELECT * FROM users");

            $totalEarnings = 0;
            foreach ($allOrders as $ord) {
                $totalEarnings += $ord['total_price'];
            }

            $stats = [
                'total_products' => count($allProducts),
                'total_orders'   => count($allOrders),
                'total_users'    => count($allUsers),
                'total_earnings' => $totalEarnings
            ];
        } else {
            // Customer stats
            $myOrders = $orderModel->getOrdersByUserId($_SESSION['user_id']);
            $allProducts = $productModel->getAllProducts();

            $stats = [
                'total_orders'   => count($myOrders),
                'total_products' => count($allProducts)
            ];
        }

        if (class_exists('App') && !App::$isApi) {
            View::load('home', [
                'user'  => $user,
                'stats' => $stats
            ]);
            return;
        }

        $this->json([
            'status'  => true,
            'user'    => $user,
            'stats'   => $stats
        ]);
    }
}

<?php

class OrdersController extends ApiController
{
    /**
     * Display order history.
     */
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }

        $userModel = new User();
        $user = $userModel->getUserById($_SESSION['user_id']);

        $orderModel = new Order();
        
        if ($user['role'] === 'admin') {
            // Admin sees all orders
            $orders = $orderModel->getAllOrders();
        } else {
            // Customers see only their own orders
            $orders = $orderModel->getOrdersByUserId($_SESSION['user_id']);
        }

        // Attach items to each order
        foreach ($orders as $index => $order) {
            $orders[$index]['items'] = $orderModel->getOrderItems($order['id']);
        }

        if (class_exists('App') && !App::$isApi) {
            View::load('orders/index', [
                'orders' => $orders,
                'user'   => $user
            ]);
            return;
        }

        // API Mode
        $this->json([
            'status' => true,
            'data'   => $orders
        ]);
    }
}

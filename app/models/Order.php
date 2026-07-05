<?php

/**
 * Order model — handles checkout transactions and order retrieval.
 */
class Order extends Database
{
    /**
     * Create an order with transaction safety.
     */
    public function createOrder($userId, $totalPrice, $items)
    {
        try {
            $this->dbh->beginTransaction();

            // 1. Insert order
            $sql = "INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'completed')";
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([$userId, $totalPrice]);
            $orderId = $this->dbh->lastInsertId();

            // 2. Insert order items & deduct product stock
            $itemSql = "INSERT INTO order_items (order_id, product_id, price, qty) VALUES (?, ?, ?, ?)";
            $itemStmt = $this->dbh->prepare($itemSql);

            $updateStockSql = "UPDATE products SET qty = qty - ? WHERE id = ?";
            $stockStmt = $this->dbh->prepare($updateStockSql);

            foreach ($items as $item) {
                $itemStmt->execute([
                    $orderId,
                    $item['id'],
                    $item['price'],
                    $item['qty']
                ]);

                $stockStmt->execute([
                    $item['qty'],
                    $item['id']
                ]);
            }

            $this->dbh->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->dbh->rollBack();
            return false;
        }
    }

    /**
     * Get orders for a specific user.
     */
    public function getOrdersByUserId($userId)
    {
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all orders in the system (Admin only).
     */
    public function getAllOrders()
    {
        return $this->query("
            SELECT o.*, u.name as user_name, u.email as user_email 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC
        ");
    }

    /**
     * Get items of a specific order.
     */
    public function getOrderItems($orderId)
    {
        $sql = "
            SELECT oi.*, p.name as product_name, p.image as product_image 
            FROM order_items oi 
            JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?
        ";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

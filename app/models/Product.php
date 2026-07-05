<?php

/**
 * Product model — handles all product CRUD operations.
 */
class Product extends Database
{
    /**
     * Get all products with their categories.
     */
    public function getAllProducts()
    {
        return $this->query("
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id
            ORDER BY p.id DESC
        ");
    }

    /**
     * Get products filtered by search and category.
     */
    public function getFilteredProducts($search = '', $categoryId = '')
    {
        $sql = "
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }

        if (!empty($categoryId)) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }

        $sql .= " ORDER BY p.id DESC";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Add a new product.
     */
    public function addProduct($data)
    {
        $sql = "INSERT INTO products (name, price, description, qty, category_id, image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->dbh->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['price'],
            $data['description'],
            $data['qty'],
            $data['category_id'] ?? null,
            $data['image'] ?? null
        ]);
    }

    /**
     * Get a single product by ID.
     */
    public function getProductById($id)
    {
        $sql = "
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.id = ?
        ";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Delete a product by ID.
     */
    public function deleteProduct($id)
    {
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $this->dbh->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Update an existing product.
     */
    public function updateProduct($data)
    {
        if (isset($data['image']) && $data['image'] !== null) {
            $sql = "UPDATE products SET name=?, price=?, description=?, qty=?, category_id=?, image=? WHERE id=?";
            $params = [
                $data['name'],
                $data['price'],
                $data['description'],
                $data['qty'],
                $data['category_id'] ?? null,
                $data['image'],
                $data['id']
            ];
        } else {
            $sql = "UPDATE products SET name=?, price=?, description=?, qty=?, category_id=? WHERE id=?";
            $params = [
                $data['name'],
                $data['price'],
                $data['description'],
                $data['qty'],
                $data['category_id'] ?? null,
                $data['id']
            ];
        }
        $stmt = $this->dbh->prepare($sql);
        return $stmt->execute($params);
    }
}
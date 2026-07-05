<?php

/**
 * Category model — handles all product category operations.
 */
class Category extends Database
{
    /**
     * Get all categories.
     */
    public function getAllCategories()
    {
        return $this->query("SELECT * FROM categories ORDER BY name ASC");
    }

    /**
     * Get a category by its ID.
     */
    public function getCategoryById($id)
    {
        $sql = "SELECT * FROM categories WHERE id = ?";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

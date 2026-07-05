<?php

/**
 * Database connection handler.
 * Auto-creates the database and tables if they don't exist.
 */
class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    protected $dbh;

    public function __construct()
    {
        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
            $this->dbh = new PDO($dsn, $this->user, $this->pass);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            if ($e->getCode() == 1049 || strpos($e->getMessage(), 'Unknown database') !== false) {
                $this->createDatabase();
            } else {
                die('Connection failed: ' . $e->getMessage());
            }
        }

        $this->createTablesIfNotExist();
    }

    /**
     * Auto-create the database when it doesn't exist.
     */
    private function createDatabase()
    {
        try {
            $dsn = 'mysql:host=' . $this->host . ';charset=utf8mb4';
            $pdo = new PDO($dsn, $this->user, $this->pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$this->dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
            $this->dbh = new PDO($dsn, $this->user, $this->pass);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Database auto-creation failed: ' . $e->getMessage());
        }
    }

    /**
     * Create required tables if they don't exist.
     */
    private function createTablesIfNotExist()
    {
        try {
            // 1. Create Users Table
            $this->dbh->exec("CREATE TABLE IF NOT EXISTS `users` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(255) NOT NULL,
                `email` VARCHAR(255) NOT NULL UNIQUE,
                `password` VARCHAR(255) NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;");

            // 2. Create Products Table
            $this->dbh->exec("CREATE TABLE IF NOT EXISTS `products` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(255) NOT NULL,
                `price` DECIMAL(10, 2) NOT NULL,
                `description` TEXT,
                `qty` INT NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;");

            // 3. Create Categories Table
            $this->dbh->exec("CREATE TABLE IF NOT EXISTS `categories` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(255) NOT NULL UNIQUE,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;");

            // 4. Seed Default Categories if empty
            $count = $this->dbh->query("SELECT COUNT(*) FROM `categories`")->fetchColumn();
            if ($count == 0) {
                $categories = ['Electronics', 'Fashion', 'Books', 'Home & Living'];
                $stmt = $this->dbh->prepare("INSERT INTO `categories` (`name`) VALUES (?)");
                foreach ($categories as $cat) {
                    $stmt->execute([$cat]);
                }
            }

            // 5. Safely alter Users table for 'role' and 'avatar'
            try {
                $this->dbh->query("SELECT `role` FROM `users` LIMIT 1");
            } catch (PDOException $e) {
                $this->dbh->exec("ALTER TABLE `users` ADD COLUMN `role` VARCHAR(50) DEFAULT 'customer';");
            }

            try {
                $this->dbh->query("SELECT `avatar` FROM `users` LIMIT 1");
            } catch (PDOException $e) {
                $this->dbh->exec("ALTER TABLE `users` ADD COLUMN `avatar` VARCHAR(255) DEFAULT NULL;");
            }

            // 6. Safely alter Products table for 'category_id' and 'image'
            try {
                $this->dbh->query("SELECT `category_id` FROM `products` LIMIT 1");
            } catch (PDOException $e) {
                $this->dbh->exec("ALTER TABLE `products` ADD COLUMN `category_id` INT DEFAULT NULL;");
            }

            try {
                $this->dbh->query("SELECT `image` FROM `products` LIMIT 1");
            } catch (PDOException $e) {
                $this->dbh->exec("ALTER TABLE `products` ADD COLUMN `image` VARCHAR(255) DEFAULT NULL;");
            }

            // 7. Create Orders Table
            $this->dbh->exec("CREATE TABLE IF NOT EXISTS `orders` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT NOT NULL,
                `total_price` DECIMAL(10, 2) NOT NULL,
                `status` VARCHAR(50) DEFAULT 'pending',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;");

            // 8. Create Order Items Table
            $this->dbh->exec("CREATE TABLE IF NOT EXISTS `order_items` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `order_id` INT NOT NULL,
                `product_id` INT NOT NULL,
                `price` DECIMAL(10, 2) NOT NULL,
                `qty` INT NOT NULL
            ) ENGINE=InnoDB;");

            // 9. Create Password Resets Table
            $this->dbh->exec("CREATE TABLE IF NOT EXISTS `password_resets` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `email` VARCHAR(255) NOT NULL,
                `token` VARCHAR(255) NOT NULL,
                `expires_at` DATETIME NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;");

        } catch (PDOException $e) {
            die('Table creation / migration failed: ' . $e->getMessage());
        }
    }

    /**
     * Execute a SELECT query and return all results.
     */
    public function query($sql)
    {
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
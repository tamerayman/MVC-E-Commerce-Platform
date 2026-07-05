# 🛒 MVC E-Commerce Platform

A fully-featured e-commerce store built from scratch using the **MVC (Model-View-Controller)** architecture pattern in PHP (without the use of ready-made frameworks). The project aims to provide a seamless shopping experience with a dashboard for managing products, a shopping cart system, and an order management system.

## ✨ Features

*   **Clean MVC Architecture:** A complete separation between Logic (Models), User Interface (Views), and Controllers, making the code easy to read and develop.
*   **Routing System:** A custom router to handle URLs elegantly and in an SEO-friendly manner (e.g., `/product/show/1`).
*   **Integrated Authentication System:** Login, account registration, and user session management with roles (Admin / Customer).
*   **Product Management (Admins Only):** The admin can add, edit, and delete products, as well as upload images.
*   **Product Browsing & Filtering:** An attractive interface for displaying products with the ability to search by name or description, and filter by categories.
*   **Shopping Cart:** Customers can add products, adjust quantities, and proceed to checkout, with automatic stock availability validation.
*   **Order System:** Recording orders and interactively deducting the purchased quantity from the inventory.
*   **Modern UI/UX Design:** A contemporary user interface featuring a Glassmorphism aesthetic that is responsive and compatible with all screens.

## 🛠️ Tech Stack

*   **Backend:** PHP (OOP & MVC)
*   **Database:** MySQL (PDO)
*   **Frontend:** HTML5, CSS3 (Vanilla), JavaScript
*   **Architecture:** Model-View-Controller

## 🚀 Local Installation

1.  Clone the repository.
2.  Move the project folder into your `htdocs` directory in XAMPP (or its equivalent).
3.  Create a new database in MySQL.
4.  Make sure to update the database connection credentials in the `app/config/config.php` file:
    ```php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'your_database_name');
    define('URLROOT', 'http://localhost/mvc/public');
    ```
5.  Run your project from the browser by visiting the URL: `http://localhost/mvc/public`.
*(Note: The database and its tables will be created and configured automatically on the first run thanks to the auto-migration feature in the Database code).*

## 📸 Screenshots

### 1. Home Page & Products
![Products](screenshots/home.png)

### 2. Shopping Cart
![Cart](screenshots/cart.png)

### 3. Admin Dashboard (Add Product)
![Add Product](screenshots/admin.png)

---
*Developed with ❤️ using PHP MVC.*

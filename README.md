# 🛒 MVC E-Commerce Platform

A fully-featured e-commerce store built from scratch using the **MVC (Model-View-Controller)** architecture pattern in PHP (without the use of ready-made frameworks). The project aims to provide a seamless shopping experience with a dashboard for managing products, a shopping cart system, and an order management system.

## ✨ Features

- **Built from Scratch using MVC Architecture:** Implemented a custom Model-View-Controller architecture with a clear separation of concerns for improved maintainability and scalability.
- **Custom Routing System:** Developed an SEO-friendly routing mechanism for clean and readable URLs (e.g., `/product/show/1`).
- **Authentication & Authorization:** Implemented secure user authentication, session management, and role-based access control for Administrators and Customers.
- **Product Management:** Built a complete admin dashboard for product CRUD operations and image uploads.
- **Product Catalog & Search:** Implemented product browsing, category filtering, and keyword-based search.
- **Shopping Cart:** Developed a dynamic shopping cart with quantity management and stock availability validation.
- **Order Management:** Implemented order processing with automatic inventory updates after successful checkout.
- **Responsive UI:** Designed a modern, responsive Glassmorphism-based user interface compatible with desktop and mobile devices.

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

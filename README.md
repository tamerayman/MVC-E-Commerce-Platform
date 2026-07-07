<h1 align="center">
🛒 MVC E-Commerce Platform
</h1>

<p align="center">
A full-stack e-commerce platform built entirely from scratch using a custom MVC (Model-View-Controller) architecture in pure PHP, without relying on any frameworks.
</p>

<p align="center">

<img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white"/>

<img src="https://img.shields.io/badge/MySQL-PDO-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>

<img src="https://img.shields.io/badge/MVC-Custom_Architecture-success?style=for-the-badge"/>

<img src="https://img.shields.io/badge/OOP-PHP-blue?style=for-the-badge"/>

<img src="https://img.shields.io/badge/Routing-Custom-orange?style=for-the-badge"/>

<img src="https://img.shields.io/badge/RBAC-Authorization-green?style=for-the-badge"/>

<img src="https://img.shields.io/badge/REST-Ready-red?style=for-the-badge"/>

<img src="https://img.shields.io/badge/PDO-SQL_Injection_Protection-yellow?style=for-the-badge"/>

<img src="https://img.shields.io/badge/Responsive-Glassmorphism-purple?style=for-the-badge"/>

<img src="https://img.shields.io/badge/Git-Version_Control-black?style=for-the-badge"/>

</p>

---

# 📖 Overview

MVC E-Commerce Platform is a complete online shopping system built entirely from scratch using pure PHP and a custom MVC architecture.

Unlike traditional PHP applications that rely on frameworks such as Laravel or Symfony, this project implements the core concepts behind modern frameworks, including routing, controllers, models, authentication, authorization, and database abstraction.

The project demonstrates a deep understanding of backend architecture while delivering a complete full-stack shopping experience.

---

# ✨ Features

| Module | Description |
|----------|-------------|
| 🏗 Custom MVC Architecture | Built from scratch without frameworks |
| 🛣 Custom Router | SEO-friendly dynamic routing system |
| 🔐 Authentication | Secure login, registration & session management |
| 🛡 Authorization | Role-Based Access Control (Admin & Customer) |
| 📦 Product Management | Complete CRUD with image uploads |
| 📂 Categories | Product categorization & filtering |
| 🔍 Search | Keyword-based product search |
| 🛒 Shopping Cart | Dynamic cart with quantity validation |
| 📦 Orders | Checkout & order processing |
| 📈 Inventory | Automatic stock updates after purchases |
| 👤 Profile Management | User profile & avatar upload |
| 🗄 Auto Database Setup | Automatic database/table creation |

---

# 🏗 System Architecture

```
                 Browser

                     │

              Custom Router

                     │

              Controllers

                     │

        ┌────────────┼────────────┐
        │            │            │

      Models       Views      Sessions

        │

        ▼

     MySQL Database
```

---

# 📦 Core Modules

## 🔐 Authentication

- User Registration
- Login
- Logout
- Password Hashing
- Session Management

---

## 👤 User Management

- User Profiles
- Avatar Upload
- Profile Editing
- Role Management

---

## 📦 Products

- Product CRUD
- Product Images
- Categories
- Search
- Product Details

---

## 🛒 Shopping Cart

- Add to Cart
- Update Quantity
- Remove Items
- Stock Validation

---

## 📋 Orders

- Checkout
- Order History
- Inventory Updates

---

## 🏗 Framework Components

- Custom MVC Architecture
- Custom Routing System
- PDO Database Layer
- Request Handling
- URL Parsing

---

# 🗃 Database

Current database includes:

- Users
- Products
- Categories
- Orders
- Order Items

The application automatically creates the required tables during the first run.

---

# ⚙ Technologies Used

- PHP (OOP)

- MySQL (PDO)

- HTML5

- CSS3

- JavaScript

- Custom MVC Architecture

- Session Authentication

- Role-Based Authorization

- Git

- GitHub

---

# 📂 Project Structure

```
app/

 Controllers/

 Models/

 Views/

 Core/

 Config/

 Helpers/

public/

assets/

uploads/

database/
```

---

# 🚀 Installation

Clone the repository

```bash
git clone https://github.com/tamerayman/MVC-E-Commerce-Platform.git
```

Move the project to your web server

```bash
htdocs/
```

Configure the database

```php
DB_HOST

DB_USER

DB_PASS

DB_NAME
```

Run the project

```
http://localhost/mvc/public
```

The database schema will be created automatically on the first run.

---

# 📸 Screenshots

## 🏠 Home Page

![Products](screenshots/home.png)

---

## 🛒 Shopping Cart

![Cart](screenshots/cart.png)

---

## 👨‍💼 Admin Dashboard

![Admin](screenshots/admin.png)

---

# 💡 What This Project Demonstrates

This project showcases:

- Building a custom MVC framework from scratch
- Deep understanding of Object-Oriented Programming
- Backend architecture design
- Authentication & Authorization
- Session Management
- Database abstraction using PDO
- Custom Routing
- Clean code organization
- Full-stack web application development

---

# 📄 License

This project is licensed under the MIT License.

---

<p align="center">

Developed with ❤️ by <strong>Tamer Ayman</strong>

</p>

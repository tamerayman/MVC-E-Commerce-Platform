<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر PHP MVC الإلكتروني</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/style.css">
    <!-- Google Fonts Outfit & Tajawal for professional Arabic typography -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', 'Outfit', sans-serif;
        }
        /* Custom dynamic badge styling */
        .cart-badge {
            background: #ef4444;
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 50%;
            margin-right: 5px;
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.6);
        }
        .user-nav-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid #7c3aed;
            object-fit: cover;
            vertical-align: middle;
            margin-right: 8px;
            box-shadow: 0 0 8px rgba(124, 58, 237, 0.4);
        }
        .user-role-badge {
            font-size: 10px;
            background: rgba(124, 58, 237, 0.15);
            color: #c084fc;
            border: 1px solid rgba(124, 58, 237, 0.3);
            padding: 2px 6px;
            border-radius: 4px;
            margin-right: 5px;
            font-weight: 700;
        }
        /* Flip navigation flex direction for RTL */
        .navbar ul {
            direction: ltr;
        }
        .navbar ul li {
            margin-right: 0;
            margin-left: 25px;
        }
    </style>
</head>
<body>

<?php
$cartCount = 0;
if (isset($_SESSION['cart'])) {
    $cartCount = array_sum($_SESSION['cart']);
}

$currentUser = null;
if (isset($_SESSION['user_id'])) {
    $userModel = new User();
    $currentUser = $userModel->getUserById($_SESSION['user_id']);
}
?>

<header class="main-header">
    <div class="container nav-wrapper">
        <div class="logo">
            <a href="<?php echo URLROOT; ?>/home">
                <img src="<?php echo URLROOT; ?>/assets/images/logo.png" alt="Logo" onerror="this.src='https://placehold.co/180x60/0f172a/c084fc?text=PHP+MVC+Store&font=playfair'">
            </a>
        </div>
        <nav class="navbar">
            <ul>
                <?php if (isset($_SESSION['user_id']) && $currentUser): ?>
                    <li>
                        <a href="<?php echo URLROOT; ?>/profile">
                            <?php if (!empty($currentUser['avatar'])): ?>
                                <img src="<?php echo URLROOT; ?>/<?php echo $currentUser['avatar']; ?>" class="user-nav-avatar" alt="Avatar">
                            <?php else: ?>
                                <img src="https://placehold.co/100x100/7c3aed/ffffff?text=<?php echo urlencode(mb_substr($currentUser['name'], 0, 1)); ?>" class="user-nav-avatar" alt="Avatar">
                            <?php endif; ?>
                            <span><?php echo htmlspecialchars($currentUser['name']); ?></span>
                            <?php if ($currentUser['role'] === 'admin'): ?>
                                <span class="user-role-badge">مشرف</span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li><a href="<?php echo URLROOT; ?>/home">الرئيسية</a></li>
                    <li><a href="<?php echo URLROOT; ?>/product">المنتجات</a></li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/cart">
                            السلة
                            <?php if ($cartCount > 0): ?>
                                <span class="cart-badge"><?php echo $cartCount; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li><a href="<?php echo URLROOT; ?>/orders">الطلبات</a></li>
                    
                    <?php if ($currentUser['role'] === 'admin'): ?>
                        <li><a href="<?php echo URLROOT; ?>/product/add" class="btn-add" style="padding: 6px 12px;">+ إضافة منتج</a></li>
                    <?php endif; ?>
                    
                    <li>
                        <a href="<?php echo URLROOT; ?>/auth/logout">خروج</a>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo URLROOT; ?>/auth/login">دخول</a></li>
                    <li><a href="<?php echo URLROOT; ?>/auth/register" class="btn-add" style="padding: 6px 12px;">تسجيل جديد</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
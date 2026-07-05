<?php require APPROOT . '/views/inc/header.php'; ?>

<main class="main-content">
    <div class="container">
        
        <!-- Alerts Block -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" style="margin-bottom: 20px;">
                <span>✅ <?php echo $_SESSION['success']; ?></span>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <span>⚠️ <?php echo $_SESSION['error']; ?></span>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <h2 style="font-size: 32px; font-weight: 700; background: linear-gradient(135deg, #ffffff 40%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 30px;">سلة المشتريات</h2>

        <?php if (!empty($cartItems) && is_array($cartItems)): ?>
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; align-items: start;">
                
                <!-- Items list -->
                <div class="table-container" style="margin-top: 0; padding: 10px;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th style="width: 150px; text-align: center;">السعر</th>
                                <th style="width: 150px; text-align: center;">الكمية</th>
                                <th style="width: 150px; text-align: center;">الإجمالي</th>
                                <th style="width: 100px; text-align: center;">إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 15px;">
                                            <?php if (!empty($item['image'])): ?>
                                                <?php if (strpos($item['image'], 'http') === 0): ?>
                                                    <img src="<?php echo $item['image']; ?>" alt="Product" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-glass);">
                                                <?php else: ?>
                                                    <img src="<?php echo URLROOT; ?>/uploads/<?php echo $item['image']; ?>" alt="Product" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-glass);">
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <img src="https://placehold.co/100x100/18182b/7c3aed?text=<?php echo urlencode($item['name']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-glass);">
                                            <?php endif; ?>
                                            <div>
                                                <strong style="color: #fff; display: block; font-size: 15px;"><?php echo htmlspecialchars($item['name']); ?></strong>
                                                <span style="font-size: 11px; color: var(--text-muted);"><?php echo htmlspecialchars($item['category_name'] ?? 'عام'); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align: center; color: #fff;">
                                        <strong><?php echo number_format($item['price'], 2); ?> $</strong>
                                    </td>
                                    <td style="text-align: center;">
                                        <!-- Quantity Update Form -->
                                        <form action="<?php echo URLROOT; ?>/cart/update/<?php echo $item['id']; ?>" method="POST" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                                            <input type="number" name="qty" value="<?php echo $item['qty_in_cart']; ?>" min="1" max="<?php echo $item['qty']; ?>" style="width: 60px; padding: 6px 8px; text-align: center; background: rgba(255,255,255,0.03); border: 1px solid var(--border-glass); border-radius: 8px; color: #fff;" onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td style="text-align: center; color: #34d399;">
                                        <strong><?php echo number_format($item['subtotal'], 2); ?> $</strong>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="<?php echo URLROOT; ?>/cart/remove/<?php echo $item['id']; ?>" class="btn-delete" style="padding: 6px 12px; font-size: 11px; border-radius: 6px;">حذف</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Cart summary card -->
                <div class="form-card" style="margin: 0; padding: 30px;">
                    <h3 style="font-size: 20px; font-weight: 700; color: #fff; margin-bottom: 20px; border-bottom: 1px solid var(--border-glass); padding-bottom: 15px;">ملخص الطلب</h3>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                        <span style="color: var(--text-secondary);">عدد المنتجات</span>
                        <strong style="color: #fff;"><?php echo count($cartItems); ?> فريد</strong>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 30px; border-bottom: 1px solid var(--border-glass); padding-bottom: 15px;">
                        <span style="color: var(--text-secondary); font-size: 16px;">المجموع الإجمالي</span>
                        <strong style="color: #34d399; font-size: 24px;"><?php echo number_format($total, 2); ?> $</strong>
                    </div>

                    <a href="<?php echo URLROOT; ?>/cart/checkout" class="btn-primary" style="width: 100%; text-align: center; padding: 14px; text-decoration: none; font-size: 16px; display: block; border-radius: 12px;">✅ إتمام الشراء الآن</a>
                    
                    <a href="<?php echo URLROOT; ?>/product" style="display: block; text-align: center; margin-top: 15px; color: var(--text-secondary); text-decoration: none; font-size: 13px; transition: color 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--text-secondary)'">← استمرار في التسوق</a>
                </div>

            </div>
        <?php else: ?>
            <!-- Empty Cart view -->
            <div class="form-card" style="text-align: center; padding: 60px; max-width: 100%; border: 1px solid var(--border-glass);">
                <div style="font-size: 70px; margin-bottom: 20px; filter: drop-shadow(0 0 10px rgba(124, 58, 237, 0.4));">🛒</div>
                <h3 style="font-size: 22px; color: #fff; margin-bottom: 10px;">سلة التسوق فارغة حالياً!</h3>
                <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 30px;">تصفح كتالوج المنتجات وأضف ما يعجبك إلى السلة لتتمكن من الشراء.</p>
                <a href="<?php echo URLROOT; ?>/product" class="btn-primary">🛍️ تصفح المنتجات الآن</a>
            </div>
        <?php endif; ?>

    </div>
</main>

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

        <div style="margin-bottom: 30px;">
            <h2 style="font-size: 32px; font-weight: 700; background: linear-gradient(135deg, #ffffff 40%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                <?php echo ($user['role'] === 'admin') ? 'سجلات طلبات المتجر' : 'سجل طلباتي الشخصية'; ?>
            </h2>
            <p style="color: var(--text-secondary); font-size: 14px; margin-top: 5px;">
                <?php echo ($user['role'] === 'admin') ? 'عرض وإدارة كافة الطلبات والعمليات المالية في النظام' : 'تتبع تفاصيل وحالة مشترياتك السابقة'; ?>
            </p>
        </div>

        <?php if (!empty($orders) && is_array($orders)): ?>
            <div style="display: flex; flex-direction: column; gap: 25px;">
                <?php foreach ($orders as $order): ?>
                    <div class="form-card" style="margin: 0; max-width: 100%; padding: 25px; border: 1px solid var(--border-glass);">
                        
                        <!-- Order Header metadata -->
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-glass); padding-bottom: 15px; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                            <div>
                                <span style="font-size: 12px; color: var(--text-secondary); display: block;">رقم الطلب</span>
                                <strong style="color: #a78bfa; font-size: 16px;">#<?php echo $order['id']; ?></strong>
                            </div>
                            
                            <?php if ($user['role'] === 'admin'): ?>
                                <div>
                                    <span style="font-size: 12px; color: var(--text-secondary); display: block;">العميل</span>
                                    <strong style="color: #fff; font-size: 14px;"><?php echo htmlspecialchars($order['user_name']); ?></strong>
                                    <span style="font-size: 11px; color: var(--text-muted);">(<?php echo htmlspecialchars($order['user_email']); ?>)</span>
                                </div>
                            <?php endif; ?>

                            <div>
                                <span style="font-size: 12px; color: var(--text-secondary); display: block;">تاريخ الطلب</span>
                                <span style="color: #fff; font-size: 14px;"><?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></span>
                            </div>

                            <div>
                                <span style="font-size: 12px; color: var(--text-secondary); display: block;">حالة الطلب</span>
                                <span class="badge badge-in-stock" style="background: rgba(52, 211, 153, 0.1); color: #34d399; font-size: 11px;">
                                    <?php echo ($order['status'] === 'completed') ? 'تم التسليم' : htmlspecialchars($order['status']); ?>
                                </span>
                            </div>

                            <div>
                                <span style="font-size: 12px; color: var(--text-secondary); display: block;">إجمالي المدفوعات</span>
                                <strong style="color: #34d399; font-size: 18px;"><?php echo number_format($order['total_price'], 2); ?> $</strong>
                            </div>
                        </div>

                        <!-- Order items breakdown -->
                        <h4 style="font-size: 14px; color: var(--text-secondary); margin-bottom: 12px; font-weight: 700;">تفاصيل المنتجات:</h4>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <?php if (!empty($order['items']) && is_array($order['items'])): ?>
                                <?php foreach ($order['items'] as $item): ?>
                                    <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02); padding: 10px 15px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.03);">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <?php if (!empty($item['product_image'])): ?>
                                                <img src="<?php echo URLROOT; ?>/uploads/<?php echo $item['product_image']; ?>" alt="Product" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-glass);">
                                            <?php else: ?>
                                                <img src="https://placehold.co/100x100/18182b/7c3aed?text=<?php echo urlencode($item['product_name']); ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-glass);">
                                            <?php endif; ?>
                                            <div>
                                                <strong style="color: #fff; font-size: 14px;"><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                                <span style="font-size: 11px; color: var(--text-muted); display: block;">سعر القطعة: <?php echo number_format($item['price'], 2); ?> $</span>
                                            </div>
                                        </div>
                                        <div style="text-align: left;">
                                            <span style="color: var(--text-secondary); font-size: 13px;">الكمية: </span>
                                            <strong style="color: #fff; font-size: 14px;"><?php echo $item['qty']; ?></strong>
                                            <span style="margin: 0 10px; color: var(--text-muted);">|</span>
                                            <span style="color: var(--text-secondary); font-size: 13px;">المجموع: </span>
                                            <strong style="color: #34d399; font-size: 14px;"><?php echo number_format($item['price'] * $item['qty'], 2); ?> $</strong>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p style="font-size: 12px; color: var(--text-muted);">لا توجد تفاصيل متاحة لهذه العناصر.</p>
                            <?php endif; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- No Orders view -->
            <div class="form-card" style="text-align: center; padding: 60px; max-width: 100%; border: 1px solid var(--border-glass);">
                <div style="font-size: 70px; margin-bottom: 20px; filter: drop-shadow(0 0 10px rgba(124, 58, 237, 0.4));">📦</div>
                <h3 style="font-size: 22px; color: #fff; margin-bottom: 10px;">لا يوجد أي طلبات مسجلة!</h3>
                <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 30px;">
                    <?php echo ($user['role'] === 'admin') ? 'لم يقم أي عميل بالشراء من المتجر بعد.' : 'لم تقم بإجراء أي طلبات شراء بعد. تصفح المتجر وأجرِ طلبك الأول!'; ?>
                </p>
                <?php if ($user['role'] !== 'admin'): ?>
                    <a href="<?php echo URLROOT; ?>/product" class="btn-primary">🛍️ تصفح المنتجات الآن</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</main>

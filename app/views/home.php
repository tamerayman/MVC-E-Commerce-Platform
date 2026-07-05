<?php require APPROOT . '/views/inc/header.php'; ?>

<main class="main-content">
    
    <!-- Hero Welcoming Section -->
    <section class="hero-section" style="padding: 60px 0 40px 0;">
        <div class="container">
            <div style="display: flex; flex-direction: column; align-items: center; gap: 15px; margin-bottom: 20px;">
                <?php if (!empty($user['avatar'])): ?>
                    <img src="<?php echo URLROOT; ?>/<?php echo $user['avatar']; ?>" alt="Avatar" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid #7c3aed; box-shadow: var(--accent-glow);">
                <?php else: ?>
                    <img src="https://placehold.co/200x200/7c3aed/ffffff?text=<?php echo urlencode(mb_substr($user['name'], 0, 1)); ?>" alt="Avatar" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid #7c3aed; box-shadow: var(--accent-glow);">
                <?php endif; ?>
            </div>
            
            <h1 style="font-size: 42px; font-weight: 800; background: linear-gradient(135deg, #ffffff 30%, #a78bfa 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                مرحباً بك، <?php echo htmlspecialchars($user['name']); ?>! 👋
            </h1>
            <p style="color: var(--text-secondary); font-size: 16px; max-width: 600px; margin: 10px auto 30px;">
                <?php echo ($user['role'] === 'admin') ? 'أهلاً بك في لوحة تحكم مشرف المتجر. إليك نظرة سريعة على إحصاءات النظام اليوم.' : 'سعداء برؤيتك مجدداً! يمكنك تصفح المنتجات وإدارة سلة المشتريات ومتابعة طلباتك السابقة من هنا.'; ?>
            </p>
            <div style="display: flex; justify-content: center; gap: 15px;">
                <a href="<?php echo URLROOT; ?>/product" class="btn-primary" style="padding: 12px 28px;">🛍️ تصفح كتالوج المنتجات</a>
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="<?php echo URLROOT; ?>/product/add" class="btn-primary" style="padding: 12px 28px; background: rgba(167, 139, 250, 0.1); color: #c084fc; border: 1px solid rgba(167, 139, 250, 0.2); box-shadow: none;">+ إضافة منتج جديد</a>
                <?php else: ?>
                    <a href="<?php echo URLROOT; ?>/orders" class="btn-primary" style="padding: 12px 28px; background: rgba(167, 139, 250, 0.1); color: #c084fc; border: 1px solid rgba(167, 139, 250, 0.2); box-shadow: none;">📋 تتبع طلباتي</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Dashboard Statistics Grid -->
    <div class="container">
        <div style="margin-bottom: 20px;">
            <h3 style="font-size: 20px; font-weight: 700; color: #fff; border-right: 4px solid #7c3aed; padding-right: 12px;">إحصائيات لوحة التحكم</h3>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 30px; margin-top: 20px;">
            
            <?php if ($user['role'] === 'admin'): ?>
                
                <!-- Admin Card 1: Products -->
                <div class="form-card" style="margin: 0; max-width: 100%; text-align: center; padding: 30px; border: 1px solid var(--border-glass); transition: all 0.3s ease;">
                    <div style="font-size: 40px; margin-bottom: 10px; filter: drop-shadow(0 0 10px rgba(124, 58, 237, 0.3));">📦</div>
                    <span style="color: var(--text-secondary); font-size: 13px; display: block; margin-bottom: 5px;">إجمالي المنتجات</span>
                    <strong style="font-size: 32px; color: #fff;"><?php echo $stats['total_products']; ?></strong>
                    <a href="<?php echo URLROOT; ?>/product" style="display: block; margin-top: 15px; font-size: 13px; color: #a78bfa; text-decoration: none;">إدارة المنتجات ←</a>
                </div>

                <!-- Admin Card 2: Orders -->
                <div class="form-card" style="margin: 0; max-width: 100%; text-align: center; padding: 30px; border: 1px solid var(--border-glass); transition: all 0.3s ease;">
                    <div style="font-size: 40px; margin-bottom: 10px; filter: drop-shadow(0 0 10px rgba(52, 211, 153, 0.3));">📋</div>
                    <span style="color: var(--text-secondary); font-size: 13px; display: block; margin-bottom: 5px;">إجمالي الطلبات</span>
                    <strong style="font-size: 32px; color: #fff;"><?php echo $stats['total_orders']; ?></strong>
                    <a href="<?php echo URLROOT; ?>/orders" style="display: block; margin-top: 15px; font-size: 13px; color: #34d399; text-decoration: none;">عرض الطلبات ←</a>
                </div>

                <!-- Admin Card 3: Users -->
                <div class="form-card" style="margin: 0; max-width: 100%; text-align: center; padding: 30px; border: 1px solid var(--border-glass); transition: all 0.3s ease;">
                    <div style="font-size: 40px; margin-bottom: 10px; filter: drop-shadow(0 0 10px rgba(245, 158, 11, 0.3));">👥</div>
                    <span style="color: var(--text-secondary); font-size: 13px; display: block; margin-bottom: 5px;">المستخدمين المسجلين</span>
                    <strong style="font-size: 32px; color: #fff;"><?php echo $stats['total_users']; ?></strong>
                    <span style="display: block; margin-top: 15px; font-size: 13px; color: var(--text-muted);">حسابات نشطة في النظام</span>
                </div>

                <!-- Admin Card 4: Earnings -->
                <div class="form-card" style="margin: 0; max-width: 100%; text-align: center; padding: 30px; border: 1px solid var(--border-glass); transition: all 0.3s ease;">
                    <div style="font-size: 40px; margin-bottom: 10px; filter: drop-shadow(0 0 10px rgba(239, 68, 68, 0.3));">💰</div>
                    <span style="color: var(--text-secondary); font-size: 13px; display: block; margin-bottom: 5px;">إجمالي الأرباح</span>
                    <strong style="font-size: 32px; color: #34d399;"><?php echo number_format($stats['total_earnings'], 2); ?> $</strong>
                    <span style="display: block; margin-top: 15px; font-size: 13px; color: var(--text-muted);">عائدات المبيعات الناجحة</span>
                </div>

            <?php else: ?>
                
                <!-- Customer Card 1: Orders -->
                <div class="form-card" style="margin: 0; max-width: 100%; text-align: center; padding: 40px; border: 1px solid var(--border-glass); transition: all 0.3s ease;">
                    <div style="font-size: 45px; margin-bottom: 15px; filter: drop-shadow(0 0 10px rgba(124, 58, 237, 0.3));">📋</div>
                    <span style="color: var(--text-secondary); font-size: 14px; display: block; margin-bottom: 5px;">طلباتي المكتملة</span>
                    <strong style="font-size: 36px; color: #fff;"><?php echo $stats['total_orders']; ?></strong>
                    <a href="<?php echo URLROOT; ?>/orders" style="display: block; margin-top: 20px; font-size: 14px; color: #a78bfa; text-decoration: none; font-weight: 700;">عرض تفاصيل وحالة الطلبات ←</a>
                </div>

                <!-- Customer Card 2: Catalog Products -->
                <div class="form-card" style="margin: 0; max-width: 100%; text-align: center; padding: 40px; border: 1px solid var(--border-glass); transition: all 0.3s ease;">
                    <div style="font-size: 45px; margin-bottom: 15px; filter: drop-shadow(0 0 10px rgba(52, 211, 153, 0.3));">🛍️</div>
                    <span style="color: var(--text-secondary); font-size: 14px; display: block; margin-bottom: 5px;">منتجات متوفرة بالتسوق</span>
                    <strong style="font-size: 36px; color: #fff;"><?php echo $stats['total_products']; ?></strong>
                    <a href="<?php echo URLROOT; ?>/product" style="display: block; margin-top: 20px; font-size: 14px; color: #34d399; text-decoration: none; font-weight: 700;">ابدأ التسوق الآن والطلب ←</a>
                </div>

            <?php endif; ?>

        </div>
    </div>

</main>
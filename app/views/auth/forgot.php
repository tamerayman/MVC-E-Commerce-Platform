<?php require APPROOT . '/views/inc/header.php'; ?>

<main class="main-content">
    <div class="container">
        
        <div class="form-card" style="margin: 0 auto; max-width: 500px; border: 1px solid var(--border-glass);">
            
            <div class="text-center" style="margin-bottom: 30px;">
                <h2 style="font-size: 28px; font-weight: 700; background: linear-gradient(135deg, #ffffff 40%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">استعادة كلمة المرور</h2>
                <p style="color: var(--text-secondary); font-size: 14px; margin-top: 5px;">أدخل بريدك الإلكتروني للحصول على رابط استعادة الحساب</p>
            </div>

            <!-- Alerts Block -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" style="margin-bottom: 20px;">
                    <span>⚠️ <?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" style="margin-bottom: 20px; flex-direction: column; align-items: flex-start; gap: 10px;">
                    <span>✅ <?php echo $_SESSION['success']; ?></span>
                    
                    <?php if (isset($_SESSION['simulated_link'])): ?>
                        <div style="background: rgba(0, 0, 0, 0.2); width: 100%; padding: 12px; border-radius: 8px; border: 1px solid rgba(16, 185, 129, 0.3); margin-top: 5px; word-break: break-all;">
                            <span style="display: block; font-size: 11px; color: var(--text-secondary); margin-bottom: 5px; font-weight: 700;">📨 محاكاة البريد الإلكتروني (بيئة التطوير المحلية):</span>
                            <a href="<?php echo $_SESSION['simulated_link']; ?>" style="color: #67e8f9; font-weight: 700; font-size: 13px; text-decoration: underline;" target="_blank">
                                انقر هنا لفتح رابط استعادة كلمة المرور 🔗
                            </a>
                        </div>
                        <?php unset($_SESSION['simulated_link']); ?>
                    <?php endif; ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form action="<?php echo URLROOT; ?>/forgotpassword/forgot" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                
                <div class="form-group">
                    <label>البريد الإلكتروني المسجل</label>
                    <input type="email" name="email" placeholder="name@example.com" value="<?php echo htmlspecialchars($email ?? ''); ?>" required style="direction: ltr; text-align: right;">
                </div>

                <div>
                    <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; font-size: 15px; border-radius: 12px;">📩 إرسال رابط الاستعادة</button>
                </div>

                <p class="auth-helper">
                    تذكرت كلمة المرور؟ 
                    <a href="<?php echo URLROOT; ?>/auth/login">تسجيل الدخول</a>
                </p>

            </form>
        </div>
    </div>
</main>

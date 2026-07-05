<?php require APPROOT . '/views/inc/header.php'; ?>

<main class="main-content">
    <div class="container">
        
        <div class="form-card" style="margin: 0 auto; max-width: 500px; border: 1px solid var(--border-glass);">
            
            <div class="text-center" style="margin-bottom: 30px;">
                <h2 style="font-size: 28px; font-weight: 700; background: linear-gradient(135deg, #ffffff 40%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">إعادة تعيين كلمة المرور</h2>
                <p style="color: var(--text-secondary); font-size: 14px; margin-top: 5px;">أدخل كلمة المرور الجديدة لحسابك</p>
            </div>

            <!-- Alerts Block -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" style="margin-bottom: 20px;">
                    <span>⚠️ <?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form action="<?php echo URLROOT; ?>/forgotpassword/reset/<?php echo $token; ?>" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                
                <div class="form-group">
                    <label>كلمة المرور الجديدة</label>
                    <input type="password" name="password" placeholder="أدخل 6 أحرف على الأقل" required>
                    <?php if (!empty($errors['password'])): ?>
                        <span style="color: #f87171; font-size: 12px; margin-top: 4px; display: block;"><?php echo $errors['password']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>تأكيد كلمة المرور الجديدة</label>
                    <input type="password" name="confirm_password" placeholder="أعد كتابة كلمة المرور" required>
                    <?php if (!empty($errors['confirm_password'])): ?>
                        <span style="color: #f87171; font-size: 12px; margin-top: 4px; display: block;"><?php echo $errors['confirm_password']; ?></span>
                    <?php endif; ?>
                </div>

                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                <div>
                    <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; font-size: 15px; border-radius: 12px;">🔐 تعيين كلمة المرور الجديدة</button>
                </div>

            </form>
        </div>
    </div>
</main>

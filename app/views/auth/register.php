<?php require APPROOT . '/views/inc/header.php'; ?>

<main class="main-content">
    <div class="container">
        <div class="form-card" style="margin: 0 auto; max-width: 500px; border: 1px solid var(--border-glass);">
            
            <div class="text-center" style="margin-bottom: 30px;">
                <h2 style="font-size: 30px; font-weight: 700; background: linear-gradient(135deg, #ffffff 40%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">إنشاء حساب جديد</h2>
                <p style="color: var(--text-secondary); font-size: 14px; margin-top: 5px;">ابدأ الآن وقم بإنشاء حسابك الخاص للتسوق والمتابعة</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" style="margin-bottom: 20px;">
                    <span>⚠️ <?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo URLROOT; ?>/auth/register" style="display: flex; flex-direction: column; gap: 20px;">
                <div class="form-group">
                    <label>الاسم الكامل</label>
                    <input type="text" name="name" placeholder="أدخل اسمك الكامل"
                           value="<?php echo $data['name'] ?? ''; ?>" required>
                    <?php if (!empty($errors['name'])): ?>
                        <span style="color: #f87171; font-size: 13px; margin-top: 4px; display: block;"><?php echo $errors['name']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>البريد الإلكتروني</label>
                    <input type="email" name="email" placeholder="name@example.com"
                           value="<?php echo $data['email'] ?? ''; ?>" required style="direction: ltr; text-align: right;">
                    <?php if (!empty($errors['email'])): ?>
                        <span style="color: #f87171; font-size: 13px; margin-top: 4px; display: block;"><?php echo $errors['email']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>كلمة المرور</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                    <?php if (!empty($errors['password'])): ?>
                        <span style="color: #f87171; font-size: 13px; margin-top: 4px; display: block;"><?php echo $errors['password']; ?></span>
                    <?php endif; ?>
                </div>

                <div>
                    <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; font-size: 15px; border-radius: 12px; margin-top: 10px;">إنشاء الحساب 🚀</button>
                </div>
            </form>

            <p class="auth-helper">
                لديك حساب بالفعل؟
                <a href="<?php echo URLROOT; ?>/auth/login">تسجيل الدخول هنا</a>
            </p>
        </div>
    </div>
</main>
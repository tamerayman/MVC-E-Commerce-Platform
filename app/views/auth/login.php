<?php require APPROOT . '/views/inc/header.php'; ?>

<main class="main-content">
    <div class="container">
        <div class="form-card" style="margin: 0 auto; max-width: 500px; border: 1px solid var(--border-glass);">
            
            <div class="text-center" style="margin-bottom: 30px;">
                <h2 style="font-size: 30px; font-weight: 700; background: linear-gradient(135deg, #ffffff 40%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">تسجيل الدخول</h2>
                <p style="color: var(--text-secondary); font-size: 14px; margin-top: 5px;">أهلاً بك مجدداً! قم بتسجيل الدخول للمتابعة</p>
            </div>

            <!-- Session alerts -->
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

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" style="margin-bottom: 20px;">
                    <span>⚠️ <?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo URLROOT; ?>/auth/login" style="display: flex; flex-direction: column; gap: 20px;">
                <div class="form-group">
                    <label>البريد الإلكتروني</label>
                    <input type="email" name="email" placeholder="name@example.com"
                           value="<?php echo $data['email'] ?? ''; ?>" required style="direction: ltr; text-align: right;">
                    <?php if (!empty($errors['email'])): ?>
                        <span style="color: #f87171; font-size: 13px; margin-top: 4px; display: block;"><?php echo $errors['email']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label>كلمة المرور</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                    <?php if (!empty($errors['password'])): ?>
                        <span style="color: #f87171; font-size: 13px; margin-top: 4px; display: block;"><?php echo $errors['password']; ?></span>
                    <?php endif; ?>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 5px;">
                    <a href="<?php echo URLROOT; ?>/forgotpassword/forgot" style="color: #a78bfa; text-decoration: none; font-size: 13px; font-weight: 500; transition: color 0.3s;" onmouseover="this.style.color='#c084fc'" onmouseout="this.style.color='#a78bfa'">نسيت كلمة المرور؟</a>
                </div>

                <div>
                    <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; font-size: 15px; border-radius: 12px; margin-top: 10px;">تسجيل الدخول 🔑</button>
                </div>
            </form>

            <p class="auth-helper">
                ليس لديك حساب؟
                <a href="<?php echo URLROOT; ?>/auth/register">إنشاء حساب جديد</a>
            </p>
        </div>
    </div>
</main>
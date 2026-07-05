<?php require APPROOT . '/views/inc/header.php'; ?>

<main class="main-content">
    <div class="container">
        
        <!-- Alerts Block -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" style="margin-bottom: 20px; max-width: 650px; margin-left: auto; margin-right: auto;">
                <span>✅ <?php echo $_SESSION['success']; ?></span>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px; max-width: 650px; margin-left: auto; margin-right: auto;">
                <span>⚠️ <?php echo $_SESSION['error']; ?></span>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="form-card" style="margin: 0 auto; max-width: 650px; border: 1px solid var(--border-glass);">
            
            <div class="text-center" style="margin-bottom: 40px;">
                <h2 style="font-size: 30px; font-weight: 700; background: linear-gradient(135deg, #ffffff 40%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">الملف الشخصي</h2>
                <p style="color: var(--text-secondary); font-size: 14px; margin-top: 5px;">تحديث بيانات الحساب وتخصيص صورتك الشخصية</p>
            </div>

            <form action="<?php echo URLROOT; ?>/profile" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 20px;">
                
                <!-- Avatar Section -->
                <div style="display: flex; flex-direction: column; align-items: center; gap: 15px; margin-bottom: 20px; border-bottom: 1px solid var(--border-glass); padding-bottom: 25px;">
                    <div style="position: relative; width: 110px; height: 110px;">
                        <?php if (!empty($user['avatar'])): ?>
                            <img src="<?php echo URLROOT; ?>/<?php echo $user['avatar']; ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; border: 3px solid #7c3aed; box-shadow: var(--accent-glow);">
                        <?php else: ?>
                            <img src="https://placehold.co/200x200/7c3aed/ffffff?text=<?php echo urlencode(mb_substr($user['name'], 0, 1)); ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; border: 3px solid #7c3aed; box-shadow: var(--accent-glow);">
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 0; text-align: center;">
                        <label style="cursor: pointer; background: rgba(124, 58, 237, 0.1); border: 1px solid rgba(124, 58, 237, 0.3); color: #c084fc; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; transition: all 0.3s;" onmouseover="this.style.background='rgba(124, 58, 237, 0.2)'" onmouseout="this.style.background='rgba(124, 58, 237, 0.1)'">
                            📁 رفع صورة شخصية جديدة
                            <input type="file" name="avatar" accept="image/*" style="display: none;" onchange="document.getElementById('avatar-hint').innerText = 'تم اختيار الصورة: ' + this.files[0].name">
                        </label>
                        <span id="avatar-hint" style="display: block; font-size: 11px; color: var(--text-muted); margin-top: 5px;">صيغ مدعومة: JPG, PNG, WEBP</span>
                        <?php if (!empty($errors['avatar'])): ?>
                            <span style="color: #f87171; font-size: 12px; margin-top: 4px; display: block;"><?php echo $errors['avatar']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Profile details -->
                <div class="form-group">
                    <label>الاسم الكامل</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required placeholder="أدخل اسمك الكامل">
                    <?php if (!empty($errors['name'])): ?>
                        <span style="color: #f87171; font-size: 12px; margin-top: 4px; display: block;"><?php echo $errors['name']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>البريد الإلكتروني</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required placeholder="name@example.com" style="direction: ltr; text-align: right;">
                    <?php if (!empty($errors['email'])): ?>
                        <span style="color: #f87171; font-size: 12px; margin-top: 4px; display: block;"><?php echo $errors['email']; ?></span>
                    <?php endif; ?>
                </div>

                <!-- Password update sections -->
                <div style="border-top: 1px solid var(--border-glass); padding-top: 25px; margin-top: 10px;">
                    <h3 style="font-size: 16px; color: #fff; margin-bottom: 15px; font-weight: 700;">تغيير كلمة المرور (اختياري)</h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label>كلمة المرور الجديدة</label>
                            <input type="password" name="password" placeholder="••••••••">
                            <?php if (!empty($errors['password'])): ?>
                                <span style="color: #f87171; font-size: 12px; margin-top: 4px; display: block;"><?php echo $errors['password']; ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label>تأكيد كلمة المرور</label>
                            <input type="password" name="confirm_password" placeholder="••••••••">
                            <?php if (!empty($errors['confirm_password'])): ?>
                                <span style="color: #f87171; font-size: 12px; margin-top: 4px; display: block;"><?php echo $errors['confirm_password']; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; font-size: 16px; border-radius: 12px;">💾 حفظ التغييرات والملف الشخصي</button>
                </div>

            </form>
        </div>
    </div>
</main>

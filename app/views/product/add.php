<?php require APPROOT . '/views/inc/header.php'; ?>

<main class="main-content">
    <div class="container">
        <div class="form-card" style="margin: 0 auto; max-width: 600px;">
            <div class="text-center">
                <h2><?php echo isset($product['id']) ? 'تعديل المنتج' : 'إضافة منتج جديد'; ?></h2>
                <p style="color: var(--text-secondary); font-size: 14px; margin-top: 5px;">
                    <?php echo isset($product['id']) ? 'قم بتحديث بيانات المنتج المسجل في النظام' : 'أدخل بيانات المنتج الجديد في مستودع المتجر'; ?>
                </p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger" style="margin-bottom: 20px;">
                    <span>⚠️ <?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form action="<?php echo URLROOT; ?>/product/<?php echo isset($product['id']) ? 'edit/' . $product['id'] : 'add'; ?>" method="POST" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label>اسم المنتج</label>
                    <input type="text" name="name" value="<?php echo isset($product) ? htmlspecialchars($product['name']) : ''; ?>" placeholder="مثال: سماعة لاسلكية ذكية" required>
                    <?php if (!empty($errors['name'])): ?>
                        <span style="color: #f87171; font-size: 13px; margin-top: 4px; display: block;"><?php echo $errors['name']; ?></span>
                    <?php endif; ?>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>السعر ($)</label>
                        <input type="number" name="price" step="0.01" value="<?php echo isset($product) ? htmlspecialchars($product['price']) : ''; ?>" placeholder="0.00" required>
                        <?php if (!empty($errors['price'])): ?>
                            <span style="color: #f87171; font-size: 13px; margin-top: 4px; display: block;"><?php echo $errors['price']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>الكمية المتوفرة بالمخزن</label>
                        <input type="number" name="qty" value="<?php echo isset($product) ? htmlspecialchars($product['qty']) : ''; ?>" placeholder="0" required>
                        <?php if (!empty($errors['qty'])): ?>
                            <span style="color: #f87171; font-size: 13px; margin-top: 4px; display: block;"><?php echo $errors['qty']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>تصنيف المنتج</label>
                    <select name="category_id" style="width: 100%; padding: 14px 18px; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-glass); border-radius: 12px; color: #fff; font-family: inherit; outline: none; transition: all 0.3s ease;">
                        <option value="" style="background: var(--bg-main); color: #fff;">اختر تصنيفاً...</option>
                        <?php if (!empty($categories) && is_array($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo (isset($product['category_id']) && $product['category_id'] == $cat['id']) ? 'selected' : ''; ?> style="background: var(--bg-main); color: #fff;">
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>صورة المنتج</label>
                    <input type="file" name="image" accept="image/*" style="padding: 10px 14px;">
                    <?php if (isset($product) && !empty($product['image'])): ?>
                        <div style="margin-top: 15px;">
                            <span style="font-size: 12px; color: var(--text-secondary); display: block; margin-bottom: 5px;">الصورة الحالية للمنتج:</span>
                            <img src="<?php echo URLROOT; ?>/uploads/<?php echo $product['image']; ?>" alt="Current Image" style="max-width: 160px; max-height: 120px; object-fit: cover; border-radius: 10px; border: 1px solid var(--border-glass); box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($errors['image'])): ?>
                        <span style="color: #f87171; font-size: 13px; margin-top: 4px; display: block;"><?php echo $errors['image']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>وصف تفصيلي للمنتج</label>
                    <textarea name="description" rows="4" placeholder="اكتب مواصفات المنتج ومميزاته بالتفصيل هنا..."><?php echo isset($product) ? htmlspecialchars($product['description'] ?? '') : ''; ?></textarea>
                </div>

                <?php if (isset($product['id'])): ?>
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                <?php endif; ?>

                <div style="display: flex; gap: 15px; margin-top: 30px; flex-direction: row-reverse;">
                    <button type="submit" class="btn-primary" style="flex: 2;">حفظ بيانات المنتج</button>
                    <a href="<?php echo URLROOT; ?>/product" class="btn-primary" style="flex: 1; text-align: center; background: rgba(255,255,255,0.05); color: var(--text-primary); border: 1px solid var(--border-glass); box-shadow: none; display: flex; align-items: center; justify-content: center; text-decoration: none;">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</main>
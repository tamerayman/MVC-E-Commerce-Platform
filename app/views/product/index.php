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

        <!-- Storefront Header & Action -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
            <div>
                <h2 style="font-size: 32px; font-weight: 700; background: linear-gradient(135deg, #ffffff 40%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">كتالوج المنتجات</h2>
                <p style="color: var(--text-secondary); font-size: 14px; margin-top: 5px;">تصفح أفضل العروض والمنتجات الحصرية</p>
            </div>
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <a href="<?php echo URLROOT; ?>/product/add" class="btn-primary" style="padding: 10px 20px; font-size: 14px;">+ إضافة منتج جديد</a>
            <?php endif; ?>
        </div>

        <!-- Search and Filter Panel -->
        <div class="form-card" style="margin: 0 0 40px 0; max-width: 100%; padding: 25px;">
            <form action="<?php echo URLROOT; ?>/product" method="GET" style="display: grid; grid-template-columns: 2fr 1fr auto; gap: 15px; align-items: end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label>بحث بالاسم أو الوصف</label>
                    <input type="text" name="search" placeholder="اكتب للبحث..." value="<?php echo htmlspecialchars($search ?? ''); ?>" style="padding: 11px 15px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label>التصنيفات</label>
                    <select name="category_id" style="width: 100%; padding: 11px 15px; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-glass); border-radius: 12px; color: #fff; font-family: inherit; outline: none; transition: all 0.3s ease;">
                        <option value="" style="background: var(--bg-main); color: #fff;">كل التصنيفات</option>
                        <?php if (!empty($categories) && is_array($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo (isset($category_id) && $category_id == $cat['id']) ? 'selected' : ''; ?> style="background: var(--bg-main); color: #fff;">
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn-primary" style="padding: 12px 24px; font-size: 14px;">بحث</button>
                    <?php if (!empty($search) || !empty($category_id)): ?>
                        <a href="<?php echo URLROOT; ?>/product" class="btn-delete" style="padding: 12px 20px; font-size: 14px; text-decoration: none; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center;">إلغاء</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Catalog Products Grid -->
        <?php if (!empty($products) && is_array($products)): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px;">
                <?php foreach ($products as $product): ?>
                    <div class="form-card" style="margin: 0; max-width: 100%; padding: 0; overflow: hidden; display: flex; flex-direction: column; height: 100%; transition: transform 0.3s ease, box-shadow 0.3s ease; border: 1px solid var(--border-glass);" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 30px rgba(124, 58, 237, 0.15)';" onmouseout="this.style.transform='none'; this.style.boxShadow='none';">
                        
                        <!-- Product Image -->
                        <div style="position: relative; width: 100%; height: 200px; background: rgba(255, 255, 255, 0.02); display: flex; align-items: center; justify-content: center; border-bottom: 1px solid var(--border-glass);">
                            <?php if (!empty($product['image'])): ?>
                                <?php if (strpos($product['image'], 'http') === 0): ?>
                                    <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <img src="<?php echo URLROOT; ?>/uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php endif; ?>
                            <?php else: ?>
                                <img src="https://placehold.co/400x300/18182b/7c3aed?text=<?php echo urlencode($product['name']); ?>" alt="Placeholder" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php endif; ?>

                            <!-- Category Badge -->
                            <?php if (!empty($product['category_name'])): ?>
                                <span style="position: absolute; top: 12px; right: 12px; background: rgba(124, 58, 237, 0.85); color: #fff; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; backdrop-filter: blur(4px);">
                                    <?php echo htmlspecialchars($product['category_name']); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Product Content -->
                        <div style="padding: 20px; display: flex; flex-direction: column; flex-grow: 1;">
                            
                            <!-- Stock Indicator -->
                            <div style="margin-bottom: 10px;">
                                <?php if ($product['qty'] >= 10): ?>
                                    <span class="badge badge-in-stock">متوفر (<?php echo $product['qty']; ?>)</span>
                                <?php elseif ($product['qty'] > 0): ?>
                                    <span class="badge badge-low-stock">كمية محدودة (<?php echo $product['qty']; ?>)</span>
                                <?php else: ?>
                                    <span class="badge badge-out-of-stock">نفد المخزون</span>
                                <?php endif; ?>
                            </div>

                            <h3 style="font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 8px; line-height: 1.4;">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </h3>

                            <p style="color: var(--text-secondary); font-size: 13px; margin-bottom: 20px; flex-grow: 1; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; height: 55px;">
                                <?php echo htmlspecialchars($product['description'] ?? 'لا يوجد وصف متاح للمنتج.'); ?>
                            </p>

                            <!-- Price & Actions Footer -->
                            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-glass); padding-top: 15px; margin-top: auto;">
                                <div>
                                    <span style="display: block; font-size: 11px; color: var(--text-muted);">السعر</span>
                                    <strong style="font-size: 20px; color: #34d399;"><?php echo number_format($product['price'], 2); ?> $</strong>
                                </div>

                                <div style="display: flex; gap: 8px;">
                                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                        <a href="<?php echo URLROOT; ?>/product/edit/<?php echo $product['id']; ?>" class="btn-edit" style="padding: 8px 12px; font-size: 12px; border-radius: 6px;">تعديل</a>
                                        <a href="<?php echo URLROOT; ?>/product/delete/<?php echo $product['id']; ?>" class="btn-delete" onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')" style="padding: 8px 12px; font-size: 12px; border-radius: 6px;">حذف</a>
                                    <?php else: ?>
                                        <?php if ($product['qty'] > 0): ?>
                                            <a href="<?php echo URLROOT; ?>/cart/add/<?php echo $product['id']; ?>" class="btn-primary" style="padding: 8px 14px; font-size: 12px; border-radius: 6px; white-space: nowrap;">🛍️ أضف للسلة</a>
                                        <?php else: ?>
                                            <button class="btn-primary" style="padding: 8px 14px; font-size: 12px; border-radius: 6px; background: rgba(255, 255, 255, 0.05); color: var(--text-muted); border: 1px solid var(--border-glass); cursor: not-allowed; box-shadow: none;" disabled>غير متوفر</button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="form-card" style="text-align: center; padding: 60px; max-width: 100%;">
                <div style="font-size: 60px; margin-bottom: 20px; filter: drop-shadow(0 0 10px rgba(124, 58, 237, 0.4));">📦</div>
                <h3 style="font-size: 22px; color: #fff; margin-bottom: 10px;">لم يتم العثور على أي منتجات</h3>
                <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 25px;">حاول كتابة كلمة بحث أخرى أو تصفح تصنيف مختلف.</p>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="<?php echo URLROOT; ?>/product/add" class="btn-primary">إضافة منتجك الأول</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</main>
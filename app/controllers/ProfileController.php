<?php

class ProfileController extends ApiController
{
    /**
     * User profile edit page.
     */
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }

        $userModel = new User();
        $user = $userModel->getUserById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->body();
            
            // Clean inputs
            $name = trim($data['name'] ?? '');
            $email = trim($data['email'] ?? '');
            $password = trim($data['password'] ?? '');
            $confirmPassword = trim($data['confirm_password'] ?? '');

            $errors = [];

            if (empty($name)) {
                $errors['name'] = 'الاسم مطلوب';
            }

            if (empty($email)) {
                $errors['email'] = 'البريد الإلكتروني مطلوب';
            }

            // Check if email already used by someone else
            $existingUser = $userModel->findUserByEmail($email);
            if ($existingUser && $existingUser['id'] != $_SESSION['user_id']) {
                $errors['email'] = 'البريد الإلكتروني مستخدم بالفعل';
            }

            // Password update validation
            if (!empty($password)) {
                if (strlen($password) < 6) {
                    $errors['password'] = 'يجب أن تكون كلمة المرور 6 أحرف على الأقل';
                }
                if ($password !== $confirmPassword) {
                    $errors['confirm_password'] = 'كلمتا المرور غير متطابقتين';
                }
            }

            // Avatar Upload handling
            $avatarPath = null;
            if (isset($_FILES['avatar'])) {
                if ($_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES['avatar']['tmp_name'];
                    $fileName = $_FILES['avatar']['name'];
                    $fileNameCmps = explode(".", $fileName);
                    $fileExtension = strtolower(end($fileNameCmps));
                    
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    if (in_array($fileExtension, $allowedExtensions)) {
                        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                        $uploadDir = APPROOT . '/../public/uploads/';
                        
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        
                        $destPath = $uploadDir . $newFileName;
                        if (move_uploaded_file($fileTmpPath, $destPath)) {
                            $avatarPath = 'uploads/' . $newFileName;
                        } else {
                            $errors['avatar'] = 'فشل تحميل الصورة بسبب مشكلة في الصلاحيات.';
                        }
                    } else {
                        $errors['avatar'] = 'صيغة الصورة غير مدعومة (فقط: jpg, jpeg, png, gif, webp)';
                    }
                } elseif ($_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
                    // Handle specific upload errors
                    $uploadErrors = [
                        UPLOAD_ERR_INI_SIZE   => 'حجم الصورة يتجاوز الحد المسموح به في إعدادات الخادم (php.ini).',
                        UPLOAD_ERR_FORM_SIZE  => 'حجم الصورة يتجاوز الحد المسموح به في النموذج.',
                        UPLOAD_ERR_PARTIAL    => 'تم رفع الصورة جزئياً فقط.',
                        UPLOAD_ERR_NO_TMP_DIR => 'مجلد الرفع المؤقت غير موجود.',
                        UPLOAD_ERR_CANT_WRITE => 'فشلت كتابة الصورة إلى القرص.',
                        UPLOAD_ERR_EXTENSION  => 'تم إيقاف رفع الصورة بسبب إضافة برمجية.'
                    ];
                    $errorCode = $_FILES['avatar']['error'];
                    $errors['avatar'] = $uploadErrors[$errorCode] ?? 'حدث خطأ غير معروف أثناء رفع الصورة.';
                }
            }

            if (empty($errors)) {
                // Prepare update payload
                $updatePayload = [
                    'name'  => $name,
                    'email' => $email
                ];

                if ($avatarPath !== null) {
                    $updatePayload['avatar'] = $avatarPath;
                }

                $userModel->updateProfile($_SESSION['user_id'], $updatePayload);

                // Update password if provided
                if (!empty($password)) {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $userModel->updatePassword($_SESSION['user_id'], $hashed);
                }

                $_SESSION['success'] = 'تم تحديث الملف الشخصي بنجاح!';
                header('Location: ' . URLROOT . '/profile');
                exit();
            } else {
                View::load('profile/index', [
                    'user'   => array_merge($user, [
                        'name'  => $name,
                        'email' => $email
                    ]),
                    'errors' => $errors
                ]);
                return;
            }
        }

        View::load('profile/index', ['user' => $user]);
    }
}

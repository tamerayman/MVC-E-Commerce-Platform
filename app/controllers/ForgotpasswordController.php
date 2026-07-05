<?php

class ForgotpasswordController extends ApiController
{
    /**
     * Request password reset form / email submission.
     */
    public function forgot()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/home');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->body();
            $email = trim($data['email'] ?? '');

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                View::load('auth/forgot', ['error' => 'الرجاء إدخال بريد إلكتروني صحيح']);
                return;
            }

            $userModel = new User();
            $user = $userModel->findUserByEmail($email);

            if ($user) {
                // Generate secure random token
                $token = bin2hex(random_bytes(32));
                $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Save to database
                $userModel->saveResetToken($email, $token, $expiresAt);

                // Build recovery link
                $resetLink = URLROOT . '/forgotpassword/reset/' . $token;

                $_SESSION['success'] = 'تم إنشاء رابط استعادة كلمة المرور بنجاح!';
                $_SESSION['simulated_link'] = $resetLink; // For easy local developer simulation
            } else {
                View::load('auth/forgot', [
                    'error' => 'هذا البريد الإلكتروني غير مسجل لدينا',
                    'email' => $email
                ]);
                return;
            }
        }

        View::load('auth/forgot');
    }

    /**
     * Reset password using token.
     */
    public function reset($token)
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/home');
            exit();
        }

        $userModel = new User();
        $resetRecord = $userModel->validateResetToken($token);

        if (!$resetRecord) {
            View::load('auth/forgot', ['error' => 'رابط استعادة كلمة المرور غير صالح أو انتهت صلاحيته. الرجاء طلب رابط جديد.']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->body();
            $password = trim($data['password'] ?? '');
            $confirmPassword = trim($data['confirm_password'] ?? '');

            $errors = [];

            if (empty($password) || strlen($password) < 6) {
                $errors['password'] = 'يجب أن تكون كلمة المرور 6 أحرف على الأقل';
            }

            if ($password !== $confirmPassword) {
                $errors['confirm_password'] = 'كلمتا المرور غير متطابقتين';
            }

            if (empty($errors)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                // Update password & clear tokens
                $userModel->updatePasswordByEmail($resetRecord['email'], $hashedPassword);
                $userModel->deleteResetToken($resetRecord['email']);

                $_SESSION['success'] = 'تم تغيير كلمة المرور بنجاح! يمكنك الآن تسجيل الدخول باستخدام كلمة المرور الجديدة.';
                header('Location: ' . URLROOT . '/auth/login');
                exit();
            } else {
                View::load('auth/reset', [
                    'token'  => $token,
                    'errors' => $errors
                ]);
                return;
            }
        }

        View::load('auth/reset', ['token' => $token]);
    }
}

<?php

class AuthController extends ApiController
{
    public function register()
    {
        if (class_exists('App') && !App::$isApi) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = $this->body();
                $validator = new Validator();
                $errors = $validator->validateRegister($data);

                if (!empty($errors)) {
                    View::load('auth/register', ['errors' => $errors, 'data' => $data]);
                    return;
                }

                $userModel = new User();
                if ($userModel->findUserByEmail($data['email'])) {
                    View::load('auth/register', [
                        'error' => 'الايميل مسجل بالفعل',
                        'data'  => $data
                    ]);
                    return;
                }

                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if ($userModel->register($data)) {
                    header('Location: ' . URLROOT . '/auth/login');
                    exit();
                } else {
                    View::load('auth/register', [
                        'error' => 'حدث خطأ أثناء التسجيل، يرجى المحاولة لاحقاً',
                        'data'  => $data
                    ]);
                }
            } else {
                View::load('auth/register');
            }
            return;
        }

        // API Mode
        $data = $this->body();
        $validator = new Validator();
        $errors = $validator->validateRegister($data);

        if (!empty($errors)) {
            $this->json([
                'status' => false,
                'errors' => $errors
            ], 422);
            return;
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $userModel = new User();

        if ($userModel->register($data)) {
            $this->json([
                'status'  => true,
                'message' => 'User registered successfully'
            ]);
        } else {
            $this->json([
                'status'  => false,
                'message' => 'Registration failed'
            ], 500);
        }
    }

    public function login()
    {
        if (class_exists('App') && !App::$isApi) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = $this->body();
                $validator = new Validator();
                $errors = $validator->validateLogin($data);

                if (!empty($errors)) {
                    View::load('auth/login', ['errors' => $errors, 'data' => $data]);
                    return;
                }

                $userModel = new User();
                $user = $userModel->findUserByEmail($data['email']);

                if ($user && password_verify($data['password'], $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_role'] = $user['role'] ?? 'customer';
                    header('Location: ' . URLROOT . '/home');
                    exit();
                } else {
                    View::load('auth/login', [
                        'error' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
                        'data'  => $data
                    ]);
                }
            } else {
                View::load('auth/login');
            }
            return;
        }

        // API Mode
        $data = $this->body();
        $validator = new Validator();
        $errors = $validator->validateLogin($data);

        if (!empty($errors)) {
            $this->json([
                'status' => false,
                'errors' => $errors
            ], 422);
            return;
        }

        $userModel = new User();
        $user = $userModel->findUserByEmail($data['email']);

        if ($user && password_verify($data['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'] ?? 'customer';
            $this->json([
                'status'  => true,
                'message' => 'Login successful'
            ]);
        } else {
            $this->json([
                'status'  => false,
                'message' => 'Invalid email or password'
            ], 401);
        }
    }

    public function logout()
    {
        session_destroy();

        if (class_exists('App') && !App::$isApi) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }

        $this->json([
            'status'  => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
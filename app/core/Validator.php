<?php

/**
 * Input validator for forms and API requests.
 */
class Validator
{
    protected $errors = [];

    /**
     * Validate product data.
     */
    public function validate($data)
    {
        if (empty($data['name'])) {
            $this->errors['name'] = 'الاسم مطلوب';
        }

        if (!is_numeric($data['price'])) {
            $this->errors['price'] = 'السعر لازم يكون رقم';
        }

        if (!is_numeric($data['qty'])) {
            $this->errors['qty'] = 'الكمية لازم تكون رقم';
        }

        return $this->errors;
    }

    /**
     * Validate registration data.
     */
    public function validateRegister($data)
    {
        if (empty($data['name'])) {
            $this->errors['name'] = 'الاسم مطلوب';
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'ايميل غير صحيح';
        }

        if (empty($data['password']) || strlen($data['password']) < 4) {
            $this->errors['password'] = 'كلمة المرور لازم تكون 4 أحرف على الأقل';
        }

        return $this->errors;
    }

    /**
     * Validate login data.
     */
    public function validateLogin($data)
    {
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'ايميل غير صحيح';
        }

        if (empty($data['password'])) {
            $this->errors['password'] = 'كلمة المرور مطلوبة';
        }

        return $this->errors;
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }
}
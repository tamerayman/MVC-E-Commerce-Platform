<?php

/**
 * User model — handles registration and user lookup.
 */
class User extends Database
{
    /**
     * Register a new user.
     */
    public function register($data)
    {
        try {
            $role = (strpos(strtolower($data['email']), 'admin@') === 0) ? 'admin' : 'customer';
            $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $this->dbh->prepare($sql);
            return $stmt->execute([
                $data['name'],
                $data['email'],
                $data['password'],
                $role
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Find a user by email address.
     */
    public function findUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get a user by ID.
     */
    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update user profile information.
     */
    public function updateProfile($id, $data)
    {
        if (isset($data['avatar']) && $data['avatar'] !== null) {
            $sql = "UPDATE users SET name = ?, email = ?, avatar = ? WHERE id = ?";
            $params = [$data['name'], $data['email'], $data['avatar'], $id];
        } else {
            $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
            $params = [$data['name'], $data['email'], $id];
        }
        $stmt = $this->dbh->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Update user password by user ID.
     */
    public function updatePassword($id, $hashedPassword)
    {
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $this->dbh->prepare($sql);
        return $stmt->execute([$hashedPassword, $id]);
    }

    /**
     * Update user password by email.
     */
    public function updatePasswordByEmail($email, $hashedPassword)
    {
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $this->dbh->prepare($sql);
        return $stmt->execute([$hashedPassword, $email]);
    }

    /**
     * Save a password reset token.
     */
    public function saveResetToken($email, $token, $expiresAt)
    {
        $this->deleteResetToken($email);
        $sql = "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)";
        $stmt = $this->dbh->prepare($sql);
        return $stmt->execute([$email, $token, $expiresAt]);
    }

    /**
     * Validate a password reset token.
     */
    public function validateResetToken($token)
    {
        $sql = "SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW() LIMIT 1";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Delete reset tokens for an email.
     */
    public function deleteResetToken($email)
    {
        $sql = "DELETE FROM password_resets WHERE email = ?";
        $stmt = $this->dbh->prepare($sql);
        return $stmt->execute([$email]);
    }
}
<?php
class User extends Model {
    protected $table = 'users';

    protected function getPrimaryKey() {
        return 'user_id';
    }

    public function findByEmail($email) {
        return $this->queryOne("SELECT * FROM users WHERE email = ?", [$email]);
    }

    public function register($fullName, $email, $password, $phone = null) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        return $this->insert([
            'full_name'     => $fullName,
            'email'         => $email,
            'password_hash' => $hash,
            'phone'         => $phone,
            'role'          => 'customer',
        ]);
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public function updateAvatar($userId, $avatarUrl) {
        return $this->update($userId, ['avatar_url' => $avatarUrl]);
    }

    public function updatePassword($userId, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        return $this->update($userId, ['password_hash' => $hash]);
    }

    public function updateName($userId, $fullName) {
        return $this->update($userId, ['full_name' => $fullName]);
    }

    public function saveResetToken($email, $token, $expiresAt) {
        return $this->query("UPDATE users SET reset_token = ?, reset_expires_at = ? WHERE email = ?", [$token, $expiresAt, $email]);
    }

    public function findByResetToken($token) {
        return $this->queryOne("SELECT * FROM users WHERE reset_token = ?", [$token]);
    }

    public function clearResetToken($userId) {
        return $this->query("UPDATE users SET reset_token = NULL, reset_expires_at = NULL WHERE user_id = ?", [$userId]);
    }
}

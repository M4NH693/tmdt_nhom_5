<?php
class User extends Model {
    protected $table = 'users';

    protected function getPrimaryKey() {
        return 'user_id';
    }

    public function findByEmail($email) {
        return $this->queryOne("SELECT * FROM users WHERE email = ?", [$email]);
    }

    public function register($fullName, $email, $password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        return $this->insert([
            'full_name'     => $fullName,
            'email'         => $email,
            'password_hash' => $hash,
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
}

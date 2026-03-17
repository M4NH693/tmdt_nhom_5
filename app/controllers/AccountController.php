<?php
/**
 * AccountController - Customer Dashboard
 */
class AccountController extends Controller {

    /**
     * Hiển thị dashboard tài khoản
     */
    public function dashboard() {
        $this->requireLogin();

        $userModel = $this->model('User');
        $user = $userModel->findById($_SESSION['user_id']);

        $this->view('account/dashboard', [
            'pageTitle' => 'Tài khoản của tôi - BookStore',
            'user'      => $user,
        ]);
    }

    /**
     * Cập nhật avatar
     */
    public function updateAvatar() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('account');
            return;
        }

        // Validate file upload
        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $this->setFlash('error', 'Vui lòng chọn file ảnh hợp lệ.');
            $this->redirect('account');
            return;
        }

        $file = $_FILES['avatar'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            $this->setFlash('error', 'Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WEBP).');
            $this->redirect('account');
            return;
        }

        if ($file['size'] > $maxSize) {
            $this->setFlash('error', 'File ảnh không được vượt quá 5MB.');
            $this->redirect('account');
            return;
        }

        // Generate unique filename
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
        $uploadDir = BASE_PATH . '/public/images/avatars/';

        // Create directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destination = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $avatarUrl = '/images/avatars/' . $filename;

            $userModel = $this->model('User');
            $userModel->updateAvatar($_SESSION['user_id'], $avatarUrl);
            $_SESSION['user_avatar'] = $avatarUrl;

            $this->setFlash('success', 'Cập nhật avatar thành công!');
        } else {
            $this->setFlash('error', 'Không thể tải ảnh lên. Vui lòng thử lại.');
        }

        $this->redirect('account');
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('account');
            return;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword      = $_POST['new_password'] ?? '';
        $confirmPassword  = $_POST['confirm_password'] ?? '';

        // Validate
        $userModel = $this->model('User');
        $user = $userModel->findById($_SESSION['user_id']);

        if (!$userModel->verifyPassword($currentPassword, $user->password_hash)) {
            $this->setFlash('error', 'Mật khẩu hiện tại không đúng.');
            $this->redirect('account');
            return;
        }

        if (strlen($newPassword) < 6) {
            $this->setFlash('error', 'Mật khẩu mới phải có ít nhất 6 ký tự.');
            $this->redirect('account');
            return;
        }

        if ($newPassword !== $confirmPassword) {
            $this->setFlash('error', 'Mật khẩu xác nhận không khớp.');
            $this->redirect('account');
            return;
        }

        $userModel->updatePassword($_SESSION['user_id'], $newPassword);
        $this->setFlash('success', 'Đổi mật khẩu thành công!');
        $this->redirect('account');
    }
}

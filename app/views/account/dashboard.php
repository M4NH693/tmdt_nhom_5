<?php
/**
 * Customer Dashboard - Trang tài khoản khách hàng
 */
?>
<div class="account-page">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Trang chủ</a>
            <i class="fas fa-chevron-right"></i>
            <span class="current">Tài khoản của tôi</span>
        </div>

        <div class="account-header">
            <h1><i class="fas fa-user-cog"></i> Tài khoản của tôi</h1>
            <p class="account-subtitle">Quản lý thông tin cá nhân và bảo mật tài khoản</p>
        </div>

        <!-- Flash Messages -->
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= $error ?></div>
        <?php endif; ?>

        <div class="account-layout">
            <!-- LEFT: Avatar & Quick Info -->
            <div class="account-sidebar">
                <?php if ($user->role === 'admin'): ?>
                    <a href="<?= BASE_URL ?>/admin" class="btn-admin-dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard quản lý
                    </a>
                <?php endif; ?>
                <div class="avatar-card">
                    <div class="avatar-wrapper" id="avatarWrapper">
                        <?php if (!empty($user->avatar_url)): ?>
                            <img src="<?= BASE_URL . $user->avatar_url ?>" alt="Avatar" class="avatar-image" id="avatarPreview">
                        <?php else: ?>
                            <div class="avatar-placeholder" id="avatarPlaceholder">
                                <?= strtoupper(mb_substr($user->full_name, 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        <form action="<?= BASE_URL ?>/account/avatar" method="POST" enctype="multipart/form-data" id="avatarForm">
                            <label class="avatar-overlay" for="avatarInput">
                                <i class="fas fa-camera"></i>
                                <span>Đổi ảnh</span>
                            </label>
                            <input type="file" id="avatarInput" name="avatar" accept="image/*" hidden>
                        </form>
                    </div>
                    <h3 class="avatar-name"><?= htmlspecialchars($user->full_name) ?></h3>
                    <span class="avatar-role">
                        <i class="fas fa-<?= $user->role === 'admin' ? 'shield-alt' : 'user' ?>"></i>
                        <?= $user->role === 'admin' ? 'Quản trị viên' : 'Khách hàng' ?>
                    </span>
                    <div class="avatar-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Tham gia <?= date('d/m/Y', strtotime($user->created_at)) ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-envelope"></i>
                            <span><?= htmlspecialchars($user->email) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Info & Password -->
            <div class="account-content">
                <!-- Thông tin tài khoản (readonly) -->
                <div class="account-card">
                    <div class="card-header">
                        <h2><i class="fas fa-id-card"></i> Thông tin tài khoản</h2>
                        <span class="badge-readonly"><i class="fas fa-lock"></i> Chỉ đọc</span>
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <label><i class="fas fa-user"></i> Họ và tên</label>
                            <div class="info-value"><?= htmlspecialchars($user->full_name) ?></div>
                        </div>
                        <div class="info-item">
                            <label><i class="fas fa-envelope"></i> Email</label>
                            <div class="info-value"><?= htmlspecialchars($user->email) ?></div>
                        </div>
                        <div class="info-item">
                            <label><i class="fas fa-phone"></i> Số điện thoại</label>
                            <div class="info-value"><?= !empty($user->phone) ? htmlspecialchars($user->phone) : '<span class="text-muted">Chưa cập nhật</span>' ?></div>
                        </div>
                        <div class="info-item">
                            <label><i class="fas fa-user-tag"></i> Vai trò</label>
                            <div class="info-value">
                                <span class="role-badge role-<?= $user->role ?>">
                                    <?= $user->role === 'admin' ? 'Quản trị viên' : 'Khách hàng' ?>
                                </span>
                            </div>
                        </div>
                        <div class="info-item">
                            <label><i class="fas fa-calendar-plus"></i> Ngày tạo tài khoản</label>
                            <div class="info-value"><?= date('H:i - d/m/Y', strtotime($user->created_at)) ?></div>
                        </div>
                        <div class="info-item">
                            <label><i class="fas fa-clock"></i> Cập nhật lần cuối</label>
                            <div class="info-value"><?= date('H:i - d/m/Y', strtotime($user->updated_at)) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Đổi mật khẩu -->
                <div class="account-card">
                    <div class="card-header">
                        <h2><i class="fas fa-shield-alt"></i> Đổi mật khẩu</h2>
                    </div>
                    <form action="<?= BASE_URL ?>/account/password" method="POST" class="password-form" id="passwordForm">
                        <div class="form-group">
                            <label for="current_password"><i class="fas fa-key"></i> Mật khẩu hiện tại</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="current_password" name="current_password" 
                                       class="form-control" placeholder="Nhập mật khẩu hiện tại" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('current_password', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="new_password"><i class="fas fa-lock"></i> Mật khẩu mới</label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="new_password" name="new_password" 
                                           class="form-control" placeholder="Tối thiểu 6 ký tự" required minlength="6">
                                    <button type="button" class="password-toggle" onclick="togglePassword('new_password', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password"><i class="fas fa-check-double"></i> Xác nhận mật khẩu</label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="confirm_password" name="confirm_password" 
                                           class="form-control" placeholder="Nhập lại mật khẩu mới" required minlength="6">
                                    <button type="button" class="password-toggle" onclick="togglePassword('confirm_password', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="password-hint">
                            <i class="fas fa-info-circle"></i>
                            Mật khẩu phải có ít nhất 6 ký tự. Nên kết hợp chữ hoa, chữ thường, số và ký tự đặc biệt.
                        </div>
                        <button type="submit" class="btn btn-primary btn-change-password">
                            <i class="fas fa-save"></i> Cập nhật mật khẩu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-submit avatar form on file select
document.getElementById('avatarInput').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        // Preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const wrapper = document.getElementById('avatarWrapper');
            const existing = wrapper.querySelector('.avatar-image') || wrapper.querySelector('.avatar-placeholder');
            if (existing.tagName === 'IMG') {
                existing.src = e.target.result;
            } else {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Avatar';
                img.className = 'avatar-image';
                existing.replaceWith(img);
            }
        };
        reader.readAsDataURL(this.files[0]);
        
        // Submit form
        document.getElementById('avatarForm').submit();
    }
});

// Toggle password visibility
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Validate password form
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    const newPass = document.getElementById('new_password').value;
    const confirmPass = document.getElementById('confirm_password').value;
    if (newPass !== confirmPass) {
        e.preventDefault();
        alert('Mật khẩu xác nhận không khớp!');
    }
});
</script>

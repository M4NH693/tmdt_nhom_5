<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <h1><i class="fas fa-lock-open"></i> Đặt lại mật khẩu</h1>
            <p class="subtitle">Tạo mật khẩu mới cho tài khoản <strong><?= htmlspecialchars($_SESSION['otp_email'] ?? '') ?></strong></p>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $err): ?>
                        <div><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/reset-password" novalidate>

                <div class="form-group">
                    <label for="new_password">Mật khẩu mới</label>
                    <input type="password" id="new_password" name="new_password" class="form-control"
                           placeholder="Ít nhất 6 ký tự" required autofocus>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                           placeholder="Nhập lại mật khẩu mới" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Đặt lại mật khẩu
                </button>
            </form>

            <div class="auth-footer">
                <a href="<?= BASE_URL ?>/login"><i class="fas fa-arrow-left"></i> Quay lại đăng nhập</a>
            </div>
        </div>
    </div>
</div>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <h1><i class="fas fa-key"></i> Quên mật khẩu</h1>
            <p class="subtitle">Xác minh danh tính để đặt lại mật khẩu</p>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $err): ?>
                        <div><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/forgot-password" novalidate>
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email đã đăng ký</label>
                    <input type="email" id="email" name="email" class="form-control"
                           placeholder="example@email.com"
                           value="<?= htmlspecialchars($email ?? '') ?>" required autofocus>
                </div>

                <div style="background: rgba(45,106,79,0.06); padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85em; color: var(--text-secondary);">
                    <i class="fas fa-info-circle" style="color: var(--primary);"></i>
                    Hệ thống sẽ gửi một mã xác thực (OTP) gồm 6 chữ số tới hòm thư của bạn nếu email này tồn tại trong hệ thống.
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Gửi khôi phục
                </button>
            </form>

            <div class="auth-footer">
                <a href="<?= BASE_URL ?>/login"><i class="fas fa-arrow-left"></i> Quay lại đăng nhập</a>
            </div>
        </div>
    </div>
</div>

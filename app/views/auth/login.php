<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <h1>Đăng nhập</h1>
            <p class="subtitle">Chào mừng bạn trở lại BookStore</p>

            <form id="loginForm" method="POST" action="<?= BASE_URL ?>/login" novalidate>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control"
                           placeholder="example@email.com"
                           value="<?= htmlspecialchars($email ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="Nhập mật khẩu" required>
                </div>

                <div class="form-group" style="margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="agree_terms" name="agree_terms" required style="width: auto; margin: 0;">
                    <label for="agree_terms" style="margin: 0; font-weight: normal; font-size: 0.9em;">
                        Tôi đồng ý với <a href="<?= BASE_URL ?>/terms" target="_blank" style="color: var(--primary-color);">Điều khoản sử dụng</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                </button>
            </form>

            <div class="auth-footer">
                Chưa có tài khoản? <a href="<?= BASE_URL ?>/register">Đăng ký ngay</a>
            </div>
        </div>
    </div>
</div>

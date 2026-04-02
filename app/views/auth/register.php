<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <h1>Đăng ký tài khoản</h1>
            <p class="subtitle">Tạo tài khoản để mua sắm tại BookStore</p>

            <form id="registerForm" method="POST" action="<?= BASE_URL ?>/register" novalidate>
                <div class="form-group">
                    <label for="full_name">Họ và tên</label>
                    <input type="text" id="full_name" name="full_name" class="form-control"
                           placeholder="Nguyễn Văn A"
                           value="<?= htmlspecialchars($full_name ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control"
                           placeholder="example@email.com"
                           value="<?= htmlspecialchars($email ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="Tối thiểu 6 ký tự" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                           placeholder="Nhập lại mật khẩu" required>
                </div>

                <div class="form-group" style="margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="agree_terms" name="agree_terms" required style="width: auto; margin: 0;">
                    <label for="agree_terms" style="margin: 0; font-weight: normal; font-size: 0.9em;">
                        Tôi đồng ý với <a href="<?= BASE_URL ?>/terms" target="_blank" style="color: var(--primary-color);">Điều khoản sử dụng</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Đăng ký
                </button>
            </form>

            <div class="auth-footer">
                Đã có tài khoản? <a href="<?= BASE_URL ?>/login">Đăng nhập</a>
            </div>
        </div>
    </div>
</div>

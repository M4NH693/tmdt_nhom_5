<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <h1><i class="fas fa-shield-alt"></i> Nhập mã xác thực</h1>
            <p class="subtitle">Mã OTP đã được gửi về email của bạn</p>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $err): ?>
                        <div><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/verify-otp" novalidate onsubmit="return validateOtp()">
                <div class="form-group text-center">
                    <label for="otp">Mã xác nhận (6 chữ số)</label>
                    <input type="text" id="otp" name="otp" class="form-control" style="font-size: 24px; letter-spacing: 12px; text-align: center; font-weight: bold; padding: 15px;" 
                           placeholder="------" maxlength="6" pattern="\d{6}" autocomplete="off" required autofocus>
                </div>

                <div style="background: rgba(45,106,79,0.06); padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85em; color: var(--text-secondary);">
                    <i class="fas fa-info-circle" style="color: var(--primary);"></i>
                    Mã xác nhận gồm 6 chữ số và có thời hạn sử dụng trong 15 phút. Vui lòng check cả thư mục rác (spam).
                </div>

                <button type="submit" class="btn btn-primary" id="btn-submit">
                    <i class="fas fa-check-circle"></i> Xác minh
                </button>
            </form>

            <div class="auth-footer" style="display: flex; justify-content: space-between; margin-top: 15px;">
                <a href="<?= BASE_URL ?>/login"><i class="fas fa-arrow-left"></i> Về đăng nhập</a>
                <a href="<?= BASE_URL ?>/forgot-password" style="color: var(--primary);"><i class="fas fa-redo"></i> Gửi lại mã</a>
            </div>
        </div>
    </div>
</div>

<script>
    const otpInput = document.getElementById('otp');
    
    // Chỉ cho phép nhập số
    otpInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    function validateOtp() {
        if(otpInput.value.length !== 6) {
            alert('Vui lòng nhập đủ 6 chữ số OTP!');
            return false;
        }
        return true;
    }
</script>

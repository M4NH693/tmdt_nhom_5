import { showToast } from './alerts.js';

export function initAuth() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    // Function to handle showing validation messages like in the user's prompt
    const showMessage = (msg, isError = false) => {
        showToast(msg, isError ? 'error' : 'success');
        return false;
    };

    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            if (email === "") {
                return showMessage("Vui lòng nhập Email!", true);
            }
            if (password === "") {
                return showMessage("Vui lòng nhập mật khẩu!", true);
            }

            const agreeTerms = document.getElementById('agree_terms');
            if (agreeTerms && !agreeTerms.checked) {
                return showMessage("Bạn cần đồng ý với Điều khoản sử dụng để tiếp tục!", true);
            }

            try {
                const submitBtn = loginForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang đăng nhập...';
                submitBtn.disabled = true;

                const formData = new FormData(loginForm);
                const response = await fetch(loginForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.status === 'success') {
                    showMessage(data.message, false);
                    setTimeout(() => {
                        window.location.href = data.redirect || '/';
                    }, 1000);
                } else {
                    showMessage(data.message, true);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
                showMessage('Đã xảy ra lỗi, vui lòng thử lại sau!', true);
                const submitBtn = loginForm.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Đăng nhập';
                submitBtn.disabled = false;
            }
        });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const fullname = document.getElementById('full_name').value.trim();
            const email = document.getElementById('email').value.trim();
            const pass = document.getElementById('password').value;
            const repass = document.getElementById('confirm_password').value;

            // Validation rules as requested by user
            // 1. Kiểm tra Họ tên
            if (fullname === "") {
                return showMessage("Vui lòng nhập họ và tên!", true);
            }

            // 3. Kiểm tra Email
            if (email === "") {
                return showMessage("Vui lòng nhập Email!", true);
            }

            // 4. Kiểm tra Mật khẩu
            if (pass === "") {
                return showMessage("Vui lòng nhập mật khẩu!", true);
            }
            if (pass.length < 6) {
                return showMessage("Mật khẩu phải từ 6 ký tự trở lên!", true);
            }

            // 5. Kiểm tra Nhập lại mật khẩu
            if (repass === "") {
                return showMessage("Vui lòng nhập lại mật khẩu!", true);
            }
            if (pass !== repass) {
                return showMessage("Mật khẩu nhập lại không khớp!", true);
            }

            const agreeTerms = document.getElementById('agree_terms');
            if (agreeTerms && !agreeTerms.checked) {
                return showMessage("Bạn cần đồng ý với Điều khoản sử dụng để tiếp tục!", true);
            }

            try {
                const submitBtn = registerForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang đăng ký...';
                submitBtn.disabled = true;

                const formData = new FormData(registerForm);
                const response = await fetch(registerForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.status === 'success') {
                    showMessage(data.message, false);
                    setTimeout(() => {
                        window.location.href = data.redirect || '/';
                    }, 1500);
                } else {
                    showMessage(data.message, true);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }

            } catch (error) {
                console.error('Error:', error);
                showMessage('Đã xảy ra lỗi, vui lòng thử lại sau!', true);
                const submitBtn = registerForm.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-user-plus"></i> Đăng ký';
                submitBtn.disabled = false;
            }
        });
    }
}

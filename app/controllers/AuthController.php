<?php
class AuthController extends Controller
{

    public function login()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $agreeTerms = isset($_POST['agree_terms']);

            if (!$agreeTerms) {
                if ($isAjax) {
                    echo json_encode(['status' => 'error', 'message' => 'Bạn cần đồng ý với Điều khoản sử dụng để tiếp tục!']);
                    return;
                }
                $data = [
                    'pageTitle' => 'Đăng nhập - BookStore',
                    'error' => 'Bạn cần đồng ý với Điều khoản sử dụng để tiếp tục!',
                    'email' => $email,
                ];
                $this->view('auth/login', $data);
                return;
            }

            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);

            if ($user && $userModel->verifyPassword($password, $user->password_hash)) {
                $_SESSION['user_id'] = $user->user_id;
                $_SESSION['user_name'] = $user->full_name;
                $_SESSION['user_role'] = $user->role;
                $_SESSION['user_avatar'] = $user->avatar_url;
                if ($isAjax) {
                    echo json_encode(['status' => 'success', 'message' => 'Đăng nhập thành công!', 'redirect' => BASE_URL . '/']);
                    return;
                }
                $this->setFlash('success', 'Đăng nhập thành công!');
                $this->redirect('');
                return;
            } else {
                if ($isAjax) {
                    echo json_encode(['status' => 'error', 'message' => 'Email hoặc mật khẩu không đúng.']);
                    return;
                }
                $data = [
                    'pageTitle' => 'Đăng nhập - BookStore',
                    'error' => 'Email hoặc mật khẩu không đúng.',
                    'email' => $email,
                ];
                $this->view('auth/login', $data);
                return;
            }
        }

        $this->view('auth/login', ['pageTitle' => 'Đăng nhập - BookStore']);
    }

    public function register()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            $fullName = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $agreeTerms = isset($_POST['agree_terms']);
            $errors = [];

            if (!$agreeTerms)
                $errors[] = 'Bạn cần đồng ý với Điều khoản sử dụng để tiếp tục!';

            if (empty($fullName))
                $errors[] = 'Vui lòng nhập họ tên.';
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
                $errors[] = 'Email không hợp lệ.';
            if (empty($phone) || !preg_match('/^[0-9]{10,11}$/', $phone))
                $errors[] = 'Số điện thoại không hợp lệ (cần 10-11 số).';
            if (strlen($password) < 6)
                $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự.';
            if ($password !== $confirmPassword)
                $errors[] = 'Mật khẩu xác nhận không khớp.';

            $userModel = $this->model('User');
            if ($userModel->findByEmail($email)) {
                $errors[] = 'Email này đã được sử dụng.';
            }

            if (empty($errors)) {
                $userId = $userModel->register($fullName, $email, $password, $phone);
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $fullName;
                $_SESSION['user_role'] = 'customer';
                if ($isAjax) {
                    echo json_encode(['status' => 'success', 'message' => 'Đăng ký thành công! Chào mừng bạn đến với BookStore.', 'redirect' => BASE_URL . '/']);
                    return;
                }
                $this->setFlash('success', 'Đăng ký thành công! Chào mừng bạn đến với BookStore.');
                $this->redirect('');
                return;
            }

            if ($isAjax) {
                echo json_encode(['status' => 'error', 'message' => implode('<br>', $errors)]);
                return;
            }

            $data = [
                'pageTitle' => 'Đăng ký - BookStore',
                'errors' => $errors,
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone,
            ];
            $this->view('auth/register', $data);
            return;
        }

        $this->view('auth/register', ['pageTitle' => 'Đăng ký - BookStore']);
    }

    public function forgotPassword()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('');
            return;
        }

        $data = ['pageTitle' => 'Quên mật khẩu - BookStore'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $data['email'] = $email;

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['errors'] = ['Vui lòng nhập email hợp lệ.'];
                $this->view('auth/forgot_password', $data);
                return;
            }

            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);

            if ($user) {
                // Tạo OTP ngẫu nhiên 6 chữ số
                $otp = sprintf("%06d", mt_rand(0, 999999));
                $expiresAt = date('Y-m-d H:i:s', time() + 900); // 15 phút

                $userModel->saveResetToken($email, $otp, $expiresAt);
                $_SESSION['otp_email'] = $email;

                // Chuẩn bị nội dung email
                $subject = "Mã xác thực khôi phục mật khẩu - Book4U";
                $body = "
                <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;'>
                    <div style='background-color: #2D6A4F; color: #fff; padding: 20px; text-align: center;'>
                        <h2 style='margin: 0;'>Book4U</h2>
                    </div>
                    <div style='padding: 20px;'>
                        <p>Xin chào <strong>{$user->full_name}</strong>,</p>
                        <p>Chúng tôi nhận được yêu cầu khôi phục mật khẩu cho tài khoản liên kết với địa chỉ email này.</p>
                        <p>Mã xác thực (OTP) của bạn là:</p>
                        <div style='text-align: center; margin: 30px 0;'>
                            <span style='background-color: #f1f5f9; color: #2563eb; padding: 15px 30px; border-radius: 8px; font-size: 28px; font-weight: bold; letter-spacing: 5px; border: 2px dashed #cbd5e1; display: inline-block;'>{$otp}</span>
                        </div>
                        <p>Vui lòng nhập mã này trên trang web để đặt lại mật khẩu. Mã này có hiệu lực trong vòng <strong>15 phút</strong>.</p>
                        <p>Nếu bạn không thực hiện yêu cầu này, vui lòng báo cáo hoặc bỏ qua email và không chia sẻ mã này cho bất kỳ ai.</p>
                    </div>
                    <div style='background-color: #f8fafc; color: #64748b; padding: 15px; text-align: center; font-size: 0.8em;'>
                        &copy; " . date('Y') . " BookStore. All rights reserved.
                    </div>
                </div>";

                require_once __DIR__ . '/../core/Mailer.php';
                $mailer = new Mailer();
                if ($mailer->sendMail($email, $subject, $body)) {
                    $this->setFlash('success', 'Mã xác thực OTP đã được gửi tới hộp thư của bạn. Vui lòng kiểm tra email.');
                } else {
                    $this->setFlash('error', 'Không thể gửi email lúc này do lỗi hệ thống. Vui lòng thử lại sau.');
                    $this->redirect('forgot-password');
                    return;
                }
            } else {
                // Bảo mật: Không thông báo email tồn tại hay không, trả về chung log success để tránh dò rỉ tài khoản
                $_SESSION['otp_email'] = $email;
                $this->setFlash('success', 'Nếu email hợp lệ, mã xác thực OTP đã được gửi tới hộp thư của bạn. Vui lòng kiểm tra email.');
            }

            $this->redirect('verify-otp');
            return;
        }

        $this->view('auth/forgot_password', $data);
    }

    public function verifyOtp()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('');
            return;
        }

        if (empty($_SESSION['otp_email'])) {
            $this->redirect('forgot-password');
            return;
        }

        $data = ['pageTitle' => 'Nhập Mã Xác Thực - BookStore'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $otpInput = trim($_POST['otp'] ?? '');

            if (empty($otpInput) || strlen($otpInput) !== 6) {
                $data['errors'] = ['Vui lòng nhập đầy đủ 6 chữ số mã xác thực.'];
                $this->view('auth/verify_otp', $data);
                return;
            }

            $userModel = $this->model('User');
            // Truy vấn lấy user theo email trong session
            $user = $userModel->findByEmail($_SESSION['otp_email']);

            if (!$user || $user->reset_token !== $otpInput || strtotime($user->reset_expires_at) < time()) {
                $data['errors'] = ['Mã xác thực không hợp lệ hoặc đã hết hạn. Vui lòng kiểm tra lại.'];
                $this->view('auth/verify_otp', $data);
                return;
            }

            // OTP đúng
            $_SESSION['otp_verified'] = true;
            $this->setFlash('success', 'Xác minh thành công! Vui lòng nhập mật khẩu mới.');
            $this->redirect('reset-password');
            return;
        }

        $this->view('auth/verify_otp', $data);
    }

    public function resetPassword()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('');
            return;
        }

        // Bắt buộc phải qua bước verify OTP
        if (empty($_SESSION['otp_verified']) || empty($_SESSION['otp_email'])) {
            $this->setFlash('error', 'Vui lòng xác minh mã OTP trước.');
            $this->redirect('forgot-password');
            return;
        }

        $data = ['pageTitle' => 'Đặt lại mật khẩu - BookStore'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $errors = [];

            if (strlen($newPassword) < 6) {
                $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
            }
            if ($newPassword !== $confirmPassword) {
                $errors[] = 'Mật khẩu xác nhận không khớp.';
            }

            if (empty($errors)) {
                $userModel = $this->model('User');
                $user = $userModel->findByEmail($_SESSION['otp_email']);

                if ($user) {
                    $userModel->updatePassword($user->user_id, $newPassword);
                    $userModel->clearResetToken($user->user_id);
                }

                // Dọn dẹp session
                unset($_SESSION['otp_email']);
                unset($_SESSION['otp_verified']);

                $this->setFlash('success', 'Đặt lại mật khẩu thành công! Hãy đăng nhập với mật khẩu mới.');
                $this->redirect('login');
                return;
            }

            $data['errors'] = $errors;
        }

        $this->view('auth/reset_password', $data);
    }

    public function logout()
    {
        session_destroy();
        header("Location: " . BASE_URL . "/login");
        exit;
    }
}

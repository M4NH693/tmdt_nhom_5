<?php
class AuthController extends Controller {

    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirect('');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $agreeTerms = isset($_POST['agree_terms']);

            if (!$agreeTerms) {
                if ($isAjax) {
                    echo json_encode(['status' => 'error', 'message' => 'Bạn cần đồng ý với Điều khoản sử dụng để tiếp tục!']);
                    return;
                }
                $data = [
                    'pageTitle' => 'Đăng nhập - BookStore',
                    'error'     => 'Bạn cần đồng ý với Điều khoản sử dụng để tiếp tục!',
                    'email'     => $email,
                ];
                $this->view('auth/login', $data);
                return;
            }

            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);

            if ($user && $userModel->verifyPassword($password, $user->password_hash)) {
                $_SESSION['user_id']   = $user->user_id;
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
                    'error'     => 'Email hoặc mật khẩu không đúng.',
                    'email'     => $email,
                ];
                $this->view('auth/login', $data);
                return;
            }
        }

        $this->view('auth/login', ['pageTitle' => 'Đăng nhập - BookStore']);
    }

    public function register() {
        if ($this->isLoggedIn()) {
            $this->redirect('');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            $fullName        = trim($_POST['full_name'] ?? '');
            $email           = trim($_POST['email'] ?? '');
            $password        = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $agreeTerms = isset($_POST['agree_terms']);
            $errors = [];

            if (!$agreeTerms) $errors[] = 'Bạn cần đồng ý với Điều khoản sử dụng để tiếp tục!';

            if (empty($fullName)) $errors[] = 'Vui lòng nhập họ tên.';
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
                $errors[] = 'Email không hợp lệ.';
            if (strlen($password) < 6)
                $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự.';
            if ($password !== $confirmPassword)
                $errors[] = 'Mật khẩu xác nhận không khớp.';

            $userModel = $this->model('User');
            if ($userModel->findByEmail($email)) {
                $errors[] = 'Email này đã được sử dụng.';
            }

            if (empty($errors)) {
                $userId = $userModel->register($fullName, $email, $password);
                $_SESSION['user_id']   = $userId;
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
                'errors'    => $errors,
                'full_name' => $fullName,
                'email'     => $email,
            ];
            $this->view('auth/register', $data);
            return;
        }

        $this->view('auth/register', ['pageTitle' => 'Đăng ký - BookStore']);
    }

    public function logout() {
        session_destroy();
        header("Location: " . BASE_URL . "/login");
        exit;
    }
}

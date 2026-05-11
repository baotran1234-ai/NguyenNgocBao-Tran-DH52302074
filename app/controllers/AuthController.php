<?php
// ================================================================
// app/controllers/AuthController.php
// ================================================================
require_once APP_PATH . '/models/UserModel.php';

class AuthController {
    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // ---- Đăng nhập ----
    public function login(): void {
        if (isLoggedIn()) {
            redirect(url(''));
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $error = 'Yêu cầu không hợp lệ. Vui lòng thử lại.';
            } else {
                $email    = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                $user     = $this->userModel->findByEmail($email);

                if ($user && $user['is_active'] && $this->userModel->verifyPassword($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user']    = [
                        'id'     => $user['id'],
                        'name'   => $user['name'],
                        'email'  => $user['email'],
                        'avatar' => $user['avatar'] ?? null,
                    ];
                    $this->userModel->updateLastLogin($user['id']);
                    $this->mergeCart($user['id']);
                    regenerateSession();
                    setFlash('success', 'Chào mừng trở lại, ' . $user['name'] . '! 💄');
                    redirect(url(''));
                } else {
                    $error = 'Email hoặc mật khẩu không đúng, hoặc tài khoản bị khóa.';
                }
            }
        }

        $pageTitle = 'Đăng Nhập - LUXE Beauty';
        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/auth/login.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }

    // ---- Đăng ký ----
    public function register(): void {
        if (isLoggedIn()) {
            redirect(url(''));
        }

        $errors = [];
        $old    = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Yêu cầu không hợp lệ. Vui lòng thử lại.';
            } else {
                $name     = trim($_POST['name'] ?? '');
                $email    = trim($_POST['email'] ?? '');
                $phone    = trim($_POST['phone'] ?? '');
                $password = $_POST['password'] ?? '';
                $confirm  = $_POST['confirm_password'] ?? '';
                $old      = compact('name', 'email', 'phone');

                if (empty($name))                                    $errors[] = 'Vui lòng nhập họ và tên.';
                if (!filter_var($email, FILTER_VALIDATE_EMAIL))      $errors[] = 'Email không hợp lệ.';
                if (strlen($password) < 6)                           $errors[] = 'Mật khẩu tối thiểu 6 ký tự.';
                if ($password !== $confirm)                          $errors[] = 'Xác nhận mật khẩu không khớp.';
                if ($this->userModel->findByEmail($email))           $errors[] = 'Email này đã được sử dụng.';

                if (empty($errors)) {
                    $this->userModel->create([
                        'name'     => $name,
                        'email'    => $email,
                        'password' => $password,
                        'phone'    => $phone,
                    ]);
                    setFlash('success', 'Đăng ký thành công! Vui lòng đăng nhập để bắt đầu mua sắm. 🎉');
                    redirect(url('auth/login'));
                }
            }
        }

        $pageTitle = 'Đăng Ký - LUXE Beauty';
        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/auth/register.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }

    // ---- Đăng xuất ----
    public function logout(): void {
        $name = $_SESSION['user']['name'] ?? '';
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
        session_start();
        setFlash('success', 'Đăng xuất thành công' . ($name ? ', ' . $name : '') . '!');
        redirect(url('auth/login'));
    }

    // ---- Quên mật khẩu ----
    public function forgot(): void {
        $message = null;
        $error   = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $error = 'Yêu cầu không hợp lệ.';
            } else {
                $email = trim($_POST['email'] ?? '');
                $user  = $this->userModel->findByEmail($email);

                if ($user) {
                    $token = bin2hex(random_bytes(32));
                    $this->userModel->setResetToken($email, $token);
                    $resetLink = url('auth/reset/' . $token);
                    // TODO: Gửi email thật (cần SMTP)
                    $message = 'Link đặt lại mật khẩu: <a href="' . $resetLink . '">' . $resetLink . '</a>';
                } else {
                    $message = 'Nếu email tồn tại, chúng tôi đã gửi link đặt lại mật khẩu.';
                }
            }
        }

        $pageTitle = 'Quên Mật Khẩu - LUXE Beauty';
        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/auth/forgot.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }

    // ---- Đặt lại mật khẩu ----
    public function reset(?string $token): void {
        if (!$token) redirect(url('auth/forgot'));

        $user  = $this->userModel->findByResetToken($token);
        $error = null;

        if (!$user) {
            setFlash('error', 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.');
            redirect(url('auth/forgot'));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                $error = 'Yêu cầu không hợp lệ.';
            } else {
                $password = $_POST['password'] ?? '';
                $confirm  = $_POST['confirm_password'] ?? '';

                if (strlen($password) < 6)       $error = 'Mật khẩu tối thiểu 6 ký tự.';
                elseif ($password !== $confirm)   $error = 'Mật khẩu không khớp.';
                else {
                    $this->userModel->updatePassword($user['id'], $password);
                    $this->userModel->clearResetToken($user['id']);
                    setFlash('success', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập.');
                    redirect(url('auth/login'));
                }
            }
        }

        $pageTitle = 'Đặt Lại Mật Khẩu - LUXE Beauty';
        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/auth/reset.php';
        require_once APP_PATH . '/views/layouts/footer.php';
    }

    // ---- Merge cart session vào DB ----
    private function mergeCart(int $userId): void {
        $sessionCart = $_SESSION['cart'] ?? [];
        if (empty($sessionCart)) return;
        // TODO: Merge vào bảng carts nếu cần
    }
}

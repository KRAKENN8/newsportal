<?php
class modelAdmin {
    // АВТОРИЗАЦИЯ АДМИНА
    public static function userAuthentication() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['sessionId']) && isset($_SESSION['status']) && $_SESSION['status'] === 'admin') {
            return true;
        }

        $logIn = false;
        if (isset($_POST['btnLogin'])) {
            if (class_exists('Security')) {
                $throttle = Security::checkRateLimit('admin_login', 5, 300);
                if ($throttle['isBlocked']) {
                    $_SESSION['errorString'] = 'Too many failed login attempts. Please wait ' . $throttle['remainingSeconds'] . ' seconds.';
                    return false;
                }
            }

            if (!empty($_POST['email']) && !empty($_POST['password'])) {
                $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
                $password = $_POST['password'];

                if ($email) {
                    $db = new Database();
                    $item = $db->getOne("SELECT * FROM users WHERE email = :email LIMIT 1", [':email' => strtolower($email)]);
                    if ($item && isset($item['status']) && $item['status'] === 'admin') {
                        if (password_verify($password, $item['password'])) {
                            if (!headers_sent()) {
                                @session_regenerate_id(true);
                            }
                            if (class_exists('Security')) {
                                Security::clearRateLimit('admin_login');
                            }
                            $_SESSION['sessionId'] = session_id();
                            $_SESSION['userId'] = $item['id'];
                            $_SESSION['name'] = $item['username'];
                            $_SESSION['status'] = $item['status'];
                            $logIn = true;
                        } else {
                            if (class_exists('Security')) {
                                Security::recordFailedAttempt('admin_login');
                            }
                        }
                    } else {
                        if (class_exists('Security')) {
                            Security::recordFailedAttempt('admin_login');
                        }
                    }
                }
            }
        }
        return $logIn;
    }

    // Выход ИЗ АДМИНКИ
    public static function userLogout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['sessionId']);
        unset($_SESSION['userId']);
        unset($_SESSION['name']);
        unset($_SESSION['status']);
        unset($_SESSION['errorString']);
        return;
    }
}
?>
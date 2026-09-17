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
            if (!empty($_POST['email']) && !empty($_POST['password'])) {
                $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
                $password = $_POST['password'];

                if ($email) {
                    $db = new Database();
                    $item = $db->getOne("SELECT * FROM users WHERE email = :email LIMIT 1", [':email' => strtolower($email)]);
                    if ($item && isset($item['status']) && $item['status'] === 'admin') {
                        if (password_verify($password, $item['password'])) {
                            $_SESSION['sessionId'] = session_id();
                            $_SESSION['userId'] = $item['id'];
                            $_SESSION['name'] = $item['username'];
                            $_SESSION['status'] = $item['status'];
                            $logIn = true;
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
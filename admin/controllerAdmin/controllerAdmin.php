<?php
class controllerAdmin {
    // Форма авторизации админа
    public static function formLoginSite() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['sessionId']) && isset($_SESSION['status']) && $_SESSION['status'] === 'admin') {
            include_once('viewAdmin/startAdmin.php');
        } else {
            include_once('viewAdmin/formLogin.php');
        }
    }

    public static function loginAction() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $logIn = modelAdmin::userAuthentication();
        if ($logIn === true && isset($_SESSION['status']) && $_SESSION['status'] === 'admin') {
            header('Location: ./');
            exit;
        } else {
            $_SESSION['errorString'] = 'Invalid administrator credentials or insufficient privileges.';
            include_once('viewAdmin/formLogin.php');
        }
    }

    // Выход из админ панели
    public static function logoutAction() {
        modelAdmin::userLogout();
        include_once('viewAdmin/formLogin.php');
    }

    // Страница Error
    public static function error404() {
        include_once('viewAdmin/error404.php');
    }
}
?>
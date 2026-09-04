<?php
class controllerAdmin {
    // Форма авторизации админа
    public static function formLoginSite() {
        if (isset($_SESSION['sessionId']) && isset($_SESSION['status']) && $_SESSION['status'] === 'admin') {
            include_once('viewAdmin/startAdmin.php');
        } else {
            include_once('viewAdmin/formLogin.php');
        }
    }

    public static function loginAction() {
        $logIn = modelAdmin::userAuthentication();
        if (isset($logIn) && $logIn == true) {
            include_once('viewAdmin/startAdmin.php');
        } else {
            $_SESSION['errorString'] = 'Invalid email address or password.';
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
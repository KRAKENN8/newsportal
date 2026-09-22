<?php
class controllerAdminNews {
    /**
     * Enforce strict admin authorization on all actions
     */
    public static function checkAdminAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['sessionId']) || !isset($_SESSION['userId']) || !isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
            $_SESSION['errorString'] = 'Access restricted: Administrator privileges required.';
            header('Location: ./');
            exit;
        }
    }

    // list News
    public static function NewsList() {
        self::checkAdminAuth();
        $arr = modelAdminNews::getNewsList();
        include_once 'viewAdmin/newsList.php';
    }

    //------------------ add
    public static function newsAddForm() {
        self::checkAdminAuth();
        $arr = modelAdminCategory::getCategoryList();
        include_once('viewAdmin/newsAddForm.php');
    }

    public static function newsAddResult() {
        self::checkAdminAuth();
        $test = modelAdminNews::getNewsAdd();
        include_once('viewAdmin/newsAddForm.php');
    }

    //------------------ edit
    public static function newsEditForm($id) {
        self::checkAdminAuth();
        $arr = modelAdminCategory::getCategoryList();
        $detail = modelAdminNews::getNewsDetail($id);
        include_once('viewAdmin/newsEditForm.php');
    }

    public static function newsEditResult($id) {
        self::checkAdminAuth();
        $test = modelAdminNews::getNewsEdit($id);
        include_once('viewAdmin/newsEditForm.php');
    }

    //------------------ delete
    public static function newsDeleteForm($id) {
        self::checkAdminAuth();
        $arr = modelAdminCategory::getCategoryList();
        $detail = modelAdminNews::getNewsDetail($id);
        include_once('viewAdmin/newsDeleteForm.php');
    }

    public static function newsDeleteResult($id) {
        self::checkAdminAuth();
        $test = modelAdminNews::getNewsDelete($id);
        if ($test && isset($_GET['from']) && $_GET['from'] === 'list') {
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            $_SESSION['adminFlash'] = 'Publication #' . (int)$id . ' was successfully deleted.';
            header('Location: newsAdmin');
            exit;
        }
        include_once('viewAdmin/newsDeleteForm.php');
    }
}
?>
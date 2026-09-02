<?php
class controllerAdminNews {
    public static function NewsList() {
        $arr = modelAdminNews::getNewsList();
        include_once 'viewAdmin/newsList.php';
    }

    public static function newsAddForm() {
        $arr = modelAdminCategory::getCategoryList();
        include_once('viewAdmin/newsAddForm.php');
    }

    public static function newsAddResult() {
        $test = modelAdminNews::getNewsAdd();
        include_once('viewAdmin/newsAddForm.php');
    }
}
?>
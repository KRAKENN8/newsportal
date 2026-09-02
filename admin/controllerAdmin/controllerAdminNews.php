<?php
class controllerAdminNews {
    // Список новостей
    public static function NewsList() {
        $arr = modelAdminNews::getNewsList();
        include_once 'viewAdmin/newsList.php';
    }
}
?>
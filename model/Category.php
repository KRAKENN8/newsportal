<?php
class Category {
    public static function getAllCategory() {
        $query = "SELECT * FROM category ORDER BY id ASC";
        $db = new Database();
        $arr = $db->getAll($query);
        return $arr;
    }

    public static function getCategoryByID($id) {
        $safeId = (int)$id;
        $query = "SELECT * FROM category WHERE id = $safeId";
        $db = new Database();
        return $db->getOne($query);
    }
}
?>
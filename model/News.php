<?php
class News {
    public static function getLast10News() {
        $query = "SELECT items.*, category.name AS category_name FROM items LEFT JOIN category ON items.category_id = category.id ORDER BY items.id DESC LIMIT 6";
        $db = new Database();
        $arr = $db->getAll($query);
        return $arr;
    }

    public static function getAllNews() {
        $query = "SELECT items.*, category.name AS category_name FROM items LEFT JOIN category ON items.category_id = category.id ORDER BY items.id DESC";
        $db = new Database();
        $arr = $db->getAll($query);
        return $arr;
    }

    public static function getNewsByCategoryID($id) {
        $safeId = (int)$id;
        $query = "SELECT items.*, category.name AS category_name FROM items LEFT JOIN category ON items.category_id = category.id WHERE items.category_id=$safeId ORDER BY items.id DESC";
        $db = new Database();
        $arr = $db->getAll($query);
        return $arr;
    }

    public static function getNewsByID($id) {
        $safeId = (int)$id;
        $query = "SELECT items.*, category.name AS category_name, users.username AS author_name FROM items LEFT JOIN category ON items.category_id = category.id LEFT JOIN users ON items.user_id = users.id WHERE items.id=$safeId";
        $db = new Database();
        $n = $db->getOne($query);
        return $n;
    }

    public static function searchNews($keyword) {
        $db = new Database();
        $conn = $db->connect();
        $searchTerm = '%' . trim($keyword) . '%';
        $stmt = $conn->prepare("SELECT items.*, category.name AS category_name FROM items LEFT JOIN category ON items.category_id = category.id WHERE items.title LIKE :q1 OR items.text LIKE :q2 ORDER BY items.id DESC");
        $stmt->execute([':q1' => $searchTerm, ':q2' => $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

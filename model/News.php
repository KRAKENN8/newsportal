<?php
class News {
    public static function getLast10News() {
        $query = "SELECT news.*, category.name AS category_name FROM news LEFT JOIN category ON news.category_id = category.id ORDER BY news.id DESC LIMIT 6";
        $db = new Database();
        $arr = $db->getAll($query);
        return $arr;
    }

    public static function getAllNews() {
        $query = "SELECT news.*, category.name AS category_name FROM news LEFT JOIN category ON news.category_id = category.id ORDER BY news.id DESC";
        $db = new Database();
        $arr = $db->getAll($query);
        return $arr;
    }

    public static function getNewsByCategoryID($id) {
        $safeId = (int)$id;
        $query = "SELECT news.*, category.name AS category_name FROM news LEFT JOIN category ON news.category_id = category.id WHERE news.category_id=$safeId ORDER BY news.id DESC";
        $db = new Database();
        $arr = $db->getAll($query);
        return $arr;
    }

    public static function getNewsByID($id) {
        $safeId = (int)$id;
        $query = "SELECT news.*, category.name AS category_name, users.username AS author_name FROM news LEFT JOIN category ON news.category_id = category.id LEFT JOIN users ON news.user_id = users.id WHERE news.id=$safeId";
        $db = new Database();
        $n = $db->getOne($query);
        return $n;
    }

    public static function searchNews($keyword) {
        $db = new Database();
        $conn = $db->connect();
        $searchTerm = '%' . trim($keyword) . '%';
        $stmt = $conn->prepare("SELECT news.*, category.name AS category_name FROM news LEFT JOIN category ON news.category_id = category.id WHERE news.title LIKE :q1 OR news.text LIKE :q2 ORDER BY news.id DESC");
        $stmt->execute([':q1' => $searchTerm, ':q2' => $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
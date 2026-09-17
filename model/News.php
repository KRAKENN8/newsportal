<?php
class News {
    public static function getLast10News() {
        $query = "SELECT items.*, category.name AS category_name, COALESCE(c.comments_count, 0) AS comments_count 
                  FROM items 
                  LEFT JOIN category ON items.category_id = category.id 
                  LEFT JOIN (SELECT news_id, COUNT(id) AS comments_count FROM details GROUP BY news_id) AS c ON items.id = c.news_id 
                  ORDER BY items.id DESC LIMIT 6";
        $db = new Database();
        return $db->getAll($query);
    }

    public static function getAllNews() {
        $query = "SELECT items.*, category.name AS category_name, COALESCE(c.comments_count, 0) AS comments_count 
                  FROM items 
                  LEFT JOIN category ON items.category_id = category.id 
                  LEFT JOIN (SELECT news_id, COUNT(id) AS comments_count FROM details GROUP BY news_id) AS c ON items.id = c.news_id 
                  ORDER BY items.id DESC";
        $db = new Database();
        return $db->getAll($query);
    }

    public static function getNewsByCategoryID($id) {
        $query = "SELECT items.*, category.name AS category_name, COALESCE(c.comments_count, 0) AS comments_count 
                  FROM items 
                  LEFT JOIN category ON items.category_id = category.id 
                  LEFT JOIN (SELECT news_id, COUNT(id) AS comments_count FROM details GROUP BY news_id) AS c ON items.id = c.news_id 
                  WHERE items.category_id = :cat_id 
                  ORDER BY items.id DESC";
        $db = new Database();
        return $db->getAll($query, [':cat_id' => (int)$id]);
    }

    public static function getNewsByID($id) {
        $query = "SELECT items.*, category.name AS category_name, users.username AS author_name, COALESCE(c.comments_count, 0) AS comments_count 
                  FROM items 
                  LEFT JOIN category ON items.category_id = category.id 
                  LEFT JOIN users ON items.user_id = users.id 
                  LEFT JOIN (SELECT news_id, COUNT(id) AS comments_count FROM details GROUP BY news_id) AS c ON items.id = c.news_id 
                  WHERE items.id = :id";
        $db = new Database();
        return $db->getOne($query, [':id' => (int)$id]);
    }

    public static function searchNews($keyword) {
        $searchTerm = '%' . trim($keyword) . '%';
        $query = "SELECT items.*, category.name AS category_name, COALESCE(c.comments_count, 0) AS comments_count 
                  FROM items 
                  LEFT JOIN category ON items.category_id = category.id 
                  LEFT JOIN (SELECT news_id, COUNT(id) AS comments_count FROM details GROUP BY news_id) AS c ON items.id = c.news_id 
                  WHERE items.title LIKE :q1 OR items.text LIKE :q2 
                  ORDER BY items.id DESC";
        $db = new Database();
        return $db->getAll($query, [':q1' => $searchTerm, ':q2' => $searchTerm]);
    }
}
?>

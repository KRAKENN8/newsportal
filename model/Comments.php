<?php
class Comments {
    public static function insertComment($text, $newsId) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        $db = new Database();
        $conn = $db->connect();

        $query = "INSERT INTO comments (news_id, user_id, text, date) 
                  VALUES (:news_id, :user_id, :text, CURRENT_TIMESTAMP)";
        $stmt = $conn->prepare($query);
        return $stmt->execute([
            ':news_id' => $newsId,
            ':user_id' => $_SESSION['user_id'],
            ':text'    => $text
        ]);
    }

    public static function getCommentByNewsID($id) {
        $db = new Database();
        $conn = $db->connect();

        $query = "SELECT comments.*, users.username 
                  FROM comments 
                  LEFT JOIN users ON comments.user_id = users.id 
                  WHERE comments.news_id = :news_id 
                  ORDER BY comments.id DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute([':news_id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCommentsCountByNewsID($id) {
        $db = new Database();
        $conn = $db->connect();

        $query = "SELECT COUNT(id) as count FROM comments WHERE news_id = :news_id";
        $stmt = $conn->prepare($query);
        $stmt->execute([':news_id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

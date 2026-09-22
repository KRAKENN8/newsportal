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

        $query = "INSERT INTO details (news_id, user_id, text, date) 
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

        $query = "SELECT details.*, users.username 
                  FROM details 
                  LEFT JOIN users ON details.user_id = users.id 
                  WHERE details.news_id = :news_id 
                  ORDER BY details.id DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute([':news_id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCommentsCountByNewsID($id) {
        $db = new Database();
        $conn = $db->connect();

        $query = "SELECT COUNT(id) as count FROM details WHERE news_id = :news_id";
        $stmt = $conn->prepare($query);
        $stmt->execute([':news_id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getCommentById($id) {
        $db = new Database();
        return $db->getOne("SELECT * FROM details WHERE id = :id", [':id' => (int)$id]);
    }

    public static function deleteComment($commentId, $userId, $isAdmin = false) {
        $db = new Database();
        if ($isAdmin) {
            return $db->executeRun("DELETE FROM details WHERE id = :id", [':id' => (int)$commentId]);
        } else {
            return $db->executeRun("DELETE FROM details WHERE id = :id AND user_id = :user_id", [
                ':id'      => (int)$commentId,
                ':user_id' => (int)$userId
            ]);
        }
    }
}
?>

<?php
class modelAdminComments {
    public static function getCommentsList() {
        $query = "SELECT details.*, users.username, items.title AS news_title 
                  FROM details 
                  LEFT JOIN users ON details.user_id = users.id 
                  LEFT JOIN items ON details.news_id = items.id 
                  ORDER BY details.id DESC";
        $db = new Database();
        return $db->getAll($query);
    }

    public static function deleteComment($id) {
        $db = new Database();
        $query = "DELETE FROM details WHERE id = :id";
        return $db->executeRun($query, [':id' => (int)$id]);
    }

    public static function getCommentsCount() {
        $db = new Database();
        $res = $db->getOne("SELECT COUNT(id) AS total FROM details");
        return isset($res['total']) ? (int)$res['total'] : 0;
    }
}
?>

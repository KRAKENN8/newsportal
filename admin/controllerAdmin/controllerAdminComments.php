<?php
class controllerAdminComments {
    private static function checkAdminAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['sessionId']) || !isset($_SESSION['userId']) || !isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
            $_SESSION['errorString'] = 'Access restricted: Administrator privileges required.';
            header('Location: ./');
            exit;
        }
    }

    public static function CommentsList() {
        self::checkAdminAuth();
        $comments = modelAdminComments::getCommentsList();
        include_once 'viewAdmin/commentsList.php';
    }

    public static function commentDeleteResult($id) {
        self::checkAdminAuth();
        $id = (int)$id;
        if ($id > 0) {
            modelAdminComments::deleteComment($id);
            $_SESSION['adminFlash'] = 'Comment #' . $id . ' was successfully removed.';
        }
        header('Location: commentsAdmin');
        exit;
    }
}
?>

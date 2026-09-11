<?php

require_once __DIR__ . '/../view/news.php';
require_once __DIR__ . '/../view/comments.php';

class Controller {
    public static function StartSite() {
        $arr = News::getLast10News();
        include_once 'view/start.php';
    }

    public static function AllCategory() {
        $arr = Category::getAllCategory();
        include_once 'view/category.php';
    }

    public static function AllNews() {
        $arr = News::getAllNews();
        include_once 'view/allnews.php';
    }

    public static function NewsByCatID($id) {
        $arr = News::getNewsByCategoryID($id);
        include_once 'view/catnews.php';
    }

    public static function NewsByID($id) {
        $n = News::getNewsByID($id);
        include_once 'view/readnews.php';
    }

    public static function error404() {
        include_once 'view/error404.php';
    }

    public static function InsertComment($c, $id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: formLogin');
            exit;
        }
        Comments::insertComment($c, $id);
        header('Location:news?id='.$id.'#ctable');
    }

    public static function Comments($newsid) {
        $arr = Comments::getCommentByNewsID($newsid);
        ViewComments::CommentsByNews($arr);
    }

    public static function CommentsCount($newsid) {
        $arr = Comments::getCommentsCountByNewsID($newsid);
        ViewComments::CommentsCount($arr);
    }

    public static function CommentsCountWithAncor($newsid) {
        $arr = Comments::getCommentsCountByNewsID($newsid);
        ViewComments::CommentsCountWithAncor($arr);
    }

    public static function SearchNews($keyword) {
        $arr = News::searchNews($keyword);
        include_once 'view/search.php';
    }

    public static function AboutSite() {
        include_once 'view/about.php';
    }

    public static function registerForm() {
        include_once('view/formRegister.php');
    }

    public static function registerUser() {
        $result = Register::registerUser();
        include_once('view/answerRegister.php');
    }

    public static function formLogin() {
        include_once('view/formLogin.php');
    }

    public static function loginUser() {
        $result = Login::loginUser();
        include_once('view/answerLogin.php');
    }

    public static function profile() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: formLogin');
            exit;
        }

        $result = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
            $result = Profile::updateUsername($_SESSION['user_id'], $_POST['username']);
            if ($result[0] === true) {
                $_SESSION['username'] = trim($_POST['username']);
            }
        }

        $user = Profile::getUserById($_SESSION['user_id']);
        include_once 'view/profile.php';
    }

    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: ./');
        exit;
    }
}
?>

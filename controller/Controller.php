<?php
class Controller {

    public static function NewsByCatID($id)
    {
        $arr = News::getNewsByCatID($id);
        include_once 'view/catnews.php';
    }

    public static function NewsByID($id)
    {
        $n = News::getNewsByID($id);
        include_once 'view/readnews.php';
    }

    public static function error404()
    {
        include_once 'view/error404.php';
    }

    public static function InsertComment($c, $id)
    {
        Comments::insertComment($c, $id);
        // self::NewsByID($id);
        header('Location: news?id=' . $id . '#ctable');
    }

    // список комментариев
    public static function Comments($newsid)
    {
        $arr = Comments::getCommentByNewsID($newsid);
        ViewComments::CommentsByNews($arr);
    }

    // количество комментариев к новости
    public static function CommentsCount($newsid)
    {
        $arr = Comments::getCommentsCountByNewsID($newsid);
        ViewComments::CommentsCount($arr);
    }

    // ссылка - переход к списку комментариев
    public static function CommentsCountWithAncor($newsid)
    {
        $arr = Comments::getCommentsCountByNewsID($newsid);
        ViewComments::CommentsCountWithAncor($arr);
    }

} //end class
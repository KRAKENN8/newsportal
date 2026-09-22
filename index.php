<?php
    require_once 'inc/Security.php';
    Security::sendSecurityHeaders();
    Security::initSession();

    include_once 'inc/Database.php';
    require 'model/Category.php';
    require 'model/News.php';
    require 'model/Comments.php';
    require 'model/Register.php';
    require 'model/Login.php';
    require 'model/Profile.php';

    include_once 'view/news.php';
    include_once 'view/comments.php';

    include_once 'controller/Controller.php';
    include_once 'route/routing.php';

    echo $response;
?>
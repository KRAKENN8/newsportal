<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "model/Comments.php"; // поправьте путь под свою структуру

if (!isset($_SESSION['user_id'])) {
    header('Location: formLogin');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'], $_POST['id'])) {
    $text = trim($_POST['comment']);
    $newsId = (int)$_POST['id'];

    if ($text !== '') {
        Comments::insertComment($text, $newsId);
    }

    header('Location: article?id=' . $newsId . '#cp-comments');
    exit;
}

header('Location: ./');
exit;
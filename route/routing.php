<?php
// Вычислить маршрут из адресной строки
$rawUri = explode('?', $_SERVER['REQUEST_URI'])[0];
$cleanUri = rtrim($rawUri, '/');
$parts = explode('/', $cleanUri);
$path = end($parts);

if ($path == '' || $path == 'newsportal' || $path == 'index' || $path == 'index.php') {
    $response = Controller::StartSite();
}
elseif ($path == 'all') {
    $response = Controller::AllNews();
}
elseif ($path == 'category' && isset($_GET['id'])) {
    $response = Controller::NewsByCatID($_GET['id']);
}
elseif ($path == 'news' && isset($_GET['id'])) {
    $response = Controller::NewsByID($_GET['id']);
}
elseif ($path == 'insertcomment') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $comment = isset($_POST['comment']) ? $_POST['comment'] : '';
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if (!isset($_SESSION['user_id'])) {
        header('Location: formLogin');
        exit;
    }
    if ($comment !== '' && $id > 0) {
        Controller::InsertComment($comment, $id);
    }
    header('Location: news?id=' . $id . '#cp-comments');
    exit;
}
elseif ($path == 'search') {
    $keyword = isset($_GET['otsi']) ? $_GET['otsi'] : (isset($_GET['q']) ? $_GET['q'] : '');
    $response = Controller::SearchNews($keyword);
}
elseif ($path == 'about') {
    $response = Controller::AboutSite();
}
elseif ($path == 'registerForm') {
    $response = Controller::registerForm();
}
elseif ($path == 'registerAnswer') {
    $response = Controller::registerUser();
}
elseif ($path == 'formLogin') {
    $response = Controller::formLogin();
}
elseif ($path == 'loginAnswer') {
    $response = Controller::loginUser();
}
elseif ($path == 'profile') {
    $response = Controller::profile();
}
elseif ($path == 'logout') {
    Controller::logout();
}
else {
    $response = Controller::error404();
}
?>
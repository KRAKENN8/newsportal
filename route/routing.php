<?php
// Вычислить маршрут из адресной строки
$rawUri = explode('?', $_SERVER['REQUEST_URI'])[0];
$cleanUri = rtrim($rawUri, '/');
$parts = explode('/', $cleanUri);
$path = end($parts);

if ($path == '' || $path == 'cyberpulse' || $path == 'index' || $path == 'index.php') {
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
elseif ($path == 'deletecomment' && isset($_GET['id'])) {
    Controller::DeleteComment($_GET['id']);
}
elseif ($path == 'quicksearch') {
    $q = isset($_GET['q']) ? trim($_GET['q']) : '';
    $results = [];
    if (mb_strlen($q) >= 2) {
        $rawResults = News::searchNews($q);
        $sliced = array_slice($rawResults, 0, 5);
        foreach ($sliced as $item) {
            $results[] = [
                'id'             => (int)$item['id'],
                'title'          => $item['title'],
                'category_name'  => $item['category_name'] ?? 'Technology',
                'reading_time'   => ViewNews::getReadingTime($item['text']),
                'comments_count' => (int)($item['comments_count'] ?? 0)
            ];
        }
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['results' => $results]);
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
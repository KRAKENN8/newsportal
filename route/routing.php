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
    $comment = isset($_POST['comment']) ? $_POST['comment'] : (isset($_GET['comment']) ? $_GET['comment'] : '');
    $id = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : 0);
    if (!empty($comment) && !empty($id)) {
        $response = Controller::InsertComment($comment, $id);
    } else {
        header('Location: news?id=' . (int)$id);
    }
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
else {
    $response = Controller::error404();
}
?>
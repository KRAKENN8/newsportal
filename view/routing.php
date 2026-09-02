<?php
// вычислить маршрут из адресной строки
$host = explode('?', $_SERVER['REQUEST_URI'])[0];
$num = substr_count($host, '/');
$path = explode('/', $host)[$num];

if ($path == '' OR $path == 'index' OR $path == 'index.php') {
    $response = Controller::StartSite();
}
elseif ($path == 'all') {
    $response = Controller::AllNews();
}
elseif ($path == 'category' and isset($_GET['id'])) {
    $response = Controller::NewsByCatID($_GET['id']);
}
elseif ($path == 'news' and isset($_GET['id'])) {
    $response = Controller::NewsByID($_GET['id']);
}
elseif ($path == 'registerForm') {
    // форма регистрации
    $response = Controller::registerForm();
}
elseif ($path == 'registerAnswer') {
    // регистрация пользователя
    $response = Controller::registerUser();
}
elseif ($path == 'insertcomment' and isset($_GET['comment'], $_GET['id'])) {
    // добавление комментария
    $response = Controller::InsertComment($_GET['comment'], $_GET['id']);
}
else {
    $response = Controller::error404();
}
?>
<?php
$rawUri = explode('?', $_SERVER['REQUEST_URI'])[0];
$cleanUri = rtrim($rawUri, '/');
$parts = explode('/', $cleanUri);
$path = end($parts);

if ($path == '' || $path == 'admin' || $path == 'index.php') {
    // Главная страница админки
    $response = controllerAdmin::formLoginSite();
}
elseif ($path == 'login') {
    // Форма входа
    $response = controllerAdmin::loginAction();
}
elseif ($path == 'logout') {
    // Выход
    $response = controllerAdmin::logoutAction();
}
//----------------- listNews
elseif ($path == 'newsAdmin') {
    $response = controllerAdminNews::NewsList();
}
//----------------- add news
elseif ($path == 'newsAdd') {
    $response = controllerAdminNews::newsAddForm();
}
elseif ($path == 'newsAddResult') {
    if (!Security::validateCsrfToken()) {
        $_SESSION['adminFlash'] = 'Security error: Invalid or missing CSRF token.';
        header('Location: newsAdmin');
        exit;
    }
    $response = controllerAdminNews::newsAddResult();
}
//----------------- edit news
elseif ($path == 'newsEdit' && isset($_GET['id'])) {
    $response = controllerAdminNews::newsEditForm($_GET['id']);
}
elseif ($path == 'newsEditResult' && isset($_GET['id'])) {
    if (!Security::validateCsrfToken()) {
        $_SESSION['adminFlash'] = 'Security error: Invalid or missing CSRF token.';
        header('Location: newsAdmin');
        exit;
    }
    $response = controllerAdminNews::newsEditResult($_GET['id']);
}
//----------------- delete news
elseif ($path == 'newsDel' && isset($_GET['id'])) {
    $response = controllerAdminNews::newsDeleteForm($_GET['id']);
}
elseif ($path == 'newsDelResult' && isset($_GET['id'])) {
    if (!Security::validateCsrfToken()) {
        $_SESSION['adminFlash'] = 'Security error: Invalid or missing CSRF token.';
        header('Location: newsAdmin');
        exit;
    }
    $response = controllerAdminNews::newsDeleteResult($_GET['id']);
}
//----------------- comments moderation
elseif ($path == 'commentsAdmin') {
    $response = controllerAdminComments::CommentsList();
}
elseif ($path == 'commentDel' && isset($_GET['id'])) {
    if (!Security::validateCsrfToken()) {
        $_SESSION['adminFlash'] = 'Security error: Invalid or missing CSRF token.';
        header('Location: commentsAdmin');
        exit;
    }
    $response = controllerAdminComments::commentDeleteResult($_GET['id']);
}
else {
    // Страница не существует
    $response = controllerAdmin::error404();
}
?>
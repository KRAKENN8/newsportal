<?php
$host = explode('?', $_SERVER['REQUEST_URI'])[0];
$num = substr_count($host, '/');
$path = explode('/', $host)[$num];

if ($path == '' OR $path == 'index.php') {
    $response = controllerAdmin::formLoginSite();
}
elseif ($path == 'login') {
    $response = controllerAdmin::loginAction();
}
elseif ($path == 'logout') {
    $response = controllerAdmin::logoutAction();
}
elseif ($path == 'newsAdmin') {
    $response = controllerAdminNews::NewsList();
}
elseif ($path == 'newsAdd') {
    $response = controllerAdminNews::newsAddForm();
}
elseif ($path == 'newsAddResult') {
    $response = controllerAdminNews::newsAddResult();
}
elseif ($path == 'newsEdit' && isset($_GET['id'])) {
    $response = controllerAdminNews::newsEditForm($_GET['id']);
}
elseif ($path == 'newsEditResult' && isset($_GET['id'])) {
    $response = controllerAdminNews::newsEditResult($_GET['id']);
}
else {
    $response = controllerAdmin::error404();
}
?>
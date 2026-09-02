<?php
    if ($path == 'category' and isset($_GET['id'])) {
        $response = Controller::NewsByCatID($_GET['id']);
    }
    elseif ($path == 'news' and isset($_GET['id'])) {
        $response = Controller::NewsByID($_GET['id']);
    }
    elseif ($path == 'insertcomment' and isset($_GET['comment'], $_GET['id'])) {
        $response = Controller::InsertComment($_GET['comment'], $_GET['id']);
    }
    else {
        $response = Controller::error404();
    }
?>
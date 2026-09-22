<?php
require_once('../inc/Security.php');
Security::sendSecurityHeaders();
Security::initSession();

require_once('../inc/Database.php'); // База данных

include_once("modelAdmin/modelAdmin.php");
include_once("modelAdmin/modelAdminNews.php");
include_once("modelAdmin/modelAdminCategory.php");
include_once("modelAdmin/modelAdminComments.php");
include_once("../view/news.php");

include_once("controllerAdmin/controllerAdmin.php");
include_once("controllerAdmin/controllerAdminNews.php");
include_once("controllerAdmin/controllerAdminComments.php");

include_once("routeAdmin/routingAdmin.php");

echo $response;
?>
<?php
ob_start();

$newsId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

ViewNews::ReadNews($n);

Controller::Comments($newsId);

echo '<div style="margin-top:25px; background:var(--cp-bg-surface); border:1px solid var(--cp-border); border-radius:var(--cp-radius-md); padding:25px;">';
ViewComments::CommentsForm();
echo '</div>';

$content = ob_get_clean();
include_once 'view/layout.php';
?>
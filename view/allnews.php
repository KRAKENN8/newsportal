<?php
ob_start();
$categories = Category::getAllCategory();
?>

<div class="cp-breadcrumbs">
    <a href="./"><i class="fa fa-home"></i> Home</a> &raquo; 
    <span>All Publications</span>
</div>

<div class="cp-section-header">
    <h1 class="cp-section-title">
        All Technology Publications
    </h1>
    <span style="font-size:13px; color:var(--cp-text-dim); font-family:var(--cp-font-mono);">
        Total articles: <?php echo count($arr); ?>
    </span>
</div>

<!-- Category pills switcher -->
<div class="cp-filter-pills">
    <a href="all" class="cp-pill active"><i class="fa fa-globe"></i> All Topics</a>
    <?php
    foreach ($categories as $cat) {
        echo '<a href="category?id=' . $cat['id'] . '" class="cp-pill">' . htmlspecialchars($cat['name']) . '</a>';
    }
    ?>
</div>

<?php
ViewNews::AllNews($arr);
?>

<?php
$content = ob_get_clean();
include_once 'view/layout.php';
?>
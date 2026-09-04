<?php
ob_start();
$catId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$currentCategory = Category::getCategoryByID($catId);
$catName = $currentCategory ? htmlspecialchars($currentCategory['name']) : 'Category';
$allCategories = Category::getAllCategory();
?>

<div class="cp-breadcrumbs">
    <a href="./"><i class="fa fa-home"></i> Home</a> &raquo; 
    <a href="all">Topics</a> &raquo; 
    <span><?php echo $catName; ?></span>
</div>

<div class="cp-section-header">
    <h1 class="cp-section-title">
        <i class="fa fa-folder-open-o" style="color:var(--cp-cyan);"></i> Topic: <?php echo $catName; ?>
    </h1>
    <span style="font-size:13px; color:var(--cp-text-dim); font-family:var(--cp-font-mono);">
        Articles found: <?php echo count($arr); ?>
    </span>
</div>

<!-- Category pills switcher -->
<div class="cp-filter-pills">
    <a href="all" class="cp-pill"><i class="fa fa-globe"></i> All</a>
    <?php
    foreach ($allCategories as $cat) {
        $isActive = ($cat['id'] == $catId) ? ' active' : '';
        echo '<a href="category?id=' . $cat['id'] . '" class="cp-pill' . $isActive . '">' . htmlspecialchars($cat['name']) . '</a>';
    }
    ?>
</div>

<?php
ViewNews::NewsByCategory($arr);
?>

<?php
$content = ob_get_clean();
include_once 'view/layout.php';
?>
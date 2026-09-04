<?php
ob_start();
$queryClean = isset($_GET['otsi']) ? htmlspecialchars($_GET['otsi']) : (isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '');
?>

<div class="cp-breadcrumbs">
    <a href="./"><i class="fa fa-home"></i> Home</a> &raquo; 
    <span>Search Results</span>
</div>

<div class="cp-section-header">
    <h1 class="cp-section-title">
        <i class="fa fa-search" style="color:var(--cp-cyan);"></i> Search Results: &ldquo;<?php echo $queryClean; ?>&rdquo;
    </h1>
    <span style="font-size:13px; color:var(--cp-text-dim); font-family:var(--cp-font-mono);">
        Results found: <?php echo count($arr); ?>
    </span>
</div>

<?php
if (!empty($arr)) {
    ViewNews::NewsByCategory($arr);
} else {
    echo '<div style="background:var(--cp-bg-surface); border:1px solid var(--cp-border); border-radius:var(--cp-radius-md); padding:40px 20px; text-align:center; margin:20px 0;">';
    echo '  <i class="fa fa-search-minus" style="font-size:48px; color:var(--cp-cyan); opacity:0.6; margin-bottom:15px; display:block;"></i>';
    echo '  <h3 style="color:#fff; margin-bottom:10px;">No Results Found</h3>';
    echo '  <p style="color:var(--cp-text-muted); max-width:500px; margin:0 auto 20px;">We could not find any articles matching &ldquo;' . $queryClean . '&rdquo;. Try adjusting your keywords or explore the full news stream.</p>';
    echo '  <a href="all" class="cp-btn cp-btn-primary"><i class="fa fa-newspaper-o"></i> View All Publications</a>';
    echo '</div>';
}
?>

<?php
$content = ob_get_clean();
include_once 'view/layout.php';
?>

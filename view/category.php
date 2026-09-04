<?php
echo '<li><a class="cp-dropdown-item" href="all"><span><i class="fa fa-globe"></i> All Topics</span> <i class="fa fa-angle-right"></i></a></li>';
foreach ($arr as $value) {
    $catId = (int)$value['id'];
    $catName = htmlspecialchars($value['name']);
    echo '<li>';
    echo '  <a class="cp-dropdown-item" href="category?id=' . $catId . '">';
    echo '    <span><i class="fa fa-tag" style="color:var(--cp-cyan);"></i> ' . $catName . '</span>';
    echo '    <i class="fa fa-angle-right"></i>';
    echo '  </a>';
    echo '</li>';
}
?>
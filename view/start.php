<?php
ob_start();
?>

<!-- Hero Banner -->
<div class="cp-hero-banner">
    <span class="cp-hero-pill">
        <i class="fa fa-bolt"></i> INSIGHTS INTO THE TECHNOLOGICAL FUTURE
    </span>
    <h1 class="cp-hero-title">
        Artificial Intelligence, Quantum Computing & Cybersecurity
    </h1>
    <p class="cp-hero-desc">
        Explore the foundational breakthroughs shaping our future: from reasoning-first AI agents to interplanetary radar missions and next-generation silicon.
    </p>
    <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
        <a href="all" class="cp-btn cp-btn-primary">
            <i class="fa fa-newspaper-o"></i> Read All Stories
        </a>
        <a href="category?id=1" class="cp-btn cp-btn-outline">
            <i class="fa fa-microchip"></i> AI & Neural Networks
        </a>
    </div>
</div>

<!-- Section Header -->
<div class="cp-section-header">
    <h2 class="cp-section-title">
        Featured Stories of the Week
    </h2>
    <a href="all" class="cp-read-more">
        All Stories <i class="fa fa-long-arrow-right"></i>
    </a>
</div>

<?php
ViewNews::NewsByCategory($arr);
?>

<div style="margin-top:40px; padding:25px; background:var(--cp-bg-surface); border:1px solid var(--cp-border); border-radius:var(--cp-radius-md); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:20px;">
    <div>
        <h4 style="color:#fff; margin:0 0 5px; font-weight:700;">Want to stay ahead of the next tech wave?</h4>
        <p style="color:var(--cp-text-dim); margin:0; font-size:13px;">Join our community of engineers, researchers, and technology leaders.</p>
    </div>
    <a href="registerForm" class="cp-btn cp-btn-primary">
        <i class="fa fa-user-plus"></i> Create Free Account
    </a>
</div>

<?php
$content = ob_get_clean();
include_once 'view/layout.php';
?>
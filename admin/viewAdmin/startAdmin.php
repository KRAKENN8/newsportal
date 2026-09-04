<?php
ob_start();
$db = new Database();
$newsCount = $db->getOne("SELECT COUNT(id) as c FROM news")['c'] ?? 0;
$catCount = $db->getOne("SELECT COUNT(id) as c FROM category")['c'] ?? 0;
$commCount = $db->getOne("SELECT COUNT(id) as c FROM comments")['c'] ?? 0;
$usersCount = $db->getOne("SELECT COUNT(id) as c FROM users")['c'] ?? 0;
?>

<div style="margin-bottom:25px;">
    <h2 style="font-size:24px; font-weight:800; color:#fff; margin-top:0; margin-bottom:8px;">
        Control Console // CyberPulse
    </h2>
    <p style="color:var(--cp-text-muted); font-size:14px; margin:0;">
        Welcome to the central content administration and analytics dashboard.
    </p>
</div>

<!-- Stat Cards -->
<div class="cp-stats-grid">
    <div class="cp-stat-card">
        <div class="cp-stat-icon cyan">
            <i class="fa fa-newspaper-o"></i>
        </div>
        <div>
            <div class="cp-stat-value"><?php echo $newsCount; ?></div>
            <div class="cp-stat-label">Articles</div>
        </div>
    </div>

    <div class="cp-stat-card">
        <div class="cp-stat-icon purple">
            <i class="fa fa-th-large"></i>
        </div>
        <div>
            <div class="cp-stat-value"><?php echo $catCount; ?></div>
            <div class="cp-stat-label">Topics</div>
        </div>
    </div>

    <div class="cp-stat-card">
        <div class="cp-stat-icon emerald">
            <i class="fa fa-comments"></i>
        </div>
        <div>
            <div class="cp-stat-value"><?php echo $commCount; ?></div>
            <div class="cp-stat-label">Comments</div>
        </div>
    </div>

    <div class="cp-stat-card">
        <div class="cp-stat-icon amber">
            <i class="fa fa-users"></i>
        </div>
        <div>
            <div class="cp-stat-value"><?php echo $usersCount; ?></div>
            <div class="cp-stat-label">Users</div>
        </div>
    </div>
</div>

<!-- Quick Action Cards -->
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:20px; margin-bottom:30px;">
    <div style="background:var(--cp-bg-surface); border:1px solid var(--cp-border); border-radius:var(--cp-radius-md); padding:25px;">
        <h3 style="color:#fff; font-size:18px; margin-top:0; margin-bottom:10px; display:flex; align-items:center; gap:8px;">
            <i class="fa fa-pencil" style="color:var(--cp-cyan);"></i> Content Creation
        </h3>
        <p style="color:var(--cp-text-muted); font-size:13px; margin-bottom:20px;">
            Publish a new article on AI breakthroughs, quantum computing, or cybersecurity with custom media.
        </p>
        <a href="newsAdd" class="cp-btn cp-btn-primary">
            <i class="fa fa-plus"></i> Write Article
        </a>
    </div>

    <div style="background:var(--cp-bg-surface); border:1px solid var(--cp-border); border-radius:var(--cp-radius-md); padding:25px;">
        <h3 style="color:#fff; font-size:18px; margin-top:0; margin-bottom:10px; display:flex; align-items:center; gap:8px;">
            <i class="fa fa-list" style="color:var(--cp-purple);"></i> Publication Editor
        </h3>
        <p style="color:var(--cp-text-muted); font-size:13px; margin-bottom:20px;">
            Review and update existing articles, replace illustrations, and curate public content.
        </p>
        <a href="newsAdmin" class="cp-btn cp-btn-outline">
            <i class="fa fa-cog"></i> Article Catalog
        </a>
    </div>

    <div style="background:var(--cp-bg-surface); border:1px solid var(--cp-border); border-radius:var(--cp-radius-md); padding:25px;">
        <h3 style="color:#fff; font-size:18px; margin-top:0; margin-bottom:10px; display:flex; align-items:center; gap:8px;">
            <i class="fa fa-globe" style="color:var(--cp-emerald);"></i> Public News Portal
        </h3>
        <p style="color:var(--cp-text-muted); font-size:13px; margin-bottom:20px;">
            Preview how your stories, media cards, and reader discussions appear on the live site.
        </p>
        <a href="../" target="_blank" class="cp-btn cp-btn-outline">
            <i class="fa fa-external-link"></i> Open Website
        </a>
    </div>
</div>

<?php
$content = ob_get_clean();
include "viewAdmin/templates/layout.php";
?>
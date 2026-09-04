<?php
ob_start();
?>

<div class="cp-breadcrumbs">
    <a href="./"><i class="fa fa-home"></i> Home</a> &raquo; 
    <span>About CyberPulse</span>
</div>

<div class="cp-hero-banner" style="margin-bottom:30px;">
    <span class="cp-hero-pill"><i class="fa fa-info-circle"></i> ABOUT THE PLATFORM</span>
    <h1 class="cp-hero-title">CYBERPULSE // Future Tech Media</h1>
    <p class="cp-hero-desc">
        A next-generation digital media publication dedicated to in-depth technical analysis, quantum breakthroughs, autonomous artificial intelligence, and cybersecurity.
    </p>
</div>

<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:25px; margin-bottom:40px;">
    <div style="background:var(--cp-bg-surface); border:1px solid var(--cp-border); border-radius:var(--cp-radius-md); padding:25px;">
        <div style="font-size:28px; color:var(--cp-cyan); margin-bottom:12px;"><i class="fa fa-bullseye"></i></div>
        <h3 style="color:#fff; font-size:18px; font-weight:700; margin-bottom:10px;">Our Mission</h3>
        <p style="color:var(--cp-text-muted); font-size:14px; margin:0;">
            To deliver rigorous, technically grounded reporting on the innovations reshaping our world, cutting through hype to focus on real architecture.
        </p>
    </div>

    <div style="background:var(--cp-bg-surface); border:1px solid var(--cp-border); border-radius:var(--cp-radius-md); padding:25px;">
        <div style="font-size:28px; color:var(--cp-purple); margin-bottom:12px;"><i class="fa fa-shield"></i></div>
        <h3 style="color:#fff; font-size:18px; font-weight:700; margin-bottom:10px;">Technical Independence</h3>
        <p style="color:var(--cp-text-muted); font-size:14px; margin:0;">
            Authored by engineers, security analysts, and researchers with direct hands-on experience in distributed systems, cryptography, and machine learning.
        </p>
    </div>

    <div style="background:var(--cp-bg-surface); border:1px solid var(--cp-border); border-radius:var(--cp-radius-md); padding:25px;">
        <div style="font-size:28px; color:var(--cp-emerald); margin-bottom:12px;"><i class="fa fa-comments"></i></div>
        <h3 style="color:#fff; font-size:18px; font-weight:700; margin-bottom:10px;">Open Community</h3>
        <p style="color:var(--cp-text-muted); font-size:14px; margin:0;">
            Every registered member can participate in technical discussions, review benchmark reproducibility, and share domain expertise.
        </p>
    </div>
</div>

<div style="background:var(--cp-bg-surface); border:1px solid var(--cp-border); border-radius:var(--cp-radius-md); padding:30px; margin-bottom:30px;">
    <h2 style="color:#fff; font-size:20px; font-weight:700; margin-top:0; margin-bottom:15px; border-bottom:1px solid var(--cp-border); padding-bottom:10px;">
        <i class="fa fa-cogs" style="color:var(--cp-cyan);"></i> Platform Architecture
    </h2>
    <p style="color:var(--cp-text-muted); line-height:1.7;">
        <strong>CyberPulse</strong> is engineered on a lean <code>PHP (MVC) + MySQL + High-Tech Vanilla UI</code> stack. The engine features secure session authentication, dynamic category taxonomy, responsive SVG/BLOB asset storage, and fast full-text querying.
    </p>
    <div style="margin-top:20px; display:flex; gap:12px; flex-wrap:wrap;">
        <a href="./" class="cp-btn cp-btn-primary"><i class="fa fa-home"></i> Back to Homepage</a>
        <a href="registerForm" class="cp-btn cp-btn-outline"><i class="fa fa-user-plus"></i> Join the Community</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include_once 'view/layout.php';
?>

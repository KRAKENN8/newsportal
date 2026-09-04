<?php
ob_start();
?>

<div style="text-align:center; padding:70px 20px; max-width:650px; margin:0 auto;">
    <div style="font-size:90px; font-weight:900; font-family:var(--cp-font-mono); line-height:1; color:var(--cp-cyan); text-shadow:0 0 30px var(--cp-cyan-glow); margin-bottom:15px;">
        404
    </div>
    <span class="cp-hero-pill" style="border-color:rgba(239, 68, 68, 0.4); color:#f87171; background:rgba(239, 68, 68, 0.1);">
        <i class="fa fa-exclamation-circle"></i> ROUTING ANOMALY // NODE NOT FOUND
    </span>
    <h2 style="color:#ffffff; font-size:28px; font-weight:800; margin:20px 0 12px;">
        The Requested Page Does Not Exist
    </h2>
    <p style="color:var(--cp-text-muted); font-size:15px; margin-bottom:30px; line-height:1.6;">
        The article or system endpoint you requested may have been moved, decommissioned, or entered incorrectly. Check the URL or explore the public news catalog.
    </p>
    <div style="display:flex; justify-content:center; gap:14px; flex-wrap:wrap;">
        <a href="./" class="cp-btn cp-btn-primary">
            <i class="fa fa-home"></i> Back to Homepage
        </a>
        <a href="all" class="cp-btn cp-btn-outline">
            <i class="fa fa-newspaper-o"></i> Explore All Publications
        </a>
    </div>
</div>

<?php
$content = ob_get_clean();
include_once 'view/layout.php';
?>
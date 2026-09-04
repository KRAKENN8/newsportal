<?php ob_start() ?>
<div style="text-align:center; padding:50px 20px; max-width:600px; margin:0 auto;">
    <div style="font-size:72px; font-weight:900; font-family:var(--cp-font-mono); color:var(--cp-cyan); line-height:1; margin-bottom:15px;">
        404
    </div>
    <h2 style="color:#ffffff; font-size:24px; font-weight:800; margin-bottom:10px;">Control Console Section Not Found</h2>
    <p style="color:var(--cp-text-muted); font-size:14px; margin-bottom:25px;">
        The requested admin action or URL is not registered in the control panel routing table.
    </p>
    <a href="./" class="cp-btn cp-btn-primary">
        <i class="fa fa-dashboard"></i> Back to Dashboard
    </a>
</div>
<?php $content = ob_get_clean(); ?>

<?php include "viewAdmin/templates/layout.php"; ?>
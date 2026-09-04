<?php ob_start(); ?>

<div class="cp-form-card" style="border-color:rgba(239, 68, 68, 0.4);">
    <h2 style="color:#f87171;">
        <i class="fa fa-trash-o"></i> Confirm Deletion #<?php echo htmlspecialchars($id); ?>
    </h2>

    <?php
    if (isset($test)) {
        if ($test == true) {
    ?>
            <div class="alert alert-info">
                <i class="fa fa-check-circle"></i> <strong>Publication successfully deleted!</strong>
                <a href="newsAdmin" class="cp-btn cp-btn-primary" style="margin-left:15px; padding:4px 12px; font-size:12px;">Return to Article List</a>
            </div>
    <?php
        } else if ($test == false) {
    ?>
            <div class="alert alert-warning">
                <i class="fa fa-exclamation-triangle"></i> <strong>Error deleting publication!</strong>
                <a href="newsAdmin" class="cp-btn cp-btn-outline" style="margin-left:15px; padding:4px 12px; font-size:12px;">Back to Article List</a>
            </div>
    <?php
        }
    } else {
        $imgSrc = ViewNews::getImageSrc($detail['picture']);
    ?>
        <div style="background:rgba(239, 68, 68, 0.08); border:1px dashed rgba(239, 68, 68, 0.3); border-radius:8px; padding:16px; margin-bottom:20px; color:#fca5a5; font-size:14px;">
            <i class="fa fa-warning"></i> <strong>Warning!</strong> This action is permanent. The article and all associated comments will be purged from the database.
        </div>

        <form method="POST" action="newsDelResult?id=<?php echo $id; ?>">
            <div style="margin-bottom:15px;">
                <label style="display:block; font-weight:600; margin-bottom:6px; color:var(--cp-text-muted);">Title</label>
                <div style="font-size:16px; font-weight:700; color:#fff;"><?php echo htmlspecialchars($detail['title']); ?></div>
            </div>

            <div style="margin-bottom:15px;">
                <label style="display:block; font-weight:600; margin-bottom:6px; color:var(--cp-text-muted);">Topic</label>
                <span class="cp-badge-category" style="position:static; display:inline-block;"><?php echo htmlspecialchars($detail['name']); ?></span>
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block; font-weight:600; margin-bottom:6px; color:var(--cp-text-muted);">Illustration</label>
                <div class="cp-preview-box">
                    <img src="<?php echo $imgSrc; ?>" alt="Illustration">
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:12px; padding-top:15px; border-top:1px solid var(--cp-border);">
                <button type="submit" class="cp-btn cp-btn-primary" style="background:#dc2626; border-color:#dc2626; color:#fff;" name="save">
                    <i class="fa fa-trash"></i> Yes, Delete Publication
                </button>
                <a href="newsAdmin" class="cp-btn cp-btn-outline">
                    <i class="fa fa-times"></i> Cancel
                </a>
            </div>
        </form>
    <?php
    }
    ?>
</div>

<?php $content = ob_get_clean(); ?>
<?php include "viewAdmin/templates/layout.php"; ?>
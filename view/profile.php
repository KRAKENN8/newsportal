<?php
ob_start();
?>
<div style="max-width:600px; margin:40px auto;">
    <div style="background:var(--cp-bg-surface); border:1px solid rgba(255,255,255,0.08); border-radius:var(--cp-radius-md); padding:35px 30px;">

        <div style="text-align:center; margin-bottom:25px;">
            <div style="width:70px; height:70px; border-radius:50%; background:rgba(0, 240, 255, 0.12); color:var(--cp-cyan); display:flex; align-items:center; justify-content:center; font-size:30px; margin:0 auto 15px;">
                <i class="fa fa-user"></i>
            </div>
            <h2 style="color:#fff; margin:0 0 5px;">My Profile</h2>
            <p style="color:var(--cp-text-muted); margin:0;"><?php echo htmlspecialchars($user['email']); ?></p>
        </div>

        <?php if ($result !== null): ?>
            <div style="text-align:center; margin-bottom:20px; padding:12px; border-radius:8px; <?php echo $result[0] ? 'background:rgba(16,185,129,0.12); color:#6ee7b7;' : 'background:rgba(239,68,68,0.12); color:#fca5a5;'; ?>">
                <?php echo htmlspecialchars($result[1]); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="profile">
            <div class="cp-form-group">
                <label for="username" class="cp-form-label"><i class="fa fa-user"></i> Username</label>
                <input id="username" type="text" class="cp-form-control" name="username"
                       value="<?php echo htmlspecialchars($user['username']); ?>" required maxlength="50">
            </div>
            <button type="submit" class="cp-btn cp-btn-primary" style="width:100%; padding:13px; margin-top:10px;">
                <i class="fa fa-save"></i> Save Changes
            </button>
        </form>

        <div style="text-align:center; margin-top:20px;">
            <a href="./" style="color:var(--cp-text-dim); font-size:13px;"><i class="fa fa-chevron-left"></i> Back to Homepage</a>
        </div>

    </div>
</div>
<?php
$content = ob_get_clean();
include "view/layout.php";
?>
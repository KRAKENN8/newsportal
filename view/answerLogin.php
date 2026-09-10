<?php
ob_start();

$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['password'])) {
    require_once "model/Login.php"; // подключите правильный путь к файлу с классом Login
    $result = Login::loginUser();
}
?>

<div style="max-width:600px; margin:40px auto;">
    <?php
    if (isset($result) && $result !== null) {
        if ($result[0] === true) {
    ?>
        <div style="background:var(--cp-bg-surface); border:1px solid rgba(16, 185, 129, 0.4); border-radius:var(--cp-radius-md); padding:35px 30px; text-align:center; box-shadow:0 0 25px rgba(16, 185, 129, 0.15);">
            <div style="width:60px; height:60px; border-radius:50%; background:rgba(16, 185, 129, 0.15); color:var(--cp-emerald); display:flex; align-items:center; justify-content:center; font-size:28px; margin:0 auto 20px;">
                <i class="fa fa-check"></i>
            </div>
            <h2 style="color:#fff; margin-top:0; margin-bottom:10px;">Login Completed!</h2>
            <p style="color:var(--cp-text-muted); margin-bottom:25px;">
               You have successfully logged into your <strong>Cyberpulse</strong> account.
            </p>
            <div style="display:flex; justify-content:center; gap:12px; flex-wrap:wrap;">
                <a href="./all" class="cp-btn cp-btn-primary"><i class="fa fa-home"></i> Back to Homepage</a>
                <a href="admin/" class="cp-btn cp-btn-outline"><i class="fa fa-lock"></i> Author Console</a>
            </div>
        </div>
    <?php
        } else {
    ?>
        <div style="background:var(--cp-bg-surface); border:1px solid rgba(239, 68, 68, 0.4); border-radius:var(--cp-radius-md); padding:35px 30px; text-align:center; box-shadow:0 0 25px rgba(239, 68, 68, 0.15);">
            <div style="width:60px; height:60px; border-radius:50%; background:rgba(239, 68, 68, 0.15); color:var(--cp-red); display:flex; align-items:center; justify-content:center; font-size:28px; margin:0 auto 20px;">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <h2 style="color:#fff; margin-top:0; margin-bottom:10px;">Login Error</h2>
            <div style="color:#fca5a5; margin-bottom:25px; font-size:15px;">
                <?php echo htmlspecialchars($result[1]); ?>
            </div>
            <a href="formLogin" class="cp-btn cp-btn-primary">
                <i class="fa fa-refresh"></i> Try Again
            </a>
        </div>
    <?php
        }
    }
    ?>
</div>

<?php
$content = ob_get_clean();
include "view/layout.php";
?>
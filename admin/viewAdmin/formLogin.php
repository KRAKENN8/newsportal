<?php
if (isset($_SESSION['userId']) && isset($_SESSION['sessionId'])) {
    header('Location: ./');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Login // CYBERPULSE</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- CSS -->
    <link href="public/css/login.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <form class="form-signin" action="login" method="POST">
        <div style="text-align:center; margin-bottom:20px;">
            <div style="width:50px; height:50px; margin:0 auto 12px; background:linear-gradient(135deg, var(--cp-cyan), var(--cp-purple)); border-radius:10px; display:flex; align-items:center; justify-content:center; color:#090d16; font-size:22px; box-shadow:0 0 20px var(--cp-cyan-glow);">
                <i class="fa fa-terminal"></i>
            </div>
            <h3 class="form-signin-heading" style="margin:0;">CYBERPULSE // ADMIN</h3>
            <p style="color:var(--cp-text-dim); font-size:12px; margin-top:4px;">System Administrator Authentication</p>
        </div>

        <?php
        if (isset($_SESSION['errorString'])) {
            echo '<div class="alert alert-warning" style="margin-bottom:15px; font-size:13px;"><i class="fa fa-exclamation-triangle"></i> ' . htmlspecialchars($_SESSION['errorString']) . '</div>';
            unset($_SESSION['errorString']);
        }
        ?>

        <div style="margin-bottom:15px;">
            <label style="font-size:12px; color:var(--cp-text-muted); display:block; margin-bottom:5px;"><i class="fa fa-envelope-o"></i> Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="admin@newsportal.ee" required autofocus value="admin@newsportal.ee">
        </div>

        <div style="margin-bottom:20px;">
            <label style="font-size:12px; color:var(--cp-text-muted); display:block; margin-bottom:5px;"><i class="fa fa-key"></i> Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required value="123456">
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit" name="btnLogin">
            <i class="fa fa-sign-in"></i> Log In
        </button>

        <div style="margin-top:20px; padding:12px; background:rgba(0, 240, 255, 0.05); border:1px dashed rgba(0, 240, 255, 0.2); border-radius:6px; font-size:12px; color:var(--cp-text-muted); text-align:center;">
            <strong>Demo Credentials:</strong><br>
            <code>admin@newsportal.ee</code> / <code>123456</code>
        </div>

        <p style="padding-top:20px; text-align:center; margin-bottom:0;">
            <a href="../" style="color:var(--cp-text-dim); font-size:13px;"><i class="fa fa-chevron-left"></i> Return to News Portal</a>
        </p>
    </form>
</div>
</body>
</html>
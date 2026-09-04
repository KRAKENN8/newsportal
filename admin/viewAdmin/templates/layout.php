<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>CyberPulse // Admin Control Console</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Admin CSS -->
    <link href="public/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/mystyle.css" rel="stylesheet">
    <!-- JS -->
    <script src="public/js/jquery.min.js"></script>
    <script src="public/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
<?php
if (isset($_SESSION["userId"]) && isset($_SESSION["sessionId"])) {
?>
    <header class="cp-admin-header">
        <div class="cp-admin-brand">
            <span style="width:32px; height:32px; background:linear-gradient(135deg, var(--cp-cyan), var(--cp-purple)); border-radius:6px; display:inline-flex; align-items:center; justify-content:center; color:#090d16; font-size:16px;">
                <i class="fa fa-sliders"></i>
            </span>
            <span>CYBER<span style="color:var(--cp-cyan);">PULSE</span> // ADMIN</span>
        </div>

        <nav class="cp-admin-nav">
            <a href="./" class="<?php echo (!isset($_GET['id']) && (basename($_SERVER['REQUEST_URI']) == 'admin' || basename($_SERVER['REQUEST_URI']) == '')) ? 'active' : ''; ?>">
                <i class="fa fa-dashboard"></i> Dashboard
            </a>
            <a href="newsAdmin">
                <i class="fa fa-list-alt"></i> Article List
            </a>
            <a href="newsAdd">
                <i class="fa fa-plus-circle" style="color:var(--cp-cyan);"></i> Add Article
            </a>
            <a href="../" target="_blank" style="color:var(--cp-cyan);">
                <i class="fa fa-external-link"></i> View Website
            </a>
        </nav>

        <div class="cp-admin-user">
            <span class="cp-user-badge">
                <i class="fa fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION["name"] ?? 'Admin'); ?>
            </span>
            <a href="logout" class="cp-btn cp-btn-outline" style="padding:5px 12px; font-size:12px;" title="End Session">
                <i class="fa fa-sign-out"></i> Sign Out
            </a>
        </div>
    </header>
<?php
}
?>

    <div id="content">
        <?php 
        if (isset($content)) {
            echo $content; 
        }
        ?>
    </div>

    <footer class="footer">
        <p>&copy; <?php echo date('Y'); ?> CyberPulse Admin Engine &bull; System Architecture Active</p>
    </footer>
</div>
</body>
</html>
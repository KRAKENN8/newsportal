<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>CYBERPULSE // Future Tech, AI & Science Media</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- CyberPulse CSS -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="public/css/mystyle.css">
</head>
<body>

    <!-- Header -->
    <header class="cp-header">
        <!-- Ticker line -->
        <div class="cp-ticker">
            <div class="divBox cp-ticker-inner">
                <div class="cp-ticker-badge">
                    <span class="cp-pulse-dot"></span>
                    <span>CYBERPULSE // LIVE STREAM</span>
                </div>
                <div style="color:var(--cp-text-dim); display:none; @media(min-width:768px){display:block;}">
                    <span>⚡ Quantum Processors • Multimodal LLMs • 2nm Silicon • Next-Gen Photorealism</span>
                </div>
                <div style="color:var(--cp-text-muted);">
                    <i class="fa fa-terminal"></i> SYS_VER: 2.6.4-AI
                </div>
            </div>
        </div>

        <!-- Main Navigation Bar -->
        <div class="divBox">
            <nav class="cp-navbar">
                <!-- Brand -->
                <a href="./" class="cp-brand">
                    <div class="cp-brand-icon">
                        <i class="fa fa-microchip"></i>
                    </div>
                    <div class="cp-brand-text">
                        CYBER<span>PULSE</span>
                        <span class="cp-brand-tagline">Future Tech Media</span>
                    </div>
                </a>

                <!-- Nav Menu Links -->
                <ul class="cp-nav-links">
                    <li class="cp-nav-item">
                        <a href="./" class="cp-nav-link"><i class="fa fa-home"></i> Home</a>
                    </li>
                    <li class="cp-nav-item cp-dropdown">
                        <a href="#" class="cp-nav-link">
                            <i class="fa fa-th-large"></i> Topics <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="cp-dropdown-menu">
                            <?php Controller::AllCategory(); ?>
                        </ul>
                    </li>
                    <li class="cp-nav-item">
                        <a href="all" class="cp-nav-link"><i class="fa fa-newspaper-o"></i> All News</a>
                    </li>
                    <li class="cp-nav-item">
                        <a href="about" class="cp-nav-link"><i class="fa fa-info-circle"></i> About</a>
                    </li>
                </ul>

                <!-- Search form -->
                <form action="search" method="GET" class="cp-search-form">
                    <input type="text" name="otsi" class="cp-search-input" placeholder="Search tech topics..." required value="<?php echo isset($_GET['otsi']) ? htmlspecialchars($_GET['otsi']) : ''; ?>">
                    <button type="submit" class="cp-search-btn" title="Search">
                        <i class="fa fa-search"></i>
                    </button>
                </form>

                <!-- Actions -->
                <div class="cp-header-actions">
                    <a href="registerForm" class="cp-btn cp-btn-outline">
                        <i class="fa fa-user-plus"></i> Sign Up
                    </a>
                    <a href="admin/" class="cp-btn cp-btn-admin" title="Admin Control Panel">
                        <i class="fa fa-lock"></i> Admin
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="cp-main-content">
        <div class="divBox">
            <?php
            if (isset($content)) {
                echo $content;
            } else {
                echo '<div class="alert alert-warning">Content not found.</div>';
            }
            ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="cp-footer">
        <div class="divBox cp-footer-inner">
            <div>
                <div style="font-size:16px; font-weight:800; color:#fff; margin-bottom:6px;">
                    CYBER<span style="color:var(--cp-cyan);">PULSE</span> // TECH MEDIA
                </div>
                <p style="margin:0; max-width:450px; font-size:13px; color:var(--cp-text-dim);">
                    Independent digital journalism covering quantum computing, artificial intelligence, cybersecurity, and deep-tech frontiers.
                </p>
            </div>
            <div>
                <ul class="cp-footer-links">
                    <li><a href="./">Home</a></li>
                    <li><a href="all">News Stream</a></li>
                    <li><a href="about">About Us</a></li>
                    <li><a href="registerForm">Join Community</a></li>
                    <li><a href="admin/">Dashboard</a></li>
                </ul>
            </div>
            <div style="font-family:var(--cp-font-mono); font-size:12px;">
                &copy; <?php echo date('Y'); ?> CyberPulse Media &bull; All Rights Reserved.
            </div>
        </div>
    </footer>

</body>
</html>
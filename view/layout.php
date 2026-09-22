<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 
$rawUri = explode('?', $_SERVER['REQUEST_URI'] ?? '')[0];
$cleanUri = rtrim($rawUri, '/');
$parts = explode('/', $cleanUri);
$currentRoute = end($parts);
?>
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

    <!-- Reading Progress Bar -->
    <div class="cp-reading-progress" id="cpReadingProgress"></div>

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
                <a href="./all" class="cp-brand">
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
                        <a href="./all" class="cp-nav-link <?php echo ($currentRoute === '' || $currentRoute === 'cyberpulse' || $currentRoute === 'index.php') ? 'active' : ''; ?>"><i class="fa fa-home"></i> Home</a>
                    </li>
                    <li class="cp-nav-item cp-dropdown">
                        <a href="#" class="cp-nav-link <?php echo ($currentRoute === 'category') ? 'active' : ''; ?>">
                            <i class="fa fa-th-large"></i> Topics <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="cp-dropdown-menu">
                            <?php Controller::AllCategory(); ?>
                        </ul>
                    </li>
                    <li class="cp-nav-item">
                        <a href="all" class="cp-nav-link <?php echo ($currentRoute === 'all') ? 'active' : ''; ?>"><i class="fa fa-newspaper-o"></i> All News</a>
                    </li>
                    <li class="cp-nav-item">
                        <a href="about" class="cp-nav-link <?php echo ($currentRoute === 'about') ? 'active' : ''; ?>"><i class="fa fa-info-circle"></i> About</a>
                    </li>
                </ul>

                <!-- Search form with live autocomplete -->
                <div class="cp-search-wrapper">
                    <form action="search" method="GET" class="cp-search-form" id="cpSearchForm">
                        <input type="text" name="otsi" id="cpSearchInput" class="cp-search-input" placeholder="Search tech topics..." autocomplete="off" required value="<?php echo isset($_GET['otsi']) ? htmlspecialchars($_GET['otsi']) : ''; ?>">
                        <button type="submit" class="cp-search-btn" title="Search">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                    <div class="cp-search-dropdown" id="cpSearchDropdown"></div>
                </div>

                <!-- Actions -->
                <div class="cp-header-actions">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="cp-nav-item cp-dropdown">
                            <a href="#" class="cp-nav-link">
                                <i class="fa fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?> <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="cp-dropdown-menu">
                                <li><a href="profile"><i class="fa fa-id-badge"></i> My Profile</a></li>
                                <li><a href="logout"><i class="fa fa-sign-out"></i> Log Out</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="registerForm" class="cp-btn cp-btn-outline">
                            <i class="fa fa-user-plus"></i> Sign Up
                        </a>
                        <a href="formLogin" class="cp-btn cp-btn-outline">
                            <i class="fa fa-sign-in"></i> Log In
                        </a>
                    <?php endif; ?>
                    <a href="admin/" class="cp-btn cp-btn-admin" title="Admin Control Panel">
                        <i class="fa fa-lock"></i> Admin
                    </a>
                    <!-- Mobile Hamburger Toggle -->
                    <button type="button" class="cp-mobile-toggle" id="cpMobileToggle" aria-label="Toggle Navigation">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </nav>
        </div>
    </header>

    <!-- Mobile Drawer Backdrop -->
    <div class="cp-mobile-backdrop" id="cpMobileBackdrop"></div>

    <!-- Mobile Navigation Drawer -->
    <aside class="cp-mobile-drawer" id="cpMobileDrawer" aria-label="Mobile Navigation">
        <div class="cp-mobile-drawer-header">
            <div class="cp-brand">
                <div class="cp-brand-icon"><i class="fa fa-microchip"></i></div>
                <div class="cp-brand-text">CYBER<span>PULSE</span></div>
            </div>
            <button type="button" class="cp-mobile-close" id="cpMobileClose" aria-label="Close menu">&times;</button>
        </div>
        <div class="cp-mobile-drawer-body">
            <div class="cp-search-wrapper" style="width:100%; position:relative;">
                <form action="search" method="GET" class="cp-mobile-search">
                    <input type="text" name="otsi" id="cpMobileSearchInput" placeholder="Search tech articles..." autocomplete="off" required value="<?php echo isset($_GET['otsi']) ? htmlspecialchars($_GET['otsi']) : ''; ?>">
                    <button type="submit" aria-label="Search"><i class="fa fa-search"></i></button>
                </form>
                <div class="cp-search-dropdown" id="cpMobileSearchDropdown"></div>
            </div>

            <ul class="cp-mobile-links">
                <li>
                    <a href="./all" class="<?php echo ($currentRoute === '' || $currentRoute === 'cyberpulse' || $currentRoute === 'index.php') ? 'active' : ''; ?>">
                        <i class="fa fa-home"></i> Home
                    </a>
                </li>
                <li>
                    <a href="all" class="<?php echo ($currentRoute === 'all') ? 'active' : ''; ?>">
                        <i class="fa fa-newspaper-o"></i> All News
                    </a>
                </li>
                <li class="cp-mobile-accordion" id="cpMobileAccordion">
                    <button type="button" class="cp-mobile-accordion-btn" id="cpMobileCatToggle">
                        <span><i class="fa fa-th-large"></i> Topics & Categories</span>
                        <i class="fa fa-chevron-down cp-accordion-arrow"></i>
                    </button>
                    <ul class="cp-mobile-sublinks">
                        <?php Controller::AllCategory(); ?>
                    </ul>
                </li>
                <li>
                    <a href="about" class="<?php echo ($currentRoute === 'about') ? 'active' : ''; ?>">
                        <i class="fa fa-info-circle"></i> About
                    </a>
                </li>
            </ul>

            <div class="cp-mobile-user-section">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="cp-mobile-user-info">
                        <i class="fa fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </div>
                    <div class="cp-mobile-user-actions">
                        <a href="profile" class="cp-btn cp-btn-outline"><i class="fa fa-id-badge"></i> Profile</a>
                        <a href="logout" class="cp-btn cp-btn-outline"><i class="fa fa-sign-out"></i> Log Out</a>
                    </div>
                <?php else: ?>
                    <div class="cp-mobile-auth-actions">
                        <a href="formLogin" class="cp-btn cp-btn-primary"><i class="fa fa-sign-in"></i> Log In</a>
                        <a href="registerForm" class="cp-btn cp-btn-outline"><i class="fa fa-user-plus"></i> Sign Up</a>
                    </div>
                <?php endif; ?>
                <a href="admin/" class="cp-btn cp-btn-admin" style="margin-top:12px; display:block; text-align:center;">
                    <i class="fa fa-lock"></i> Admin Panel
                </a>
            </div>
        </div>
    </aside>

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

    <!-- Flash / Toast Notification -->
    <?php if (isset($_SESSION['flash'])): 
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $flashType = $flash['type'] ?? 'info';
        $flashIcon = ($flashType === 'success') ? 'fa-check-circle' : (($flashType === 'error') ? 'fa-exclamation-circle' : 'fa-info-circle');
    ?>
        <div class="cp-toast cp-toast-<?php echo htmlspecialchars($flashType); ?>" id="cpFlashToast" role="alert">
            <i class="fa <?php echo $flashIcon; ?>"></i>
            <div style="flex:1;"><?php echo htmlspecialchars($flash['message']); ?></div>
            <button type="button" class="cp-toast-close" onclick="this.parentElement.remove();">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Floating Back to Top Button -->
    <button type="button" class="cp-back-to-top" id="cpBackToTop" aria-label="Back to Top" title="Back to Top">
        <i class="fa fa-chevron-up"></i>
    </button>

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
                    <li><a href="./all">Home</a></li>
                    <li><a href="all">News Stream</a></li>
                    <li><a href="about">About Us</a></li>
                    <li><a href="registerForm">Join Community</a></li>
                    <li><a href="formLogin">Login</a></li>
                    <li><a href="admin/">Dashboard</a></li>
                </ul>
            </div>
            <div style="font-family:var(--cp-font-mono); font-size:12px;">
                &copy; <?php echo date('Y'); ?> CyberPulse Media &bull; All Rights Reserved.
            </div>
        </div>
    </footer>

    <!-- Client Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggleBtn = document.getElementById('cpMobileToggle');
            var closeBtn = document.getElementById('cpMobileClose');
            var drawer = document.getElementById('cpMobileDrawer');
            var backdrop = document.getElementById('cpMobileBackdrop');
            var catToggle = document.getElementById('cpMobileCatToggle');
            var catAccordion = document.getElementById('cpMobileAccordion');
            var backToTop = document.getElementById('cpBackToTop');
            var toast = document.getElementById('cpFlashToast');

            function openDrawer() {
                if (drawer && backdrop) {
                    drawer.classList.add('cp-open');
                    backdrop.classList.add('cp-open');
                    document.body.style.overflow = 'hidden';
                }
            }

            function closeDrawer() {
                if (drawer && backdrop) {
                    drawer.classList.remove('cp-open');
                    backdrop.classList.remove('cp-open');
                    document.body.style.overflow = '';
                }
            }

            if (toggleBtn) toggleBtn.addEventListener('click', openDrawer);
            if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
            if (backdrop) backdrop.addEventListener('click', closeDrawer);

            if (catToggle && catAccordion) {
                catToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    catAccordion.classList.toggle('cp-expanded');
                });
            }

            // Reading Progress Bar & Back to top behavior
            var progressEl = document.getElementById('cpReadingProgress');
            window.addEventListener('scroll', function() {
                var winScroll = document.documentElement.scrollTop || document.body.scrollTop;
                var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                if (progressEl && height > 0) {
                    var scrolled = (winScroll / height) * 100;
                    progressEl.style.width = Math.min(100, Math.max(0, scrolled)) + '%';
                }

                if (backToTop) {
                    if (winScroll > 300) {
                        backToTop.classList.add('cp-visible');
                    } else {
                        backToTop.classList.remove('cp-visible');
                    }
                }
            });

            if (backToTop) {
                backToTop.addEventListener('click', function() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }

            // Auto dismiss toast after 4 seconds
            if (toast) {
                setTimeout(function() {
                    toast.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(15px)';
                    setTimeout(function() { toast.remove(); }, 400);
                }, 4000);
            }

            // Live Search Autocomplete Functionality
            function setupLiveSearch(inputId, dropdownId) {
                var input = document.getElementById(inputId);
                var dropdown = document.getElementById(dropdownId);
                if (!input || !dropdown) return;
                var searchTimer = null;

                input.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    var q = this.value.trim();
                    if (q.length < 2) {
                        dropdown.innerHTML = '';
                        dropdown.classList.remove('active');
                        return;
                    }
                    searchTimer = setTimeout(function() {
                        fetch('quicksearch?q=' + encodeURIComponent(q))
                            .then(function(res) { return res.json(); })
                            .then(function(data) {
                                if (data && data.results && data.results.length > 0) {
                                    var html = '<div class="cp-search-dropdown-list">';
                                    data.results.forEach(function(item) {
                                        html += '<a href="news?id=' + item.id + '" class="cp-search-dropdown-item">';
                                        html += '  <span class="cp-search-badge">' + item.category_name + '</span>';
                                        html += '  <div class="cp-search-item-title">' + item.title + '</div>';
                                        html += '  <div class="cp-search-item-meta">';
                                        html += '    <span><i class="fa fa-clock-o"></i> ' + item.reading_time + '</span>';
                                        html += '    <span><i class="fa fa-comments-o"></i> ' + item.comments_count + '</span>';
                                        html += '  </div>';
                                        html += '</a>';
                                    });
                                    html += '<a href="search?otsi=' + encodeURIComponent(q) + '" class="cp-search-dropdown-all">';
                                    html += '  <span>View all matching results</span> <i class="fa fa-arrow-right"></i>';
                                    html += '</a>';
                                    html += '</div>';
                                    dropdown.innerHTML = html;
                                    dropdown.classList.add('active');
                                } else {
                                    dropdown.innerHTML = '<div class="cp-search-no-results"><i class="fa fa-info-circle"></i> No publications found for &ldquo;' + q + '&rdquo;</div>';
                                    dropdown.classList.add('active');
                                }
                            })
                            .catch(function() {
                                dropdown.classList.remove('active');
                            });
                    }, 200);
                });

                document.addEventListener('click', function(e) {
                    if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.classList.remove('active');
                    }
                });
            }

            setupLiveSearch('cpSearchInput', 'cpSearchDropdown');
            setupLiveSearch('cpMobileSearchInput', 'cpMobileSearchDropdown');
        });
    </script>

    <!-- Confirmation Modal Dialog -->
    <?php include __DIR__ . '/modalConfirm.php'; ?>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Create Account // CYBERPULSE</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- CSS -->
    <link href="public/css/login.css" rel="stylesheet">
</head>
<body>

<div class="cp-auth-card">
    <div class="cp-auth-header">
        <div class="cp-auth-icon">
            <i class="fa fa-user-plus"></i>
        </div>
        <h2 class="cp-auth-title">Join CyberPulse</h2>
        <p class="cp-auth-subtitle">Create your profile to join community debates and share insights</p>
    </div>

    <form method="POST" action="registerAnswer">
        <div class="cp-form-group">
            <label for="name" class="cp-form-label"><i class="fa fa-user"></i> Full Name or Handle</label>
            <input id="name" type="text" class="cp-form-control" name="name" placeholder="e.g. QuantumDev" required autofocus>
        </div>

        <div class="cp-form-group">
            <label for="email" class="cp-form-label"><i class="fa fa-envelope-o"></i> E-Mail Address</label>
            <input id="email" type="email" class="cp-form-control" name="email" placeholder="dev@cyberpulse.tech" required>
        </div>

        <div class="cp-form-group">
            <label for="password" class="cp-form-label"><i class="fa fa-lock"></i> Password (min. 6 characters)</label>
            <input id="password" type="password" class="cp-form-control" name="password" placeholder="••••••••" required minlength="6">
        </div>

        <div class="cp-form-group">
            <label for="password-confirm" class="cp-form-label"><i class="fa fa-check-circle-o"></i> Confirm Password</label>
            <input id="password-confirm" type="password" class="cp-form-control" name="confirm" placeholder="••••••••" required minlength="6">
        </div>

        <button type="submit" class="cp-btn cp-btn-primary" style="width:100%; padding:13px; margin-top:10px;" name="save">
            <i class="fa fa-arrow-right"></i> Sign Up
        </button>
    </form>

    <div class="cp-auth-footer">
        <a href="./" style="display:inline-flex; align-items:center; gap:6px;">
            <i class="fa fa-chevron-left"></i> Return to Homepage
        </a>
    </div>
</div>

</body>
</html>
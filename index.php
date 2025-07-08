<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background: url('assets/images/field.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            background-color: rgba(255, 248, 220, 0.1);
            background-blend-mode: overlay;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                rgba(0, 0, 0, 0.2),
                rgba(0, 0, 0, 0.3)
            );
            z-index: 0;
        }

        .navbar {
            background: #2f2f2f;
            border-bottom: 1px solid #404040;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 0 20px;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 0.8rem 0;
            position: relative;
        }

        .navbar-left {
            position: absolute;
            left: -140px;
            font-family: 'Arial Black', 'Arial Bold', sans-serif;
        }

        .navbar-left a {
            font-size: 32px;
            font-weight: bold;
            color: #ffffff; /* White color for logo */
            text-decoration: none;
            letter-spacing: 0px;
            text-transform: capitalize;
            line-height: 1;  /* Added to ensure vertical centering */
        }

        .navbar-center {
            display: flex;
            justify-content: flex-start;
            gap: 1rem;
            margin-left: auto;
            margin-right: -140px;
        }

        .navbar-right {
            display: flex;
            justify-content: flex-end;
            gap: 2rem;
            align-items: center;
            margin-left: 80px;
            height: 100%;
            position: relative;
            right: -80px;
        }

        .nav-link {
            color: #e0e0e0;
            text-decoration: none;
            font-size: 16px;
            padding: 13px 10px;  /* Increased vertical padding to match new height */
            border-radius: 4px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: #ffffff;
            transition: all 0.3s ease;
        }

        .nav-link:hover:after {
            width: 100%;
            left: 0;
        }

        .nav-link:hover {
            color: #ffffff;
        }

        .nav-link.active {
            color: #ffffff;
            font-weight: bold;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            margin-right: 0;
        }

        .login-btn, .register-btn {
            padding: 8px 20px;
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.3s ease;
            background: #404040; /* Darker silver for buttons */
            color: white;
        }

        .login-btn:hover, .register-btn:hover {
            background: #505050; /* Slightly lighter on hover */
        }

        .user-menu {
            position: relative;
            height: 100%;
            display: flex;
            align-items: center;
            margin-right: -30px;
        }

        .user-menu > .nav-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            cursor: pointer;
            height: 100%;
            color: #e0e0e0;
            font-size: 14px;
            white-space: nowrap;
        }

        .user-menu-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: #2f2f2f;
            min-width: 250px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
            border-radius: 4px;
            padding: 10px 0;
            margin-top: 0;
            z-index: 1001;
        }

        .user-menu-content a {
            padding: 12px 20px;
            display: block;
            color: #e0e0e0;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .user-menu-content a:hover {
            background: #404040;
        }

        .user-menu:hover .user-menu-content {
            display: block;
        }

        .user-menu > .nav-link span {
            font-size: 12px;
            margin-left: 5px;
            transition: transform 0.3s;
        }

        .user-menu:hover > .nav-link span {
            transform: rotate(180deg);
        }

        .profile-preview {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-decoration: none;
        }

        .profile-preview:hover {
            background: #404040;
        }

        .profile-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #E0E0E0;
            overflow: hidden;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info {
            flex: 1;
        }

        .profile-info .username {
            color: #ffffff;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .profile-info .view-profile {
            color: #999;
            font-size: 11px;
        }

        /* Main content padding to account for fixed navbar */
        main {
            padding-top: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        /* Rest of your existing styles */
        .hero-section {
            text-align: center;
            padding: 0 20px;
            background: transparent;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
        }

        .hero-section h1 {
            font-size: 3.5em;
            color: #2e7d32; /* Dark green color */
            font-weight: bold;
            letter-spacing: 2px;
        }

        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            margin-top: 20px;
        }

        .hero-btn {
            padding: 12px 35px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1.1em;
            font-weight: bold;
            text-transform: uppercase;
            border: 2px solid #404040;
        }

        .login-hero-btn {
            background: #2f2f2f;
            color: white;
        }

        .register-hero-btn {
            background: #404040;
            color: white;
        }

        .hero-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            background: #505050;
            border-color: #505050;
        }

        .user-menu-link {
            display: block;
            padding: 12px 20px;
            color: #e0e0e0;
            text-decoration: none;
            transition: background 0.3s;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .user-menu-link:last-child {
            border-bottom: none;
        }

        .user-menu-link:hover {
            background: #404040;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="navbar-left">
                <a href="index.php">Agroplus</a>
            </div>
            
            <div class="navbar-center">
                <a href="index.php" class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/index.php' ? 'active' : ''); ?>">Home</a>
                <a href="contact.php" class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/contact.php' ? 'active' : ''); ?>">Contact us</a>
                <a href="about.php" class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/about.php' ? 'active' : ''); ?>">About us</a>
            </div>
            
            <div class="navbar-right">
                <?php if(isset($_SESSION["user_id"])): ?>
                    <div class="user-menu">
                        <div class="nav-link">
                            <div class="profile-avatar">
                                <img src="assets/images/default-avatar.png" alt="Profile">
                            </div>
                            <span><?php echo htmlspecialchars($_SESSION["username"]); ?></span>
                        </div>
                        <div class="user-menu-content">
                            <a href="profile.php" class="profile-preview">
                                <div class="profile-avatar">
                                    <img src="assets/images/default-avatar.png" alt="Profile">
                                </div>
                                <div class="profile-info">
                                    <div class="username"><?php echo htmlspecialchars($_SESSION["username"]); ?></div>
                                    <div class="view-profile">View Profile</div>
                                </div>
                            </a>
                            <?php if($_SESSION["user_type"] == "farmer"): ?>
                                <a href="upload_product.php">Upload Product</a>
                                <a href="view_products.php">View My Products</a>
                            <?php else: ?>
                                <a href="products.php">Products</a>
                                <a href="cart.php">Cart</a>
                            <?php endif; ?>
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main>
        <div class="hero-section">
            <h1>Welcome to AgroPlus</h1>
            <?php if(!isset($_SESSION["user_id"])): ?>
                <div class="hero-buttons">
                    <a href="login.php" class="hero-btn login-hero-btn">Login</a>
                    <a href="register.php" class="hero-btn register-hero-btn">Sign Up</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="assets/js/main.js"></script>
</body>
</html> 
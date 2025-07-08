<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set current page variable
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-items">
            <?php if(isset($_SESSION["user_id"]) && $_SESSION["user_type"] == "customer"): ?>
                <a href="index.php" class="<?php echo ($current_page == 'index') ? 'active' : ''; ?>">Home</a>
            <?php else: ?>
                <a href="index.php" class="<?php echo ($current_page == 'index') ? 'active' : ''; ?>">Home</a>
            <?php endif; ?>
            <?php if(isset($_SESSION["user_id"])): ?>
                <a href="profile.php" class="username">
                    <?php echo htmlspecialchars($_SESSION["username"]); ?>
                </a>
            <?php endif; ?>
            <?php if(isset($_SESSION["user_id"])): ?>
                <?php if($_SESSION["user_type"] == "admin"): ?>
                    <a href="admin_dashboard.php">Dashboard</a>
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <?php if($current_page != 'login'): // Check if not on login page ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
                <?php if($current_page != 'register'): // Check if not on register page ?>
                    <a href="register.php">Sign Up</a>
                <?php endif; ?>
                <a href="admin_login.php">Admin Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<style>
    .navbar {
        background: #4CAF50;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        padding: 0;
    }

    .nav-container {
        width: 700px;
        margin: 0 auto;
        padding: 0.8rem 0;
    }

    .nav-items {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
    }

    .nav-items a {
        color: #ffffff;
        text-decoration: none;
        font-size: 16px;
        padding: 8px 20px;
        transition: all 0.3s ease;
    }

    .nav-items a:hover {
        background: rgba(255,255,255,0.1);
        border-radius: 4px;
    }

    .username {
        color: #ffffff;
        font-size: 16px;
        padding: 8px 15px;
        background: rgba(255,255,255,0.1);
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .username:hover {
        background: rgba(255,255,255,0.2);
    }

    .active {
        background: rgba(255,255,255,0.1);
        border-radius: 4px;
    }
</style> 
<?php
if(session_status() === PHP_SESSION_NONE){ session_start(); }
?>
<style>
    .fb-navbar {
        background-color: #ffffff;
        height: 80px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 60px;
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 1001;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        font-family: 'Poppins', sans-serif;
    }
    .fb-logo-container { display: flex; align-items: center; text-decoration: none; gap: 12px; }
    .fb-logo-img { height: 45px; width: auto; object-fit: contain; }
    .fb-logo-text { font-size: 26px; font-weight: 800; }
    .text-green { color: #2d5a27; }
    .text-orange { color: #f39c12; }

    .fb-nav-links { display: flex; align-items: center; gap: 30px; }
    .fb-nav-links a { text-decoration: none; color: #333; font-size: 15px; font-weight: 600; transition: 0.3s; }
    .fb-nav-links a:hover { color: #f39c12; }

    /* Profile/Dashboard Button Style */
    .btn-dashboard {
        color: #2d5a27 !important;
        border: 2px solid #2d5a27;
        padding: 8px 18px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-dashboard:hover {
        background-color: #2d5a27;
        color: white !important;
    }

    .btn-register {
        background: linear-gradient(90deg, #f39c12 0%, #e67e22 100%);
        color: white !important;
        padding: 10px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
    }
    
    .btn-logout {
        color: #e74c3c !important;
        font-size: 14px;
    }
</style>

<nav class="fb-navbar">
    <a href="/food-wastage-system/index.php" class="fb-logo-container">
        <img src="/food-wastage-system/assets/images/logo.png" alt="Food Bridge Logo" class="fb-logo-img">
        <span class="fb-logo-text">
            <span class="text-green">Food</span><span class="text-orange">Bridge</span>
        </span>
    </a>

    <div class="fb-nav-links">
        <a href="/food-wastage-system/index.php">Home</a>
        <a href="/food-wastage-system/about.php">About</a>
        <a href="/food-wastage-system/feedback.php">Feedback</a>

        <?php if(!isset($_SESSION['user_id'])): ?>
            <a href="/food-wastage-system/login.php">Login</a>
            <a href="/food-wastage-system/register.php" class="btn-register">Register</a>
        <?php else: ?>
            <?php 
                $dashboard_url = "/food-wastage-system/";
                if($_SESSION['role'] == 'admin') {
                    $dashboard_url .= "admin/dashboard.php";
                } elseif($_SESSION['role'] == 'vendor') {
                    $dashboard_url .= "vendor/dashboard.php";
                } else {
                    $dashboard_url .= "ngo/dashboard.php";
                }
            ?>
            <a href="<?php echo $dashboard_url; ?>" class="btn-dashboard">
                <i class="fas fa-user-circle"></i> Dashboard
            </a>
            <a href="/food-wastage-system/logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        <?php endif; ?>
    </div>
</nav>
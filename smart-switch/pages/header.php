<?php
session_start(); // Start the session at the very beginning

// Centralized authentication check. Redirect to login if not authenticated.
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Smart Home'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard-style.css">
</head>
<body>

<nav>
    <div class="logo">
        <a href="dashboard.php">Smart House Lighting Control</a>
    </div>
    <ul>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle"><?php echo htmlspecialchars($_SESSION['username']); ?> &#9662;</a>
            <ul class="dropdown-menu">
                <li>
                    <a href="add-device.php">
                       <span>Add Device</span>
                    </a>
                </li>
                <li class="dropdown-divider"></li>
                <li>
                    <a href="change-password.php">
                        <span>Change Password</span>
                    </a>
                </li>
                <li class="dropdown-divider"></li>
                <li>
                    <a href="logout.php" class="logout-link">
                       <span>Logout</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
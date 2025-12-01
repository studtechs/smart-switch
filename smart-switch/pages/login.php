<?php
session_start();
require_once '../db/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['userid'] = $user['userid'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password');</script>";
        }
    } else {
        echo "<script>alert('User not found');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smart House Lighting - Login</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body {
    margin: 0;
    font-family: 'Orbitron', sans-serif;
    background: radial-gradient(circle at top left, #0f0c29, #302b63, #24243e);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
}

/* Form Container */
.auth-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}
.form-container {
    background: rgba(0,0,0,0.6);
    border: 2px solid #4c7dff;
    padding: 50px 40px;
    border-radius: 20px;
    width: 380px;
    text-align: center;
    box-shadow: 0 0 30px #4c7dff, 0 0 60px #325dff;
    animation: glow 2s infinite alternate;
}

@keyframes glow {
    0% { box-shadow: 0 0 20px #4c7dff, 0 0 40px #325dff; }
    100% { box-shadow: 0 0 40px #4c7dff, 0 0 80px #325dff; }
}

.brand {
    font-size: 26px;
    font-weight: 700;
    color: #00fff7;
    margin-bottom: 15px;
    text-shadow: 0 0 10px #00fff7, 0 0 20px #00fff7;
}
h2 {
    margin-bottom: 25px;
    letter-spacing: 2px;
    color: #a4c8ff;
}

/* Input Fields with Icons */
.form-group {
    position: relative;
    margin-bottom: 20px;
}
input {
    width: 85%;
    padding: 14px 16px 14px 45px;
    border-radius: 12px;
    border: none;
    outline: none;
    font-size: 16px;
    background: rgba(255,255,255,0.05);
    color: #00fff7;
    text-shadow: 0 0 5px #00fff7;
    box-shadow: inset 0 0 5px #00fff7;
    transition: all 0.3s ease;
}
input:focus {
    box-shadow: 0 0 10px #00fff7, 0 0 20px #4c7dff;
    background: rgba(255,255,255,0.1);
}

/* Icons inside inputs */
.form-group i {
    position: absolute;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
    color: #00fff7;
    font-size: 18px;
    pointer-events: none;
}

/* Button */
button {
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(90deg, #4c7dff, #325dff);
    color: #fff;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
    text-transform: uppercase;
}
button:hover {
    background: linear-gradient(90deg, #325dff, #4c7dff);
    box-shadow: 0 0 20px #4c7dff, 0 0 40px #325dff;
}
button::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: rgba(255,255,255,0.1);
    transform: rotate(45deg);
    transition: all 0.5s;
}
button:hover::after {
    top: 0;
    left: 0;
}

/* Footer */
.footer-text {
    margin-top: 20px;
    font-size: 14px;
    color: #9ab8ff;
}
a {
    color: #00fff7;
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
}

</style>
</head>
<body>
<main class="auth-wrapper">
    <div class="form-container">
        <div class="brand">Smart House Lighting</div>
        <h2>Login</h2>
        <form method="POST">
            <div class="form-group">
                <i class="fa fa-user"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <i class="fa fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <p class="footer-text">Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</main>
</body>
</html>

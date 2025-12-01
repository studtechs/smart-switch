<?php
$page_title = 'Change Password'; // Set a specific title for this page
require_once 'header.php'; // Include the new header and navigation
require_once '../db/config.php';

$message = '';
$message_type = ''; // 'success' or 'error'

// 2. Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $userid = $_SESSION['userid'];

    // 3. Validate that the new passwords match
    if ($new_password !== $confirm_password) {
        $message = "New passwords do not match.";
        $message_type = 'error';
    } else {
        // 4. Fetch the user's current hashed password from the database
        $stmt = $conn->prepare("SELECT password FROM users WHERE userid = ?");
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        // 5. Verify if the submitted 'current_password' matches the one in the database
        if ($user && password_verify($current_password, $user['password'])) {
            // 6. Hash the new password for secure storage
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

            // 7. Update the password in the database
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE userid = ?");
            $update_stmt->bind_param("si", $hashed_new_password, $userid);

            if ($update_stmt->execute()) {
                $message = "Password updated successfully!";
                $message_type = 'success';
            } else {
                $message = "Error updating password. Please try again.";
                $message_type = 'error';
            }
            $update_stmt->close();
        } else {
            $message = "Incorrect current password.";
            $message_type = 'error';
        }
    }
}
?>

<head>
    <style>
        /* General Body and Main Content Styling */
body {
    background-color: #12121e; /* Dark background similar to the image's overall theme */
    color: #ffffff; /* White text for contrast */
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    line-height: 1.6;
}

/* Style for the main container wrapping the form */
.form-container {
    max-width: 400px; /* Standard form width */
    margin: 50px auto; /* Center the form container on the page */
    padding: 30px;
    /* Dark blue/black background for the container (like the inner boxes in the image) */
    background-color: #1c1c2a;
    border-radius: 10px;
    /* Subtle blue border/shadow to match the theme */
    border: 1px solid #3b4260;
    box-shadow: 0 0 20px rgba(60, 100, 255, 0.2);
}

/* Heading Style */
.form-container h2 {
    color: #ffffff;
    text-align: center;
    margin-bottom: 25px;
    font-size: 1.8em;
    border-bottom: 2px solid #5a7dff; /* A bright blue underline */
    padding-bottom: 10px;
}

/* Form Group Spacing */
.form-group {
    margin-bottom: 20px;
}

/* Input Field Styling */
.form-group input[type="password"] {
    width: 100%;
    padding: 12px 15px;
    /* Dark input background */
    background-color: #2a2a44;
    border: 1px solid #3b4260;
    border-radius: 8px;
    color: #ffffff;
    font-size: 1em;
    box-sizing: border-box; /* Include padding and border in the element's total width and height */
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

/* Input Focus Effect */
.form-group input[type="password"]:focus {
    border-color: #5a7dff; /* Bright blue border on focus */
    outline: none;
    box-shadow: 0 0 10px rgba(90, 125, 255, 0.5);
}

/* Button Styling */
.form-container button[type="submit"] {
    width: 100%;
    padding: 15px;
    /* Bright blue primary color for the button */
    background-color: #5a7dff;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 1.1em;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.1s ease;
}

/* Button Hover Effect */
.form-container button[type="submit"]:hover {
    background-color: #3b66ff; /* Slightly darker blue on hover */
}

/* Button Active Effect (for a slight press effect) */
.form-container button[type="submit"]:active {
    transform: translateY(1px);
}

/* Message Display Styling (for success/error messages) */
.message {
    padding: 10px 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    font-weight: bold;
    text-align: center;
}

.message.success {
    background-color: #28a745; /* Green background for success */
    color: #ffffff;
    border: 1px solid #218838;
}

.message.error {
    background-color: #dc3545; /* Red background for error */
    color: #ffffff;
    border: 1px solid #c82333;
}
    </style>
</head>

<main class="content-wrapper">
    <div class="container form-container">
        <h2>Change Your Password</h2>
        <?php if (!empty($message)): ?>
            <p class="message <?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group"><input type="password" name="current_password" placeholder="Current Password" required></div>
            <div class="form-group"><input type="password" name="new_password" placeholder="New Password" required></div>
            <div class="form-group"><input type="password" name="confirm_password" placeholder="Confirm New Password" required></div>
            <button type="submit">Update Password</button>
        </form>
    </div>
</main>

<?php
require_once 'footer.php'; // Include the new footer
?>
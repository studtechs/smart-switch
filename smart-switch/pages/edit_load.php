<?php
$page_title = 'Edit Load';
require_once 'header.php';
include('../db/config.php');

// 1. Check if user is logged in
if (!isset($_SESSION['userid'])) {
    // Redirect to login if not logged in
    header("Location: login.php");
    exit();
}

$userid = $_SESSION['userid'];
$loadid = isset($_GET['id']) ? intval($_GET['id']) : 0;
$message = '';
$message_type = '';
$old_name = '';
$device_name = '';
$device_auth = '';

// 2. Security Check: Verify the load exists and belongs to the user, fetching old_name
$stmt = $conn->prepare("SELECT l.old_name, d.DeviceName, d.device_auth FROM loads l JOIN device d ON l.device_auth = d.device_auth WHERE l.loadid = ? AND d.userid = ?");
$stmt->bind_param("ii", $loadid, $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Load not found or doesn't belong to the user
    echo "<main class='content-wrapper'><div class='container form-container error-container'><p>Error: Load not found or you do not have permission to edit it.</p><a href='dashboard.php' class='button'>Back to Dashboard</a></div></main>";
    require_once 'footer.php';
    exit();
}

$load = $result->fetch_assoc();
$old_name = $load['old_name'];
$device_name = $load['DeviceName'];
$device_auth = $load['device_auth'];
$stmt->close();

// 3. Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_old_name = trim($_POST['old_name']);
    $new_device_name = trim($_POST['devicename']);

    if (empty($new_old_name)) {
        $message = 'Load name cannot be empty.';
        $message_type = 'error';
    } elseif (empty($new_device_name)) {
        $message = 'Device name cannot be empty.';
        $message_type = 'error';
    } else {
        // 4. Use a transaction for atomic updates (updating old_name now)
        $conn->begin_transaction();
        try {
            // Update old_name
            $update_load_stmt = $conn->prepare("UPDATE loads SET old_name = ? WHERE loadid = ?");
            $update_load_stmt->bind_param("si", $new_old_name, $loadid);
            $update_load_stmt->execute();
            $update_load_stmt->close();

            // Update device name
            $update_device_stmt = $conn->prepare("UPDATE device SET DeviceName = ? WHERE device_auth = ? AND userid = ?");
            $update_device_stmt->bind_param("ssi", $new_device_name, $device_auth, $userid);
            $update_device_stmt->execute();
            $update_device_stmt->close();

            $conn->commit();
            $message = 'Details updated successfully!';
            $message_type = 'success';
            $old_name = $new_old_name; // Update name for the form
            $device_name = $new_device_name; // Update name for the form
        } catch (mysqli_sql_exception $exception) {
            $conn->rollback();
            $message = 'An error occurred during the update. Please try again.';
            $message_type = 'error';
        }
    }
}

?>

<style>
    /* 🌌 Dark Theme Styles for Auth (Login/Register) Pages */

/* New wrapper for pages without navigation (login, register) */
.auth-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    /* Ensure it takes full viewport height */
    min-height: 100vh; 
    /* The body background (#0d122b) handles the deep space color */
}

/* Form Container Card - Used for Login/Register */
.container {
    /* Updated: Dark card style */
    background: #1a2240; 
    padding: 2.5rem 3rem; 
    border-radius: 12px;
    /* Blue glow effect */
    box-shadow: 0 0 15px rgba(75, 136, 255, 0.3), 0 5px 30px rgba(0, 0, 0, 0.5);
    text-align: center;
    max-width: 450px; 
    width: 100%;
    /* Subtle border matching the glow */
    border: 1px solid #4b88ff; 
}

.form-container {
    text-align: left;
}

h2 {
    /* Bright blue heading with text glow */
    color: #4b88ff; 
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-shadow: 0 0 5px rgba(75, 136, 255, 0.5);
}

.form-group {
    margin-bottom: 1.25rem;
}

.form-group input,
.form-group select {
    /* Dark input fields */
    width: 100%;
    padding: 1rem;
    border: 1px solid #3c4c8d;
    border-radius: 6px;
    background-color: #253360; 
    color: #f0f0f0; /* Light text color */
    box-sizing: border-box;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    font-family: 'Poppins', sans-serif;
}

/* Custom styling for select dropdown arrow */
.form-group select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    /* Updated arrow color for dark background */
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23f0f0f0' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 16px 12px;
    padding-right: 3rem; 
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    /* Blue focus glow */
    border-color: #4b88ff;
    box-shadow: 0 0 10px 0.2rem rgba(75, 136, 255, 0.4);
}

/* --- Button Styles (Login/Register CTA) --- */
button {
    display: inline-block;
    /* Blue/Purple Gradient for the futuristic look */
    background: linear-gradient(135deg, #4b88ff 0%, #764ba2 100%);
    color: #fff;
    padding: 1rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
    width: 100%;
    box-sizing: border-box;
    margin-top: 1.5rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

button:hover {
    /* Brighter glow/shift on hover */
    background: linear-gradient(135deg, #7d96ff 0%, #a287c9 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(75, 136, 255, 0.5);
}

p {
    /* Ensure paragraph text is light */
    color: #c9d6ff;
    margin-top: 1.5rem;
}

a {
    /* Blue link color */
    color: #4b88ff;
    text-decoration: none;
    transition: color 0.2s ease;
}

a:hover {
    text-decoration: none;
    color: #7d96ff;
}

/* --- Message Styles for Auth Feedback --- */
.message {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 4px;
    text-align: center;
    font-weight: 600;
    border: 1px solid transparent;
}

.message.success {
    /* Dark green background, light text */
    background-color: #2b5d38;
    color: #d4edda;
    border-color: #1e7e34;
}

.message.error {
    /* Dark red background, light text */
    background-color: #8b0000;
    color: #f8d7da;
    border-color: #c82333;
}
</style>

<main class="content-wrapper">
    <div class="container">
        <div class="form-container">
            <h2>Edit Load Name</h2>
            <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form action="edit_load.php?id=<?php echo $loadid; ?>" method="POST" novalidate>
                <div class="form-group">
                    <label for="devicename">Device Name</label>
                    <input type="text" id="devicename" name="devicename" value="<?php echo htmlspecialchars($device_name); ?>" required>
                </div>
                <div class="form-group">
                    <label for="old_name">Load Name</label>
                    <input type="text" id="old_name" name="old_name" value="<?php echo htmlspecialchars($old_name); ?>" required>
                </div>
                <button type="submit" class="button">Update Name</button>
            </form>
            <a href="dashboard.php" style="display: block; text-align: center; margin-top: 20px; color: #667eea;">Back to Dashboard</a>
        </div>
    </div>
</main>

<?php 
$conn->close();
require_once 'footer.php'; 
?>
<?php
$page_title = 'Add New Device'; // Set a specific title for this page
require_once 'header.php'; // Includes session_start() and authentication check
require_once '../db/config.php';

$message = '';
$message_type = ''; // 'success' or 'error'

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $device_name = trim($_POST['device_name']);
    $device_auth = trim($_POST['device_auth']); // Capture the new device auth key
    $num_channels = intval($_POST['num_channels']);
    $userid = $_SESSION['userid'];

    // 1. Validate input
    if (empty($device_name)) {
        $message = "Device name cannot be empty.";
        $message_type = 'error';
    } elseif (empty($device_auth)) {
        $message = "Device Auth Key cannot be empty.";
        $message_type = 'error';
    } elseif (!in_array($num_channels, [4, 8])) {
        $message = "Invalid number of channels selected. Please choose 4 or 8.";
        $message_type = 'error';
    } else {
        // 2. Check if the provided device_auth key already exists
        $stmt_check = $conn->prepare("SELECT device_auth FROM device WHERE device_auth = ?");
        $stmt_check->bind_param("s", $device_auth);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $stmt_check->close();

        if ($result_check->num_rows > 0) {
            $message = "Device Auth Key already exists. Please use a unique key.";
            $message_type = 'error';
        } else {
            // 3. Use a transaction to ensure atomicity
            $conn->begin_transaction();

            try {
                // 4. Insert the new device into the 'device' table
                $stmt_device = $conn->prepare("INSERT INTO device (DeviceName, device_auth, userid, device_ch) VALUES (?, ?, ?, ?)");
                $stmt_device->bind_param("ssii", $device_name, $device_auth, $userid, $num_channels);
                $stmt_device->execute();
                $stmt_device->close();

                // Initialize variables for binding before the loop
                $load_name = '';
                $old_name = '';
                // 5. Insert the corresponding loads into the 'loads' table
                $stmt_load = $conn->prepare("INSERT INTO loads (loadname, old_name, device_auth, load_state) VALUES (?, ?, ?, 0)");
                // Bind parameters once, outside the loop
                $stmt_load->bind_param("sss", $load_name, $old_name, $device_auth);
                for ($i = 1; $i <= $num_channels; $i++) {
                    $load_name = "Load$i";
                    $old_name = "Load$i"; // Set the old_name to the default name
                    // The variables $load_name and $device_auth are updated here, and execute() uses the new values
                    $stmt_load->execute();
                }
                $stmt_load->close();

                // 6. If all queries were successful, commit the transaction
                $conn->commit();

                $message = "Device '$device_name' with $num_channels loads added successfully! Redirecting to dashboard...";
                $message_type = 'success';
                // Redirect back to dashboard after a short delay
                header("refresh:3;url=dashboard.php");
            } catch (mysqli_sql_exception $exception) {
                // 7. If any query fails, roll back the transaction
                $conn->rollback();
                $message = "Error adding device: " . $exception->getMessage();
                $message_type = 'error';
            }
        }
    }
}
?>

<head>
    <style>
        /* --- Global & Container Styles --- */
        body {
            background-color: #12121e;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: #1c1c2a;
            border-radius: 10px;
            border: 1px solid #3b4260;
            box-shadow: 0 0 20px rgba(60, 100, 255, 0.2);
        }

        .form-container h2 {
            color: #ffffff;
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.8em;
            border-bottom: 2px solid #5a7dff;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #a0a0ff;
        }
        
        /* --- 1. Input Field Styling (Text Inputs ONLY - NO ARROW) --- */
        .form-group input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            background-color: #2a2a44;
            border: 1px solid #3b4260;
            border-radius: 8px;
            color: #ffffff;
            font-size: 1em;
            box-sizing: border-box; 
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        /* --- 2. Select Styling (Select ONLY - WITH Custom Arrow) --- */
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            background-color: #2a2a44;
            border: 1px solid #3b4260;
            border-radius: 8px;
            color: #ffffff;
            font-size: 1em;
            box-sizing: border-box; 
            
            /* Remove default OS dropdown arrow */
            -webkit-appearance: none; 
            -moz-appearance: none;    
            appearance: none;         

            /* Add custom white dropdown arrow */
            background-image: url('data:image/svg+xml;utf8,<svg fill="white" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px top 50%;
            padding-right: 30px; 
            cursor: pointer;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        /* Placeholder Color */
        .form-group input::placeholder {
            color: #777799;
            opacity: 1; 
        }

        /* Focus Effect (Applies to both input and select) */
        .form-group input:focus,
        .form-group select:focus {
            border-color: #5a7dff;
            outline: none;
            box-shadow: 0 0 10px rgba(90, 125, 255, 0.5);
        }

        /* --- Button and Link Styles --- */
        .form-container button[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #5a7dff;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.1s ease;
            margin-top: 10px;
        }

        .form-container button[type="submit"]:hover {
            background-color: #3b66ff;
        }

        .form-container button[type="submit"]:active {
            transform: translateY(1px);
        }

        .form-container a {
            color: #667eea !important; 
            transition: color 0.3s ease;
        }

        .form-container a:hover {
            color: #9ab4ff !important;
        }

        /* Message Display Styling */
        .message {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }

        .message.success {
            background-color: #28a745;
            color: #ffffff;
            border: 1px solid #218838;
        }

        .message.error {
            background-color: #dc3545;
            color: #ffffff;
            border: 1px solid #c82333;
        }
    </style>
</head>

<main class="content-wrapper">
    <div class="container form-container">
        <h2>Add a New Device</h2>
        
        <?php if (!empty($message)): ?>
            <p class="message <?php echo htmlspecialchars($message_type); ?>"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST" action="add-device.php">
            <div class="form-group">
                <label for="device_name">Device Name</label>
                <input type="text" id="device_name" name="device_name" placeholder="e.g., Living Room Controller" required>
            </div>
            
            <div class="form-group">
                <label for="device_auth">Device Auth Key</label>
                <input type="text" id="device_auth" name="device_auth" placeholder="Enter the unique key from your device" required>
            </div>

            <div class="form-group">
                <label for="num_channels">Number of Channels (Loads)</label>
                <select id="num_channels" name="num_channels" required>
                    <option value="" disabled selected>-- Select --</option>
                    <option value="4">4 Channels</option>
                    <option value="8">8 Channels</option>
                </select>
            </div>
            
            <button type="submit">Add Device</button>
        </form>
        <div style="text-align: center; margin-top: 20px;">
            <a href="dashboard.php" style="color: #667eea; text-decoration: none;">&larr; Back to Dashboard</a>
        </div>
    </div>
</main>

<?php
require_once 'footer.php'; // Include the footer
?>
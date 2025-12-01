<?php
session_start();
include('../db/config.php');

// Always return JSON
header('Content-Type: application/json');

// 1. Check if user is logged in
if (!isset($_SESSION['userid'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'error' => 'Authentication required.']);
    exit();
}

if (isset($_POST['loadid']) && isset($_POST['state'])) {
    $loadid = intval($_POST['loadid']);
    $state = intval($_POST['state']);
    $userid = $_SESSION['userid'];

    // 2. Securely update the load state by verifying ownership through the device table
    $query = "UPDATE loads l
              JOIN device d ON l.device_auth = d.device_auth
              SET l.load_state = ? 
              WHERE l.loadid = ? AND d.userid = ?";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $state, $loadid, $userid);

    if ($stmt->execute()) {
        // 3. Check if a row was actually updated (verifies permission and existence)
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Load state updated successfully.']);
        } else {
            http_response_code(403); // Forbidden
            echo json_encode(['success' => false, 'error' => 'Permission denied or load not found.']);
        }
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $conn->error]);
    }

    $stmt->close();
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'error' => 'Invalid request. Missing parameters.']);
}

$conn->close();
?>

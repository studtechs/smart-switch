<?php
header('Content-Type: application/json');
require_once '../db/config.php';

try {
    $query = "SELECT loadid, loadname, device_auth, load_state FROM loads ORDER BY loadid ASC";
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception('Database query failed: ' . $conn->error);
    }

    $loads = [];
    while ($row = $result->fetch_assoc()) {
        $loads[] = $row;
    }

    $conn->close();

    echo json_encode(['success' => true, 'data' => $loads]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
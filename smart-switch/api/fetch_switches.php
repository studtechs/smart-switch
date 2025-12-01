<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: login.html");
    exit();
}
include('../db/config.php');

$query = "SELECT * FROM device WHERE userid=".$_SESSION['userid'];; // adjust WHERE clause if needed
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
            
    }
?>

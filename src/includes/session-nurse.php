<?php
session_start();

if (!isset($_SESSION['nurse_id'])) {
    header("Location: src/nurse-login.php");
    exit();
}

// Database connection
require_once('connect.php');

$nurse_id = $_SESSION['nurse_id'];

// Fetch nurse's full name
$stmt = $conn->prepare("SELECT first_name, last_name FROM nurse WHERE nurse_id = ?");
$stmt->bind_param("i", $nurse_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $full_name = $first_name . ' ' . $last_name;

    // Store full name in session for later use
    $_SESSION['full_name'] = $full_name;
} else {
    // Handle case where nurse ID is not found (should not happen if session management is correct)
    header("Location: src/nurse-login.php");
    exit();
}

$stmt->close();

// Set timezone to Philippines (Asia/Manila)
date_default_timezone_set('Asia/Manila');
?>

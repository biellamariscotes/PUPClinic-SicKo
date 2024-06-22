<?php
session_start();
require_once('../connect.php');
error_log("I'M HERE EMAIL");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM patient WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    echo json_encode(['isDuplicate' => $count > 0]);
}
?>

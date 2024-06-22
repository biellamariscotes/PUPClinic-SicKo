<?php
session_start();
require_once('../connect.php');
error_log("I'M HERE");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = trim($_POST['student_id']);

    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM patient WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    echo json_encode(['isDuplicate' => $count > 0]);
}
?>

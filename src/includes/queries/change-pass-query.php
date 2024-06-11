<?php
session_start();
require_once('../connect.php');

if (isset($_POST['current_pass'], $_POST['new_pass'], $_POST['confirm_pass'], $_SESSION['patient_id'])) {
    $current_pass = $_POST['current_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];
    $patient_id = $_SESSION['patient_id'];

    if ($new_pass !== $confirm_pass) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match']);
        exit;
    }

    $stmt = $conn->prepare("SELECT password FROM patient WHERE patient_id = ?");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (password_verify($current_pass, $row['password'])) {
        $new_pass_hashed = password_hash($new_pass, PASSWORD_BCRYPT);
        $update_stmt = $conn->prepare("UPDATE patient SET password = ? WHERE patient_id = ?");
        $update_stmt->bind_param("ss", $new_pass_hashed, $patient_id);

        if ($update_stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update password']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>

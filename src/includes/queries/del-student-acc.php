<?php
session_start();
require_once('../connect.php');

if (isset($_POST['current_pass'], $_SESSION['patient_id'])) {
    $current_pass = $_POST['current_pass'];
    $patient_id = $_SESSION['patient_id'];

    // Fetch the current password hash from the database
    $stmt = $conn->prepare("SELECT password FROM patient WHERE patient_id = ?");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($current_pass, $row['password'])) {
        // Delete the account
        $delete_stmt = $conn->prepare("DELETE FROM patient WHERE patient_id = ?");
        $delete_stmt->bind_param("s", $patient_id);

        if ($delete_stmt->execute()) {
            // Logout the user by destroying the session
            session_destroy();
            echo json_encode(['status' => 'success', 'message' => 'Account deleted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete account.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>

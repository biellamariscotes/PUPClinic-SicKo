<?php
session_start();
require_once('includes/connect.php');

// Set timezone to Philippines (Asia/Manila)
date_default_timezone_set('Asia/Manila');

// Check if fullname and action are set
if (isset($_POST['fullname']) && isset($_POST['action'])) {
    $fullname = $_POST['fullname'];
    $action = $_POST['action'];
    $date = date('Y-m-d H:i:s'); // Current date and time

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO activity_log (fullname, date, action) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $date, $action);

    if ($stmt->execute()) {
        echo "Activity logged successfully.";
    } else {
        echo "Error logging activity.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>

<?php
session_start();

require_once('../connect.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $student_id = $_POST["student_id"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $sex = $_POST["sex"]; // Add this line to retrieve sex from form
    $birthday = $_POST["date"]; // Add this line to retrieve birthday from form
    $course = $_POST["course"]; // Add this line to retrieve course from form

    // Prepare and bind the INSERT statement
    $stmt = $conn->prepare("INSERT INTO patient (student_id, first_name, last_name, email, password, sex, birthday, course) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $student_id, $first_name, $last_name, $email, $password, $sex, $birthday, $course);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: ../../../register.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

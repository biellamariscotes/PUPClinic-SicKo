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
    $sex = $_POST["sex"]; 
    $birthday = $_POST["date"]; 
    $course = $_POST["course"]; 
    $section = $_POST["section"]; 

    // Prepare and bind the INSERT statement
    $stmt = $conn->prepare("INSERT INTO patient (student_id, first_name, last_name, email, password, sex, birthday, course, section) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)");
    $stmt->bind_param("sssssssss", $student_id, $first_name, $last_name, $email, $password, $sex, $birthday, $course, $section);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: ../../../login.php");
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

<?php

$stmt = $conn->prepare("SELECT * FROM patient WHERE patient_id = ?");
$stmt->bind_param("s", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$first_initial = strtoupper(substr($row['first_name'], 0, 1));
$last_initial = strtoupper(substr($row['last_name'], 0, 1));
$initials = $first_initial . $last_initial;

// Echo out data
$name = ucfirst(strtolower($row['first_name'])) . " " . ucfirst(strtolower(substr($row['middle_name'], 0, 1))) . ". " . ucfirst(strtolower($row['last_name']));
$full_name = ucfirst(strtolower($row['first_name'])) . " " . ucfirst(strtolower($row['middle_name'])) . " " . ucfirst(strtolower($row['last_name']));
$student_id = $row['student_id'];
$sex = $row['sex'];
$email = $row['email'];
$emergency_no = $row['emergency_no'];


$course_name = $row['course'];

switch ($course_name) {
    case 'BSA':
        $course = 'Accountancy';
        break;
    case 'BSIE':
        $course = 'Industrial Engineering';
        break;
    case 'BSP':
        $course = 'Psychology';
        break;
    case 'BSIT':
        $course = 'Information Technology';
        break;
    case 'BSED-ENG':
        $course = 'Education - English';
        break;
    case 'BSED-MATH':
        $course = 'Education - Mathematics';
        break;
    case 'BSBA-MM':
        $course = 'Marketing Management';
        break;
    case 'BSBA-HRM':
        $course = 'Human Resources Management';
        break;
    case 'BSEM':
        $course = 'EM';
        break;
    case 'BSMA':
        $course = 'BSMA';
        break;
    default:
        $course = 'Unknown Course'; // Default case if no match is found
        break;
}

$section = $row['section'];


$to_format_birthday = $row['birthday'];
$date = new DateTime($to_format_birthday);
$birthday = $date->format('F j, Y')

?>
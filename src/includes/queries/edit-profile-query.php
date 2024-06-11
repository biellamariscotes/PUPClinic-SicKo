<?php
$stmt = $conn->prepare("SELECT * FROM patient WHERE patient_id = ?");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $first_initial = strtoupper(substr($row['first_name'], 0, 1));
    $last_initial = strtoupper(substr($row['last_name'], 0, 1));
    $initials = $first_initial . $last_initial;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $first_name = ucfirst(strtolower($_POST['first_name']));
        $middle_name = ucfirst(strtolower($_POST['middle_name']));
        $last_name = ucfirst(strtolower($_POST['last_name']));
        $student_id = $_POST['student_id'];
        $email = $_POST['email'];
        $course = $_POST['course'];
        $section = $_POST['section'];
        $sex = $_POST['sex'];
        $emergency_no = $_POST['emergency_no'];
        $birthday = $_POST['date'];

        $update_stmt = $conn->prepare("UPDATE patient SET first_name = ?, middle_name = ?, last_name = ?, student_id = ?, email = ?, course = ?, section = ?, sex = ?, emergency_no = ? , birthday = ? WHERE patient_id = ?");
        $update_stmt->bind_param("ssssssssssi", $first_name, $middle_name, $last_name, $student_id, $email, $course, $section, $sex, $emergency_no, $birthday, $patient_id);
        $update_stmt->execute();

        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = new bootstrap.Modal(document.getElementById('saved-successfully'), {});
                    modal.show();
                    setTimeout(function() {
                        window.location.href = 'student-profile.php';
                    }, 2000);
                });
              </script>";
    }

    // Echo out data
    $name = ucfirst(strtolower($row['first_name'])) . " " . ucfirst(strtolower(substr($row['middle_name'], 0, 1))) . ". " . ucfirst(strtolower($row['last_name']));
    $first_name = ucfirst(strtolower($row['first_name']));
    $last_name = ucfirst(strtolower($row['last_name']));
    $middle_name = ucfirst(strtolower($row['middle_name']));
    $student_id = $row['student_id'];
    $sex = $row['sex'];
    $email = $row['email'];
    $emergency_no = $row['emergency_no'];
    $course_name = $row['course'];
    $section = $row['section'];
    $birthday = $row['birthday'];

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
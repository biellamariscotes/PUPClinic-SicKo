<?php
session_start();
require_once('../connect.php');

if (isset($_GET['record_id'])) {
    $record_id = $_GET['record_id'];

    $stmt = $conn->prepare("SELECT * FROM treatment_record WHERE record_id = ?");
    $stmt->bind_param("i", $record_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $formatted_date = date("F j, Y", strtotime($row['date']));
        $response = array(
            "date" => $formatted_date,
            "treatments" => ucfirst($row['treatments']),
            "symptoms" => ucfirst($row['symptoms']),
            "diagnosis" => ucfirst($row['diagnosis'])
        );
        echo json_encode($response);
    } else {
        echo json_encode(array("error" => "No record found."));
    }
} else {
    echo json_encode(array("error" => "Invalid request."));
}
?>

<?php
require_once ('includes/connect.php');

if(isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
    $sql = "SELECT student_id, CONCAT(first_name, ' ', last_name) AS full_name, gender, age, course, section FROM patient WHERE CONCAT(first_name, ' ', last_name) LIKE '%$keyword%' LIMIT 5";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo '<div onclick="selectPatient('.$row['student_id'].', \''.$row['full_name'].'\', \''.$row['gender'].'\', \''.$row['age'].'\', \''.$row['course'].'\', \''.$row['section'].'\')">'.$row['full_name'].'</div>';
        }
    } else {
        echo '<div>No results found</div>';
    }
}

mysqli_close($conn);
?>

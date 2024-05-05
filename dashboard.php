<?php
require_once('src/includes/session-nurse.php');
require_once('src/includes/connect.php');

$sql = "SELECT * 
        FROM treatment_record
        JOIN patient ON treatment_record.patient_id = patient.patient_id";
$result = mysqli_query($conn, $sql);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Dashboard</title>
    <link rel="icon" type="image/png" href="src/images/sicko-logo.png">
    <link rel="stylesheet" href="src/styles/dboardStyle.css">
</head>

<body>
    <div class="overlay" id="overlay"></div>

    <?php
    include ('src/includes/sidebar/dashboard.php');
    ?>

    <div class="content" id="content">
        <div class="dashboard-header-container">
            <img src="src/images/dashboard-header.png" alt="Dashboard Header" class="dashboard-header">
            <div class="dashboard-text">
                <p>Good day, <span class="bold">Nurse Sharwin!</span></p>
                <p class="bold" style="color: #E13F3D; font-size: 50px; font-family: 'Poppins', sans-serif;">Anong
                    SicKo?</p>
                <p style="color: black; font-size: 17px; font-family: 'Poppins', sans-serif; text-align: justify;">See
                    today’s health reports. Record daily treatments,<br> and generate diagnosis.</p>
            </div>
        </div>
        <div class="header-middle">Daily Treatment Record</div>
        <div class="table-container">
            <table class="dashboard-table">
                <tr>
                    <th>Patient Name</th>
                    <th>Course &amp; Year</th>
                    <th>Diagnosis</th>
                    <th>Time</th>
                </tr>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row["first_name"] . "</td>";
                        echo "<td>" . $row["course"] . "</td>";
                        echo "<td>" . $row["diagnosis"] . "</td>";
                        echo "<td>" . $row["acc_created"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No records found</td></tr>";
                }
                ?>
            </table>
        </div>

        <div class="header-middle">Clinic Health Tools</div>
        <div class="health-tools-container">
            <div class="health-tool health-tool-1" onclick="window.location.href='treatment-record.php'">
                <img src="src/images/health-checkup.svg" alt="Health Checkup Icon" class="health-icon">
                <div class="tool-text">
                    <p class="tool-title">Patient Form</p>
                    <p class="tool-subtitle">Treatment Record Form</p>
                </div>
            </div>
            <div class="health-tool health-tool-2" onclick="window.location.href='ai-basedSDT.php'">
                <img src="src/images/drawing.svg" alt="Health Checkup Icon" class="health-icon">
                <div class="tool-text">
                    <p class="tool-title">AI-based SDT</p>
                    <p class="tool-subtitle">Symptoms Diagnostic Tool</p>
                </div>
            </div>
            <div class="health-tool health-tool-3" onclick="window.location.href='med-reports.php'">
                <img src="src/images/health-calendar.svg" alt="Health Checkup Icon" class="health-icon">
                <div class="tool-text">
                    <p class="tool-title">Med Reports</p>
                    <p class="tool-subtitle">Medical Records Archive</p>
                </div>
            </div>
        </div>

    </div>
    <?php
    include ('src/includes/footer.php');
    ?>
    <script src="src/scripts/script.js"></script>
</body>

</html>
<?php
require_once ('includes/session-nurse.php');
require_once ('includes/connect.php');

$first_name = $row['first_name'];

// Set timezone to Philippine Time
date_default_timezone_set('Asia/Manila');

// Pagination variables
$rowsPerPage = 5;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($currentPage - 1) * $rowsPerPage;

// Get current date
$currentDate = date("Y-m-d");

// Query to fetch records for the current day
$sql = "SELECT patient.first_name, patient.last_name, patient.course, treatment_record.diagnosis, 
               treatment_record.date AS treatment_datetime
        FROM treatment_record
        JOIN patient ON treatment_record.patient_id = patient.patient_id
        WHERE DATE(treatment_record.date) = '$currentDate'
        LIMIT $offset, $rowsPerPage";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Error executing query: ' . mysqli_error($conn));
}

// Count total records for the current day
$totalRecordsResult = mysqli_query($conn, "SELECT COUNT(*) AS count FROM treatment_record WHERE DATE(date) = '$currentDate'");
if ($totalRecordsResult) {
    $totalRecordsRow = mysqli_fetch_assoc($totalRecordsResult);
    $totalRecords = $totalRecordsRow['count'];
    $totalPages = ceil($totalRecords / $rowsPerPage);
} else {
    die('Error executing count query: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" type="image/png" href="images/heart-logo.png">
    <link rel="stylesheet" href="styles/dboardStyle.css">
    <link rel="stylesheet" href="../vendors/bootstrap-5.0.2/dist/css/bootstrap.min.css">
</head>
<style>
    .pagination-button.disabled {
        pointer-events: none;
        opacity: 0.5;
    }

    .pagination-buttons {
        margin-right: 50px;
        margin-bottom: 20px;
    }

    .dashboard-table tbody tr {
        border-bottom: 1px solid #D3D3D3; 
    }

    .dashboard-table tbody tr:last-child {
        border-bottom: 1px solid #D3D3D3; 
    }

    .dashboard-table th {
        padding: 0px;
    }
</style>
<body>
    <div class="loader">
        <img src="images/loader.gif">
    </div>

    <div class="overlay" id="overlay"></div>

    <div class="main-content">
        <?php include ('includes/sidebar/dashboard.php'); ?>

        <div class="content" id="content">
            <div class="dashboard-header-container">
                <img src="images/dashboard-header.png" alt="Dashboard Header" class="dashboard-header">
                <div class="dashboard-text">
                    <p>Good day, <span class="bold">Nurse <?php echo htmlspecialchars($first_name); ?>!</span></p>
                    <p class="bold" style="color: #E13F3D; font-size: 50px; font-family: 'Poppins', sans-serif;">Anong SicKo?</p>
                    <p style="color: black; font-size: 17px; font-family: 'Poppins', sans-serif; text-align: justify;">See today’s health reports. Record daily treatments,<br> and generate diagnosis.</p>
                </div>
            </div>
            <div class="header-middle">Daily Treatment Record</div>
            <div class="table-container">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Course &amp; Year</th>
                            <th>Diagnosis</th>
                            <th>Date &amp; Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0) : ?>
                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                <?php 
                                    // Format date and time
                                    $treatmentDateTime = new DateTime($row["treatment_datetime"]);
                                    $formattedDateTime = $treatmentDateTime->format('F j, Y - h:i A');
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row["first_name"] . ' ' . $row["last_name"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["course"]); ?></td>
                                    <td><?php echo ucfirst(strtolower(htmlspecialchars($row["diagnosis"]))); ?></td>
                                    <td><?php echo htmlspecialchars($formattedDateTime); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4">No records found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">
                                <div class="sorting-pagination-container">
                                    <div class="pagination-buttons">
                                        <a href="?page=<?php echo max(1, $currentPage - 1); ?>" class="pagination-button <?php echo ($currentPage == 1) ? 'disabled' : ''; ?>">&lt;</a>
                                        <a href="?page=<?php echo min($totalPages, $currentPage + 1); ?>" class="pagination-button <?php echo ($currentPage == $totalPages) ? 'disabled' : ''; ?>">&gt;</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="header-middle">Clinic Health Tools</div>
        <div class="health-tools-container">
            <div class="health-tool health-tool-1" onclick="window.location.href='treatment-record.php'">
                <img src="images/health-checkup.svg" alt="Health Checkup Icon" class="health-icon">
                <div class="tool-text">
                    <p class="tool-title">Patient Form</p>
                    <p class="tool-subtitle">Treatment Record Form</p>
                </div>
            </div>
            <div class="health-tool health-tool-2" onclick="window.location.href='ai-basedSDT.php'">
                <img src="images/drawing.svg" alt="Health Checkup Icon" class="health-icon">
                <div class="tool-text">
                    <p class="tool-title">AI-based SDT</p>
                    <p class="tool-subtitle">Symptoms Diagnostic Tool</p>
                </div>
            </div>
            <div class="health-tool health-tool-3" onclick="window.location.href='reports.php'">
                <img src="images/health-calendar.svg" alt="Health Checkup Icon" class="health-icon">
                <div class="tool-text">
                    <p class="tool-title">Med Reports</p>
                    <p class="tool-subtitle">Medical Records Archive</p>
                </div>
            </div>
        </div>

    </div>
    <?php include ('includes/footer.php'); ?>
    </div>
    <script src="scripts/script.js"></script>
    <script src="scripts/loader.js"></script>
</body>
</html>

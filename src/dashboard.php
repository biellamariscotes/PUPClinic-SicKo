<?php
require_once('includes/session-nurse.php');
require_once('includes/connect.php');

$first_name = $row['first_name'];

// Set timezone to Philippine Time
date_default_timezone_set('Asia/Manila');

// Pagination variables
$recordsPerPage = 5;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $recordsPerPage;

$totalRecordsQuery = "SELECT COUNT(*) AS total FROM treatment_record WHERE DATE(date) = CURDATE()";
$totalRecordsResult = mysqli_query($conn, $totalRecordsQuery);
$totalRecordsRow = mysqli_fetch_assoc($totalRecordsResult);
$totalRecords = $totalRecordsRow['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Define the range of pages to display
$pageRange = 3;
$startPage = max(1, $currentPage - intval($pageRange / 2));
$endPage = min($totalPages, $startPage + $pageRange - 1);
$startPage = max(1, $endPage - $pageRange + 1);

// Get current date
$currentDate = date("Y-m-d");

// Query to fetch records for the current day
$sql = "SELECT patient.first_name, patient.last_name, patient.course, treatment_record.diagnosis, 
               treatment_record.date AS treatment_datetime
        FROM treatment_record
        JOIN patient ON treatment_record.patient_id = patient.patient_id
        WHERE DATE(treatment_record.date) = '$currentDate'
        ORDER BY treatment_record.date ASC
        LIMIT $offset, $recordsPerPage";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Error executing query: ' . mysqli_error($conn));
}

$totalRecordsResult = mysqli_query($conn, "SELECT COUNT(*) AS count FROM treatment_record WHERE DATE(date) = '$currentDate'");
if ($totalRecordsResult) {
    $totalRecordsRow = mysqli_fetch_assoc($totalRecordsResult);
    $totalRecords = $totalRecordsRow['count'];
    $totalPages = ceil($totalRecords / $recordsPerPage);
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
    .dashboard-table tbody tr {
        border-bottom: 1px solid #D3D3D3; 
    }
    .dashboard-table tbody tr:last-child {
        border-bottom: 1px solid #D3D3D3; 
    }
    .dashboard-table th {
        padding: 0px;
    }
    .empty-create-button {
        display: inline-block; 
        width: 13.063rem; 
        height: 2.625rem; 
        background-color: #058789; 
        border-radius: 0.375rem; 
        text-align: center; 
        line-height: 2.625rem; 
        vertical-align: middle; 
        cursor: pointer;
        transition: transform 0.3s ease, background-color 0.3s ease;
    }
    .empty-create-button:hover {
        transform: translateY(-5px);
        background-color: #2E8A8AF2;  
    }
    .empty-create-button img {
        transition: transform 0.2s ease;
    }
    .empty-create-button img:hover {
        transform: scale(1.3);
    }
    .hidden {
    display: none;
    }
    .page-item.active .page-link {
        z-index: 1;
    }
    .disabled-link {
        pointer-events: none;
        text-decoration: none;
        cursor: not-allowed;
    }
    .pagination-button.disabled {
        pointer-events: none;
        opacity: 0.5;
    }
    .pagination-buttons {
        margin-right: 50px;
    }
    .pagination .page-item .page-link {
        color: #020203;
        border: none;
        font-size: 0.25rem; 
        border-radius: 50%;
    }
    .pagination .page-item.active .page-link {
        background-color: #058789;
        border: none;
        color: #fff;
        border-radius: 50%;   
    }
    .pagination .page-item .page-link:hover {
        background-color: #058789;
        border: none;
        color: #fff;
        opacity: 0.8;
    }
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
    }
    .pagination .page-item .page-link {
        font-size: 0.875rem;
    }
</style>

<body>
    <div class="loader">
        <img src="images/loader.gif">
    </div>

    <div class="overlay" id="overlay"></div>

    <div class="main-content" style="overflow-x: hidden;">
        <?php include ('includes/sidebar/dashboard.php'); ?>

        <div class="content" id="content" >
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
            <table class="dashboard-table" id="dashboardTable"> <!-- Added id attribute -->
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Course &amp; Year</th>
                        <th>Diagnosis</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0) : ?>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <?php 
                                // Format date and time
                                $treatmentDateTime = new DateTime($row["treatment_datetime"]);
                                $formattedTime = $treatmentDateTime->format('h:i A'); // Format time only
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row["first_name"] . ' ' . $row["last_name"]); ?></td>
                                <td><?php echo htmlspecialchars($row["course"]); ?></td>
                                <td><?php echo ucfirst(strtolower(htmlspecialchars($row["diagnosis"]))); ?></td>
                                <td><?php echo htmlspecialchars($formattedTime); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" style="text-align: center; position: relative;">
                                <div>
                                    <img src="images/empty-state.png" alt="Empty State" style="width: 6.688rem; height: 6.688rem;">
                                </div>
                                <p style="margin-top: 0.938rem;">There are no treatment records today.</p>
                                
                                <!-- Adding the styled button with a class name -->
                                <div class="empty-create-button" onclick="window.location.href='treatment-record.php'">
                                    <img src="images/empty-add-button.svg" onclick="window.location.href='treatment-record.php'" alt="Add Button" style="vertical-align: middle; margin-right: 0.5rem;">
                                    <span style="font-family: 'Poppins', sans-serif; color: white; font-size: 12px; font-weight: 700; line-height: 18px;">Create Treatment Record</span>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot <?php echo (mysqli_num_rows($result) > 0) ? '' : 'class="hidden"'; ?>>
                    <tr>
                    <td colspan="4">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center" style="margin-bottom: 0; gap: 10px;">
                                <li class="page-item <?php echo ($currentPage == 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>">&lt;</a>
                                </li>
                                <?php for ($i = $startPage; $i <= $endPage; $i++) : ?>
                                    <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?php echo ($currentPage == $totalPages) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>">&gt;</a>
                                </li>
                            </ul>
                        </nav>
                    </td>
                    </tr>
                </tfoot>
            </table>
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var table = document.getElementById('dashboardTable');
        var sortingPaginationContainer = table.querySelector('.sorting-pagination-container');
        var tfoot = table.querySelector('tfoot');

        if (table && table.querySelector('tbody').rows.length === 0) {
            sortingPaginationContainer.style.display = 'none';
            if (tfoot) {
                tfoot.classList.add('hidden');
            }
        }
    });
    </script>

</body>
</html>

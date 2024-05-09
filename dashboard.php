<?php
require_once ('src/includes/session-nurse.php');
require_once ('src/includes/connect.php');

// Pagination variables
$rowsPerPage = 5;
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($currentPage - 1) * $rowsPerPage;

// Query to fetch limited rows
$sql = "SELECT * 
        FROM treatment_record
        JOIN patient ON treatment_record.patient_id = patient.patient_id
        LIMIT $offset, $rowsPerPage";
$result = mysqli_query($conn, $sql);

// Count total records
$totalRecords = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM treatment_record"));

// Calculate total pages
$totalPages = ceil($totalRecords / $rowsPerPage);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Dashboard</title>
    <link rel="icon" type="image/png" href="src/images/sicko-logo.png">
    <link rel="stylesheet" href="src/styles/dboardStyle.css">
    <link rel="stylesheet" href="vendors/bootstrap-5.0.2/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="loader">
        <img src="src/images/loader.gif">
    </div>

    <div class="overlay" id="overlay"></div>

    <div class="main-content">
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
                    <p style="color: black; font-size: 17px; font-family: 'Poppins', sans-serif; text-align: justify;">
                        See
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

                    <tr>
                        <td colspan="5"> <!-- Use colspan to span across all columns -->

                            <!-- Sorting and Pagination Container -->
                            <div class="sorting-pagination-container">
                                <!-- Sorting button box -->
                                <div class="sorting-button-box" id="sortingButtonBox">
                                    <!-- Sort text -->
                                    Sort by:
                                    <select id="sortCriteria"
                                        style="font-family: 'Poppins', sans-serif; font-weight: bold;">
                                        <option value="Accending">Ascending A-Z</option>
                                        <option value="Descending">Descending Z-A</option>
                                        <option value="Time">Time</option>
                                    </select>
                                </div>
                                <!-- Pagination buttons -->
                                <div class="pagination-buttons">
                                    <!-- Previous button -->
                                    <a href="?page=<?php echo max(1, $currentPage - 1); ?>"
                                        style="text-decoration: none;"
                                        class="pagination-button <?php echo ($currentPage == 1) ? 'disabled' : ''; ?>">
                                        &lt;
                                    </a>

                                    <!-- Next button -->
                                    <a href="?page=<?php echo min($totalPages, $currentPage + 1); ?>"
                                        style="text-decoration: none; margin-right: 1.25rem;"
                                        class="pagination-button  <?php echo ($currentPage == $totalPages) ? 'disabled' : ''; ?>">
                                        &gt;
                                    </a>
                                </div>
                            </div>
            </div>
            </td>
            </tr>
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
    </div>
    <script src="src/scripts/script.js"></script>
    <script src="src/scripts/loader.js"></script>
</body>

</html>
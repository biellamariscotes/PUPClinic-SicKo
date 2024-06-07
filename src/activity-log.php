<?php
require_once ('includes/session-nurse.php');
require_once ('includes/connect.php');

// Number of records to display per page
$recordsPerPage = 5;

// Current page number
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

// Offset calculation for SQL query
$offset = ($currentPage - 1) * $recordsPerPage;

// Initialize variables for filtering by academic year
$selectedAcademicYear = '';

// Check if an academic year is selected
if (isset($_GET['academic_year'])) {
    // Ensure it's a valid integer
    $selectedAcademicYear = intval($_GET['academic_year']);
}

// SQL query to fetch records with pagination
$query = "SELECT * FROM treatment_record";

// Add condition to filter by academic year if selected
if (!empty($selectedAcademicYear)) {
    $query .= " WHERE YEAR(date) = $selectedAcademicYear";
}

$query .= " LIMIT $offset, $recordsPerPage";

$result = mysqli_query($conn, $query);

// Total number of records
$totalRecords = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM treatment_record"));

// Total number of pages
$totalPages = ceil($totalRecords / $recordsPerPage);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Reports</title>
    <link rel="icon" type="image/png" href="images/heart-logo.png">
    <link rel="stylesheet" href="styles/dboardStyle.css">
    <link rel="stylesheet" href="styles/modals.css">
    <link rel="stylesheet" href="../vendors/bootstrap-5.0.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<div>

    <div class="loader">
        <img src="images/loader.gif">
    </div>

    <div class="overlay" id="overlay"></div>

    <div class="main-content">
        <?php
        include ('includes/sidebar/activity-log.php');
        ?>



        <div class="content" id="content">
            <div class="med-reports-header" style="margin-bottom: 1.5rem;">
                <div class="med-reports-header-box">
                    <div class="medreports-header-text">                                
                        <span style="color: #E13F3D; font-size: 2rem;">Activity</span>
                        <span style="color: #058789; font-size: 2rem;">Log</span></div>
                    <div class="medreports-sorting-button" id="medReportsortingButton">
                        <form method="GET">
                            <select name="academic_year" id="medReportsortCriteria"
                                style="font-family: 'Poppins', sans-serif; font-weight: bold;"
                                onchange="this.form.submit()">
                                <option value="" disabled selected hidden>Sort by</option>
                                <option value="latest-oldest">Latest to Oldest</option>
                                <option value="oldest-latest">Oldest to Latest</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <div class="activitylog-body">
                <div class="activitylog-box">
                    <div class="activitylog-date">May 26, 2024</div>
                        <div class="activitylog-box-wrapper"> 
                            <div class="activitylog-point"> <!-- Div for the scrollbar -->
                                <div class="activitylog-content"> <!-- Div for the body content -->
                                </div>
                            </div>
                        </div>
                </div>
            </div>


        </div>

        <?php
        include ('includes/footer.php');
        ?>
    </div>
</div>
<script src="../vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="scripts/script.js"></script>
<script src="scripts/loader.js"></script>
</body>

</html>
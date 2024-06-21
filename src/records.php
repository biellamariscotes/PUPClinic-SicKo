<?php
require_once ('includes/session-nurse.php');
require_once ('includes/connect.php');
require_once ('../vendors/tcpdf/tcpdf.php');

// Set timezone to Philippine Time
date_default_timezone_set('Asia/Manila');

// Check if the download button is clicked
if (isset($_GET['download'])) {
    // Retrieve all data from the treatment records table, sorted by name
    $query = "SELECT * FROM treatment_record ORDER BY date ASC";
    $result = mysqli_query($conn, $query);

    // Create new PDF document with landscape orientation and margins
    $pdf = new TCPDF('L', 'mm', 'A3', true, 'UTF-8', false);
    $pdf->SetCreator('SicKo');
    $pdf->SetTitle('Treatment Records');
    $pdf->SetHeaderData('', 0, 'SicKo - PUP Clinic', '');

    // Add a page with custom margins
    $pdf->SetMargins(15, 15, 15); // 15mm margin on all sides
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 12);

    // Add margin to the top of the header
    $pdf->SetY(15); // Adjust the value as per your requirement

    // Add header
    $pdf->Cell(0, 10, 'Treatment Records', 0, 1, 'C');

    // Add margin between header and table
    $pdf->Ln(10); // Add 10mm margin between header and table

    // Add a table
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(60, 10, 'Patient Name', 1, 0, 'C');
    $pdf->Cell(15, 10, 'Age', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Course', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Section', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Gender', 1, 0, 'C');
    $pdf->Cell(100, 10, 'Symptoms', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Diagnosis', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Treatments', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Date', 1, 1, 'C');
    $pdf->SetFont('helvetica', '', 10);

    // Populate the table with data from the database
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(60, 10, ucwords(strtolower($row['full_name'])), 1, 0, 'L');
        $pdf->Cell(15, 10, $row['age'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['course'], 1, 0, 'C');
        $pdf->Cell(20, 10, $row['section'], 1, 0, 'C');
        $pdf->Cell(20, 10, $row['sex'], 1, 0, 'C');
        $pdf->Cell(100, 10, ucfirst(strtolower($row['symptoms'])), 1, 0, 'L');
        $pdf->Cell(40, 10, ucfirst(strtolower($row['diagnosis'])), 1, 0, 'L');
        $pdf->Cell(50, 10, ucfirst(strtolower($row['treatments'])), 1, 0, 'L');
        $formattedDateTime = date('M d, Y - h:i A', strtotime($row["date"]));
        $pdf->Cell(40, 10, $formattedDateTime, 1, 1, 'C');
    }

    // Output the PDF as a download
    $pdf->Output('treatment_records.pdf', 'D');
    exit;
}

$selectedAcademicYear = isset($_GET['academic_year']) ? intval($_GET['academic_year']) : '';
$startYear = '';
$endYear = '';
$startDate = '';
$endDate = '';

if ($selectedAcademicYear) {
    $startYear = $selectedAcademicYear - 1;
    $endYear = $selectedAcademicYear;
    $startDate = "$startYear-09-01";
    $endDate = "$endYear-07-30";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmDeleteButton'])) {
    if (!empty($_POST['delete_record'])) {
        foreach ($_POST['delete_record'] as $record_id) {
            // Prepare a delete statement for treatment records
            $sql = "DELETE FROM treatment_record WHERE record_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $record_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

$recordsPerPage = 5;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($currentPage - 1) * $recordsPerPage;

// Determine sorting criteria
$sortingCriteria = isset($_GET['sort']) ? $_GET['sort'] : 'annually';
$orderBy = '';
switch ($sortingCriteria) {
    case 'annually':
        $orderBy = 'YEAR(date) DESC';
        break;
    case 'monthly':
        $orderBy = 'YEAR(date) DESC, MONTH(date) DESC';
        break;
    case 'weekly':
        // Assuming week number is extracted from the date (requires MySQL function or PHP logic)
        $orderBy = 'YEAR(date) DESC, WEEK(date) DESC';
        break;
    default:
        $orderBy = 'YEAR(date) DESC';
        break;
}

$academicYearParam = !empty($selectedAcademicYear) ? "&academic_year=$selectedAcademicYear" : '';
$sortParam = !empty($sortingCriteria) ? "&sort=$sortingCriteria" : '';

$whereClause = '';
if (!empty($selectedAcademicYear)) {
    $whereClause .= " WHERE YEAR(date) = $selectedAcademicYear";
}

$query = "SELECT * FROM treatment_record" . $whereClause . " ORDER BY $orderBy LIMIT $offset, $recordsPerPage";
$countQuery = "SELECT COUNT(*) AS total_records FROM treatment_record" . $whereClause;

$result = mysqli_query($conn, $query);
$totalRecordsResult = mysqli_query($conn, $countQuery);
$totalRecords = mysqli_fetch_assoc($totalRecordsResult)['total_records'];
$totalPages = ceil($totalRecords / $recordsPerPage);

$prevPage = max(1, $currentPage - 1);
$nextPage = min($totalPages, $currentPage + 1);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Records</title>
    <link rel="icon" type="image/png" href="images/heart-logo.png">
    <link rel="stylesheet" href="styles/dboardStyle.css">
    <link rel="stylesheet" href="styles/modals.css">
    <link rel="stylesheet" href="../vendors/bootstrap-5.0.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>

<style>
    .pagination-button.disabled {
        pointer-events: none;
        opacity: 0.5;
    }

    .pagination-buttons {
        margin-right: 50px;
    }

    .fixed-width-checkbox {
        width: 50px;
        text-align: center;
    }

    .fixed-width-checkbox input {
        display: none;
    }

    .delete-mode .fixed-width-checkbox input {
        display: inline-block;
    }

    #downloadButton {
        display: inline-block;
        margin-right: 10px;
    }

    .dashboard-table tbody tr {
        border-bottom: 1px solid #D3D3D3;
    }

    .dashboard-table tbody tr:last-child {
        border-bottom: none;
    }

    .dashboard-table th {
        padding: 0px;
    }

    select:focus {
        outline: none;
        transition: border 0.3s, box-shadow 0.3s;
        }

    select {
        -webkit-appearance: none;
        -moz-appearance: none; 
        appearance: none; 
        padding: 0.375rem 1.5rem 0.375rem 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: #fff url('data:image/svg+xml;charset=US-ASCII,<?xml version="1.0" ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"><path fill="none" stroke="#058789" stroke-width=".5" d="M2 0L0 2h4z"/></svg>') no-repeat right 0.5rem center; /* Custom arrow */
        background-size: 0.65em auto;
        font-family: 'Poppins', sans-serif;
        font-weight: bold;
    }

    #delete-selected-link.disabled {
        pointer-events: none;
        opacity: 0.5;
        cursor: not-allowed;
    }

    .disabled-view {
        pointer-events: none;
        color: #aaa;
    }
</style>

<body>
    <div class="loader">
        <img src="images/loader.gif">
    </div>

    <div class="overlay" id="overlay"></div>

    <div class="main-content">
        <?php include('includes/sidebar/records.php'); ?>

        <!-- Confirm Download Modal -->
        <div class="modal" id="downloadModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-middle-icon">
                            <i class="bi bi-check-circle-fill" style="color:#058789; font-size:5rem"></i>
                        </div>
                        <div class="modal-title" style="color: black;">Confirm Download</div>
                        <div class="modal-subtitle" style="justify-content: center;">Are you sure you want to download this file?</div>
                    </div>
                    <div class="modal-buttons">
                        <button type="button" class="btn btn-secondary" id="cancel-download" data-dismiss="modal" style="background-color: #777777; font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-right: 1.25rem;">Cancel</button>
                        <button type="button" class="btn btn-secondary" id="confirm-download" data-dismiss="modal" style="background-color: #058789; font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem;">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Download Successful Modal -->
        <div class="modal" id="download-successful-modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-middle-icon">
                            <img src="images/check.gif" style="width: 10rem; height: auto;" alt="Check Icon">
                        </div>
                        <div class="modal-title" style="color: black;">Download Successful</div>
                        <div class="modal-subtitle" style="text-wrap: pretty; justify-content: center;">Your file has been successfully downloaded.</div>
                    </div>
                    <div class="modal-buttons">
                        <button type="button" class="btn btn-secondary" id="download-close-modal" data-dismiss="modal"
                            style="background-color: #23B26D; font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-top: 1rem;">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Confirm Delete Modal -->
        <div class="modal" id="confirmDeleteModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-middle-icon">
                            <i class="bi bi-trash-fill" style="color:#E13F3D; font-size:5rem"></i>
                        </div>
                        <div class="modal-title" style="color: black;">Confirm Delete?</div>
                        <div class="modal-subtitle" style="justify-content: center; padding-bottom: 1rem;">Are you sure you want to delete the selected record(s)? This action cannot be undone.</div>
                    </div>
                    <div class="modal-buttons" style="padding-top: 1rem;">
                        <button type="button" class="btn btn-secondary" id="cancel-delete" data-bs-dismiss="modal" style="background-color: #777777; 
                        font-family: 'Poppins'; font-weight: 600; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-right: 1.25rem;">Cancel</button>
                        <button type="button" class="btn btn-secondary" id="confirm-delete" style="background-color: #E13F3D; 
                        font-family: 'Poppins'; font-weight: 600; padding: 0.070rem 1.25rem 0.070rem 1.25rem;">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="content" id="content">
            <div class="med-reports-header">
                <div class="med-reports-header-box">
                    <div class="medreports-header-text">Quarterly Reports</div>
                    <div class="medreports-sorting-button" id="medReportsortingButton">
                        <form method="GET">
                            <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sortingCriteria); ?>">
                            <select name="academic_year" id="medReportsortCriteria"
                                style="font-family: 'Poppins', sans-serif; font-weight: bold;"
                                onchange="this.form.submit()">
                                <option value="" selected>Academic Year</option>
                                <option value="2025" <?php echo $selectedAcademicYear == '2025' ? 'selected' : ''; ?>>2024-2025</option>
                                <option value="2024" <?php echo $selectedAcademicYear == '2024' ? 'selected' : ''; ?>>2023-2024</option>
                                <option value="2023" <?php echo $selectedAcademicYear == '2023' ? 'selected' : ''; ?>>2022-2023</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <div class="header-middle" style="margin: 0 20px 0 20px;">Treatment Record</div>
            <!-- Table Container -->
            <div class="table-container">
                <form method="POST">
                    <table class="dashboard-table" style="margin-bottom: 80px;">
                        <tr>
                            <th>Records</th>
                            <th>Patient Name</th>
                            <th>Course & Year</th>
                            <th>Diagnosis</th>
                            <th>Time</th>
                        </tr>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td><a href='tr-clearance.php?record_id=" . urlencode($row["record_id"]) . "' class='view-link'>View</a></td>";
                                echo "<td class='fixed-width-checkbox'>";
                                echo "<input type='checkbox' name='delete_record[]' id='delete_record_" . $row["record_id"] . "' value='" . $row["record_id"] . "' onchange='toggleDeleteButton()'>";
                                echo $row["full_name"] . "</td>";
                                echo "<td>" . $row["course"] . " " . $row["section"] . "</td>";
                                echo "<td>" . $row["diagnosis"] . "</td>";
                                echo "<td>" . date('M d, Y - h:i A', strtotime($row["date"])) . "</td>";
                                echo "</tr>";
                            }                                                  
                        } else {
                            echo "<tr><td colspan='5'>No records found</td></tr>";
                        }
                        ?>
                        <tr>
                            <td colspan="5" style="background-color: white;">
                                <div class="table-button-container">
                                    <div class="button-group">
                                    <span class="delete-records-link" id="delete-toggle-link" onclick="toggleDeleteMode()" style="color: #D22B2B;">
                                        <i class="bi bi-trash" style="color: #D22B2B; font-size: 1rem; margin-right: 0.625rem; vertical-align: middle;"></i>
                                        Delete Records
                                    </span>
                                    <span class="delete-records-link" id="delete-selected-link" style="display: none; color: #D22B2B; margin-right: 10px;" onclick="$('#confirmDeleteModal').modal('show');">Delete Selected</span>
                                    <div class="button-separator"></div>
                                    <span class="delete-records-link" id="cancel-delete-link" style="display: none; margin-left: 10px;" onclick="cancelDeleteMode()">Cancel</span>
                                    <div class="download-button" id="downloadButton">
                                        <a style="color: #058789; text-decoration: none;" href="?download=1">
                                            <i class="bi bi-download" style="color: #058789; font-size: 1rem; margin-right: 0.625rem; vertical-align: middle;"></i>
                                            <span style="transition: color 0.3s;">Download</span>
                                        </a>
                                    </div>
                                </div>
                                    <!-- Sorting and Pagination Container -->
                                <div class="sorting-pagination-container">
                                    <!-- Sorting button box -->
                                    <div class="sorting-button-box" id="sortingButtonBox">
                                        <!-- Sort text -->
                                        Sort by:
                                        <select id="sortCriteria" style="font-weight: bold;" onchange="changeSortCriteria(this.value)">
                                            <option value="annually" <?php echo ($sortingCriteria == 'annually') ? 'selected' : ''; ?>>Annually</option>
                                            <option value="monthly" <?php echo ($sortingCriteria == 'monthly') ? 'selected' : ''; ?>>Monthly</option>
                                            <option value="weekly" <?php echo ($sortingCriteria == 'weekly') ? 'selected' : ''; ?>>Weekly</option>
                                        </select>
                                    </div>
                                    <!-- Pagination buttons -->
                                    <div class="pagination-buttons">
                                        <!-- Previous button -->
                                        <a href="?page=<?php echo $prevPage . $academicYearParam . $sortParam; ?>"
                                            class="pagination-button <?php echo ($currentPage == 1) ? 'disabled' : ''; ?>">
                                            &lt;
                                        </a>
                                        <!-- Next button -->
                                        <a href="?page=<?php echo $nextPage . $academicYearParam . $sortParam; ?>"
                                            class="pagination-button <?php echo ($currentPage == $totalPages) ? 'disabled' : ''; ?>">
                                            &gt;
                                        </a>
                                    </div>
                                </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <button type="submit" id="confirmDeleteButton" name="confirmDeleteButton" style="display:none;">Confirm Delete</button>
                </form>
            </div>
        </div>
    </div>


            <div class="header-middle" style="margin: 0 20px 0 20px;">Quarterly Report</div>

            <!-- First Quarter -->
            <div class="quarterly-report-row" id="firstQuarter">
                <div class="quarterly-report-content">
                    <div class="quarterly-report-row-box">
                        <?php
                        // Fetch and display data for First Quarter
                        $query = "SELECT diagnosis, COUNT(*) AS diagnosis_count 
                                  FROM treatment_record 
                                  WHERE MONTH(date) IN (1, 2, 3)";

                        // Add condition to filter by academic year if selected
                        if (!empty($selectedAcademicYear)) {
                            $query .= " AND date BETWEEN '$startDate' AND '$endDate'";
                        }

                        $query .= " GROUP BY diagnosis 
                                    ORDER BY diagnosis_count DESC 
                                    LIMIT 1";

                        $result = mysqli_query($conn, $query);
                        if ($row = mysqli_fetch_assoc($result)) {
                            $leading_diagnosis = $row['diagnosis'];
                            $diagnosis_count = $row['diagnosis_count'];
                        } else {
                            $leading_diagnosis = "No data";
                            $diagnosis_count = 0;
                        }
                        ?>
                        <div class="row-first-content">
                            <div class="extend-down-icon" onclick="toggleQuarter('firstQuarter')">
                                <img src="images/extend-down.svg" alt="Extend Down Icon" class="extend-down-icon">
                            </div>
                            <div class="quarterly-report-title">
                                <div class="quarter-number" id="">First Quarter</div>
                                <div class="month-name">JANUARY - MARCH</div>
                            </div>
                        </div>
                        <div class="total-diagnosis-box">
                            <div class="total-diagnosis-box-text">
                                <div class="total-number" style="font-size: 35px;">
                                    <?php 
                                    $total_sec_query = "SELECT COUNT(*) AS diagnosis_count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) IN (1, 2, 3)";

                                    if (!empty($selectedAcademicYear)) {
                                        $total_sec_query .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                    }

                                    $result = mysqli_query($conn, $total_sec_query);
                                    $row = mysqli_fetch_assoc($result);
                                    echo $row['diagnosis_count'];
                                    ?>
                                </div>
                                <div class="total-sub-text" style="font-size: 10px;">Diagnoses</div>
                            </div>
                        </div>
                    </div>

                    <div class="quarterly-report-alter collapsed">
                        <div class="alter-report-content">
                            <div class="alter-first-row">
                                <div class="alter-report-header">
                                    <div class="alter-header-content">
                                        <div class="extended-down-icon" onclick="toggleQuarter('firstQuarter')">
                                            <img src="images/extended-down.svg" alt="Extended Down Icon"
                                                class="extended-down-icon">
                                        </div>
                                        <div class="alter-header-title">
                                            <div class="alter-title" id="">First Quarter</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alter-second-row">
                                <div class="leading-diagnosis-box">
                                    <div class="leading-diagnosis-box-text">
                                        <div class="leading-diagnosis-text" style="font-size: 35px;"><?php echo $leading_diagnosis; ?></div>
                                        <div class="leading-diagnosis-subtext" style="font-size: 10px;">MOST COMMON
                                            MEDICAL CONDITION FOR THE QUARTER</div>
                                    </div>
                                </div>

                                <div class="total-diagnosis-box" style="background-color: #E13F3D;">
                                    <div class="total-diagnosis-box-text" style="color: white;">
                                        <div class="total-number" style="font-size: 35px;">
                                            <?php echo $diagnosis_count; ?></div>
                                        <div class="total-sub-text" style="font-size: 10px;">
                                            <?php echo $leading_diagnosis; ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="alter-third-row">
                                <div class="alter-third-row-label">
                                    <div class="alter-patient-diagnosed">Patient Diagnosed</div>
                                    <div class="alter-leading-diagnosis">Leading Diagnosis</div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">January</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_jan = "SELECT diagnosis, COUNT(*) AS count 
                                                      FROM treatment_record 
                                                      WHERE MONTH(date) = 1";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_jan .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_jan .= " GROUP BY diagnosis 
                                                        ORDER BY count DESC 
                                                        LIMIT 1";

                                        $result_jan = mysqli_query($conn, $query_jan);
                                        if ($row_jan = mysqli_fetch_assoc($result_jan)) {
                                            echo $row_jan['count'];
                                            $leading_diagnosis_jan = $row_jan['diagnosis'];
                                        } else {
                                            echo "No data";
                                            $leading_diagnosis_jan = "No data";
                                        }
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;"><?php echo $leading_diagnosis_jan; ?></div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">February</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_feb = "SELECT diagnosis, COUNT(*) AS count 
                                                      FROM treatment_record 
                                                      WHERE MONTH(date) = 2";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_feb .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_feb .= " GROUP BY diagnosis 
                                                        ORDER BY count DESC 
                                                        LIMIT 1";

                                        $result_feb = mysqli_query($conn, $query_feb);
                                        if ($row_feb = mysqli_fetch_assoc($result_feb)) {
                                            echo $row_feb['count'];
                                            $leading_diagnosis_feb = $row_feb['diagnosis'];
                                        } else {
                                            echo "No data";
                                            $leading_diagnosis_feb = "No data";
                                        }
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;"><?php echo $leading_diagnosis_feb; ?></div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">March</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_mar = "SELECT diagnosis, COUNT(*) AS count 
                                                      FROM treatment_record 
                                                      WHERE MONTH(date) = 3";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_mar .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_mar .= " GROUP BY diagnosis 
                                                        ORDER BY count DESC 
                                                        LIMIT 1";

                                        $result_mar = mysqli_query($conn, $query_mar);
                                        if ($row_mar = mysqli_fetch_assoc($result_mar)) {
                                            echo $row_mar['count'];
                                            $leading_diagnosis_mar = $row_mar['diagnosis'];
                                        } else {
                                            echo "No data";
                                            $leading_diagnosis_mar = "No data";
                                        }
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;"><?php echo $leading_diagnosis_mar; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Quarter -->
            <div class="quarterly-report-row" id="secondQuarter">
                <div class="quarterly-report-content">
                    <div class="quarterly-report-row-box">
                        <?php
                        // Fetch and display data for First Quarter
                        $query = "SELECT diagnosis, COUNT(*) AS diagnosis_count 
                                  FROM treatment_record 
                                  WHERE MONTH(date) IN (4, 5, 6)";

                        // Add condition to filter by academic year if selected
                        if (!empty($selectedAcademicYear)) {
                            $query .= " AND date BETWEEN '$startDate' AND '$endDate'";
                        }

                        $query .= " GROUP BY diagnosis 
                                    ORDER BY diagnosis_count DESC 
                                    LIMIT 1";

                        $result = mysqli_query($conn, $query);
                        if ($row = mysqli_fetch_assoc($result)) {
                            $leading_diagnosis = $row['diagnosis'];
                            $diagnosis_count = $row['diagnosis_count'];
                        } else {
                            $leading_diagnosis = "No data";
                            $diagnosis_count = 0;
                        }
                        ?>
                        <div class="row-first-content">
                            <div class="extend-down-icon" onclick="toggleQuarter('secondQuarter')">
                                <img src="images/extend-down.svg" alt="Extend Down Icon" class="extend-down-icon">
                            </div>
                            <div class="quarterly-report-title">
                                <div class="quarter-number" id="">Second Quarter</div>
                                <div class="month-name">APRIL - JUNE</div>
                            </div>
                        </div>
                        <div class="total-diagnosis-box">
                            <div class="total-diagnosis-box-text">
                                <div class="total-number" style="font-size: 35px;">
                                    <?php 
                                    $total_sec_query = "SELECT COUNT(*) AS diagnosis_count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) IN (4, 5, 6)";

                                    if (!empty($selectedAcademicYear)) {
                                        $total_sec_query .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                    }

                                    $result = mysqli_query($conn, $total_sec_query);
                                    $row = mysqli_fetch_assoc($result);
                                    echo $row['diagnosis_count'];
                                    ?>
                                </div>
                                <div class="total-sub-text" style="font-size: 10px;">Diagnoses</div>
                            </div>
                        </div>
                    </div>

                    <div class="quarterly-report-alter collapsed">
                        <div class="alter-report-content">
                            <div class="alter-first-row">
                                <div class="alter-report-header">
                                    <div class="alter-header-content">
                                        <div class="extended-down-icon" onclick="toggleQuarter('secondQuarter')">
                                            <img src="images/extended-down.svg" alt="Extended Down Icon"
                                                class="extended-down-icon">
                                        </div>
                                        <div class="alter-header-title">
                                            <div class="alter-title" id="">Second Quarter</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alter-second-row">
                                <div class="leading-diagnosis-box">
                                    <div class="leading-diagnosis-box-text">
                                        <div class="leading-diagnosis-text" style="font-size: 35px;"><?php echo $leading_diagnosis; ?></div>
                                        <div class="leading-diagnosis-subtext" style="font-size: 10px;">MOST COMMON
                                            MEDICAL CONDITION FOR THE QUARTER</div>
                                    </div>
                                </div>

                                <div class="total-diagnosis-box" style="background-color: #E13F3D;">
                                    <div class="total-diagnosis-box-text" style="color: white;">
                                        <div class="total-number" style="font-size: 35px;">
                                            <?php echo $diagnosis_count; ?></div>
                                        <div class="total-sub-text" style="font-size: 10px;">
                                            <?php echo $leading_diagnosis; ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="alter-third-row">
                                <div class="alter-third-row-label">
                                    <div class="alter-patient-diagnosed">Patient Diagnosed</div>
                                    <div class="alter-leading-diagnosis">Leading Diagnosis</div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">April</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_apr = "SELECT diagnosis, COUNT(*) AS count 
                                                      FROM treatment_record 
                                                      WHERE MONTH(date) = 4";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_apr .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_apr .= " GROUP BY diagnosis 
                                                        ORDER BY count DESC 
                                                        LIMIT 1";

                                        $result_apr = mysqli_query($conn, $query_apr);
                                        if ($row_apr = mysqli_fetch_assoc($result_apr)) {
                                            echo $row_apr['count'];
                                            $leading_diagnosis_apr = $row_apr['diagnosis'];
                                        } else {
                                            echo "No data";
                                            $leading_diagnosis_apr = "No data";
                                        }
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;"><?php echo $leading_diagnosis_apr; ?></div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">May</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_may = "SELECT diagnosis, COUNT(*) AS count 
                                                      FROM treatment_record 
                                                      WHERE MONTH(date) = 5";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_may .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_may .= " GROUP BY diagnosis 
                                                        ORDER BY count DESC 
                                                        LIMIT 1";

                                        $result_may = mysqli_query($conn, $query_may);
                                        if ($row_may = mysqli_fetch_assoc($result_may)) {
                                            echo $row_may['count'];
                                            $leading_diagnosis_may = $row_may['diagnosis'];
                                        } else {
                                            echo "No data";
                                            $leading_diagnosis_may = "No data";
                                        }
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;"><?php echo $leading_diagnosis_may; ?></div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">June</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_jun = "SELECT diagnosis, COUNT(*) AS count 
                                                      FROM treatment_record 
                                                      WHERE MONTH(date) = 6";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_jun .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_jun .= " GROUP BY diagnosis 
                                                        ORDER BY count DESC 
                                                        LIMIT 1";

                                        $result_jun = mysqli_query($conn, $query_jun);
                                        if ($row_jun = mysqli_fetch_assoc($result_jun)) {
                                            echo $row_jun['count'];
                                            $leading_diagnosis_jun = $row_jun['diagnosis'];
                                        } else {
                                            echo "No data";
                                            $leading_diagnosis_jun = "No data";
                                        }
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;"><?php echo $leading_diagnosis_jun; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Third Quarter -->
            <div class="quarterly-report-row" id="thirdQuarter">
                <div class="quarterly-report-content">
                    <div class="quarterly-report-row-box">
                        <?php
                        // Fetch and display data for First Quarter
                        $query = "SELECT diagnosis, COUNT(*) AS diagnosis_count 
                                  FROM treatment_record 
                                  WHERE MONTH(date) IN (7, 8, 9)";

                        // Add condition to filter by academic year if selected
                        if (!empty($selectedAcademicYear)) {
                            $query .= " AND date BETWEEN '$startDate' AND '$endDate'";
                        }

                        $query .= " GROUP BY diagnosis 
                                    ORDER BY diagnosis_count DESC 
                                    LIMIT 1";

                        $result = mysqli_query($conn, $query);
                        if ($row = mysqli_fetch_assoc($result)) {
                            $leading_diagnosis = $row['diagnosis'];
                            $diagnosis_count = $row['diagnosis_count'];
                        } else {
                            $leading_diagnosis = "No data";
                            $diagnosis_count = 0;
                        }
                        ?>
                        <div class="row-first-content">
                            <div class="extend-down-icon" onclick="toggleQuarter('thirdQuarter')">
                                <img src="images/extend-down.svg" alt="Extend Down Icon" class="extend-down-icon">
                            </div>
                            <div class="quarterly-report-title">
                                <div class="quarter-number" id="">Third Quarter</div>
                                <div class="month-name">JULY - SEPTEMBER</div>
                            </div>
                        </div>
                        <div class="total-diagnosis-box">
                            <div class="total-diagnosis-box-text">
                                <div class="total-number" style="font-size: 35px;">
                                    <?php 
                                    $total_sec_query = "SELECT COUNT(*) AS diagnosis_count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) IN (7, 8, 9)";

                                    if (!empty($selectedAcademicYear)) {
                                        $total_sec_query .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                    }

                                    $result = mysqli_query($conn, $total_sec_query);
                                    $row = mysqli_fetch_assoc($result);
                                    echo $row['diagnosis_count'];
                                    ?>
                                </div>
                                <div class="total-sub-text" style="font-size: 10px;">Diagnoses</div>
                            </div>
                        </div>
                    </div>

                    <div class="quarterly-report-alter collapsed">
                        <div class="alter-report-content">
                            <div class="alter-first-row">
                                <div class="alter-report-header">
                                    <div class="alter-header-content">
                                        <div class="extended-down-icon" onclick="toggleQuarter('thirdQuarter')">
                                            <img src="images/extended-down.svg" alt="Extended Down Icon"
                                                class="extended-down-icon">
                                        </div>
                                        <div class="alter-header-title">
                                            <div class="alter-title" id="">Third Quarter</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alter-second-row">
                                <div class="leading-diagnosis-box">
                                    <div class="leading-diagnosis-box-text">
                                        <div class="leading-diagnosis-text" style="font-size: 35px;"><?php echo $leading_diagnosis; ?></div>
                                        <div class="leading-diagnosis-subtext" style="font-size: 10px;">MOST COMMON
                                            MEDICAL CONDITION FOR THE QUARTER</div>
                                    </div>
                                </div>

                                <div class="total-diagnosis-box" style="background-color: #E13F3D;">
                                    <div class="total-diagnosis-box-text" style="color: white;">
                                        <div class="total-number" style="font-size: 35px;">
                                            <?php echo $diagnosis_count; ?></div>
                                        <div class="total-sub-text" style="font-size: 10px;">
                                            <?php echo $leading_diagnosis; ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="alter-third-row">
                                <div class="alter-third-row-label">
                                    <div class="alter-patient-diagnosed">Patient Diagnosed</div>
                                    <div class="alter-leading-diagnosis">Leading Diagnosis</div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">July</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_jul = "SELECT diagnosis, COUNT(*) AS count 
                                                      FROM treatment_record 
                                                      WHERE MONTH(date) = 7";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_jul .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_jul .= " GROUP BY diagnosis 
                                                        ORDER BY count DESC 
                                                        LIMIT 1";

                                        $result_jul = mysqli_query($conn, $query_jul);
                                        if ($row_jul = mysqli_fetch_assoc($result_jul)) {
                                            echo $row_jul['count'];
                                            $leading_diagnosis_jul = $row_jul['diagnosis'];
                                        } else {
                                            echo "No data";
                                            $leading_diagnosis_jul = "No data";
                                        }
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;"><?php echo $leading_diagnosis_jul; ?></div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">August</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_aug = "SELECT diagnosis, COUNT(*) AS count 
                                                      FROM treatment_record 
                                                      WHERE MONTH(date) = 8";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_aug .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_aug .= " GROUP BY diagnosis 
                                                        ORDER BY count DESC 
                                                        LIMIT 1";

                                        $result_aug = mysqli_query($conn, $query_aug);
                                        if ($row_aug = mysqli_fetch_assoc($result_aug)) {
                                            echo $row_aug['count'];
                                            $leading_diagnosis_aug = $row_aug['diagnosis'];
                                        } else {
                                            echo "No data";
                                            $leading_diagnosis_aug = "No data";
                                        }
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;"><?php echo $leading_diagnosis_aug; ?></div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">September</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_sep = "SELECT diagnosis, COUNT(*) AS count 
                                                      FROM treatment_record 
                                                      WHERE MONTH(date) = 9";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_sep .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_sep .= " GROUP BY diagnosis 
                                                        ORDER BY count DESC 
                                                        LIMIT 1";

                                        $result_sep = mysqli_query($conn, $query_sep);
                                        if ($row_sep = mysqli_fetch_assoc($result_sep)) {
                                            echo $row_sep['count'];
                                            $leading_diagnosis_sep = $row_sep['diagnosis'];
                                        } else {
                                            echo "No data";
                                            $leading_diagnosis_sep = "No data";
                                        }
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;"><?php echo $leading_diagnosis_sep; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  


            <!-- Fourth Quarter -->
            <div class="quarterly-report-row" id="fourthQuarter">
                <div class="quarterly-report-content">
                    <div class="quarterly-report-row-box">
                        <?php
                        // Fetch and display data for First Quarter
                        $query = "SELECT diagnosis, COUNT(*) AS diagnosis_count 
                                  FROM treatment_record 
                                  WHERE MONTH(date) IN (10, 11, 12)";

                        // Add condition to filter by academic year if selected
                        if (!empty($selectedAcademicYear)) {
                            $query .= " AND date BETWEEN '$startDate' AND '$endDate'";
                        }

                        $query .= " GROUP BY diagnosis 
                                    ORDER BY diagnosis_count DESC 
                                    LIMIT 1";

                        $result = mysqli_query($conn, $query);
                        if ($row = mysqli_fetch_assoc($result)) {
                            $leading_diagnosis = $row['diagnosis'];
                            $diagnosis_count = $row['diagnosis_count'];
                        } else {
                            $leading_diagnosis = "No data";
                            $diagnosis_count = 0;
                        }
                        ?>
                        <div class="row-first-content">
                            <div class="extend-down-icon" onclick="toggleQuarter('fourthQuarter')">
                                <img src="images/extend-down.svg" alt="Extend Down Icon" class="extend-down-icon">
                            </div>
                            <div class="quarterly-report-title">
                                <div class="quarter-number" id="">Fourth Quarter</div>
                                <div class="month-name">OCTOBER - DECEMBER</div>
                            </div>
                        </div>
                        <div class="total-diagnosis-box">
                            <div class="total-diagnosis-box-text">
                                <div class="total-number" style="font-size: 35px;">
                                    <?php 
                                    $total_sec_query = "SELECT COUNT(*) AS diagnosis_count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) IN (10, 11, 12)";

                                    if (!empty($selectedAcademicYear)) {
                                        $total_sec_query .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                    }

                                    $result = mysqli_query($conn, $total_sec_query);
                                    $row = mysqli_fetch_assoc($result);
                                    echo $row['diagnosis_count'];
                                    ?>
                                </div>
                                <div class="total-sub-text" style="font-size: 10px;">Diagnoses</div>
                            </div>
                        </div>
                    </div>

                    <div class="quarterly-report-alter collapsed">
                        <div class="alter-report-content">
                            <div class="alter-first-row">
                                <div class="alter-report-header">
                                    <div class="alter-header-content">
                                        <div class="extended-down-icon" onclick="toggleQuarter('fourthQuarter')">
                                            <img src="images/extended-down.svg" alt="Extended Down Icon"
                                                class="extended-down-icon">
                                        </div>
                                        <div class="alter-header-title">
                                            <div class="alter-title" id="">Fourth Quarter</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alter-second-row">
                                <div class="leading-diagnosis-box">
                                    <div class="leading-diagnosis-box-text">
                                        <div class="leading-diagnosis-text" style="font-size: 35px;"><?php echo $leading_diagnosis; ?></div>
                                        <div class="leading-diagnosis-subtext" style="font-size: 10px;">MOST COMMON
                                            MEDICAL CONDITION FOR THE QUARTER</div>
                                    </div>
                                </div>

                                <div class="total-diagnosis-box" style="background-color: #E13F3D;">
                                    <div class="total-diagnosis-box-text" style="color: white;">
                                        <div class="total-number" style="font-size: 35px;">
                                            <?php echo $diagnosis_count; ?></div>
                                        <div class="total-sub-text" style="font-size: 10px;">
                                            <?php echo $leading_diagnosis; ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="alter-third-row">
                                <div class="alter-third-row-label">
                                    <div class="alter-patient-diagnosed">Patient Diagnosed</div>
                                    <div class="alter-leading-diagnosis">Leading Diagnosis</div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">October</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_oct = "SELECT diagnosis, COUNT(*) AS count 
                                                      FROM treatment_record 
                                                      WHERE MONTH(date) = 10";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_oct .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_oct .= " GROUP BY diagnosis 
                                                        ORDER BY count DESC 
                                                        LIMIT 1";

                                        $result_oct = mysqli_query($conn, $query_oct);
                                        if ($row_oct = mysqli_fetch_assoc($result_oct)) {
                                            echo $row_oct['count'];
                                            $leading_diagnosis_oct = $row_oct['diagnosis'];
                                        } else {
                                            echo "No data";
                                            $leading_diagnosis_oct = "No data";
                                        }
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;"><?php echo $leading_diagnosis_oct; ?></div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">November</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_nov = "SELECT diagnosis, COUNT(*) AS count 
                                                      FROM treatment_record 
                                                      WHERE MONTH(date) = 11";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_nov .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_nov .= " GROUP BY diagnosis 
                                                        ORDER BY count DESC 
                                                        LIMIT 1";

                                        $result_nov = mysqli_query($conn, $query_nov);
                                        if ($row_nov = mysqli_fetch_assoc($result_nov)) {
                                            echo $row_nov['count'];
                                            $leading_diagnosis_nov = $row_nov['diagnosis'];
                                        } else {
                                            echo "No data";
                                            $leading_diagnosis_nov = "No data";
                                        }
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;"><?php echo $leading_diagnosis_nov; ?></div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">December</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_dec = "SELECT diagnosis, COUNT(*) AS count 
                                                      FROM treatment_record 
                                                      WHERE MONTH(date) = 12";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_dec .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_dec .= " GROUP BY diagnosis 
                                                        ORDER BY count DESC 
                                                        LIMIT 1";

                                        $result_dec = mysqli_query($conn, $query_dec);
                                        if ($row_dec = mysqli_fetch_assoc($result_dec)) {
                                            echo $row_dec['count'];
                                            $leading_diagnosis_dec = $row_dec['diagnosis'];
                                        } else {
                                            echo "No data";
                                            $leading_diagnosis_dec = "No data";
                                        }
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;"><?php echo $leading_diagnosis_dec; ?></div>
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

    <script>
        function toggleDeleteButton() {
        var checkboxes = document.querySelectorAll('input[name="delete_record[]"]');
        var deleteButton = document.getElementById('delete-selected-link');

        var checked = false;
        checkboxes.forEach(function (checkbox) {
            if (checkbox.checked) {
                checked = true;
            }
        });

        if (checked) {
            deleteButton.classList.remove('disabled'); // Enable the button
        } else {
            deleteButton.classList.add('disabled'); // Disable the button
        }
    }
    </script>

<script>
    $(document).ready(function () {
        // Show the confirm download modal when the download button is clicked
        $('.download-button').click(function (event) {
            // Prevent the default action of the download link
            event.preventDefault();
            $('#downloadModal').modal('show');
        });

        // Hide the confirm download modal when the cancel button is clicked
        $('#cancel-download').click(function () {
            $('#downloadModal').modal('hide');
        });

        // When the user confirms the download, trigger the download and show the download successful modal
        $('#confirm-download').click(function () {
            // Trigger the download
            window.location.href = '?download=1';

            // Show the download successful modal after a delay to allow the download to start
            setTimeout(function () {
                $('#downloadModal').modal('hide');
                $('#download-successful-modal').modal('show');
            }, 1000); // Adjust delay as needed
        });

        // Hide the download successful modal when the close button is clicked
        $('#download-close-modal').click(function () {
            $('#download-successful-modal').modal('hide');
        });
    });

    function toggleDeleteMode() {
    // Disable view links
    var viewLinks = document.querySelectorAll('.view-link');
    viewLinks.forEach(function(link) {
        link.classList.add('disabled-view');
    });

    var checkboxes = document.querySelectorAll('input[name="delete_record[]"]');
    checkboxes.forEach(function (checkbox) {
        checkbox.style.display = 'inline-block';
    });

    // Show delete buttons
    document.getElementById('delete-toggle-link').style.display = 'none';
    document.getElementById('delete-selected-link').style.display = 'inline-block';
    document.getElementById('cancel-delete-link').style.display = 'inline-block';

    // Hide download button
    document.getElementById('downloadButton').style.display = 'none';
    
    // Hide pagination buttons
    document.getElementsByClassName('sorting-pagination-container')[0].style.display = 'none';
}

// Function to cancel delete mode
function cancelDeleteMode() {
    // Show sorting-pagination-container
    $('.sorting-pagination-container').show();

    // Enable view links
    var viewLinks = document.querySelectorAll('.view-link');
    viewLinks.forEach(function(link) {
    link.classList.remove('disabled-view');
    });

    // Hide delete mode buttons and show download button
    $('#delete-toggle-link').show();
    $('#delete-selected-link').hide();
    $('#cancel-delete-link').hide();
    $('#downloadButton').show();

    // Uncheck all checkboxes and hide them
    $('input[name="delete_record[]"]').prop('checked', false).hide();

    // Update pagination links to reflect the current page state
    updatePaginationLinks();
}

// Function to update pagination links based on current page
function updatePaginationLinks() {
    var currentPage = <?php echo $currentPage; ?>;
    var totalPages = <?php echo $totalPages; ?>;

    // Update previous page link
    var prevPageLink = $('.pagination-button:first');
    prevPageLink.attr('href', '?page=' + Math.max(1, currentPage - 1));
    if (currentPage === 1) {
        prevPageLink.addClass('disabled');
    } else {
        prevPageLink.removeClass('disabled');
    }

    // Update next page link
    var nextPageLink = $('.pagination-button:last');
    nextPageLink.attr('href', '?page=' + Math.min(totalPages, currentPage + 1));
    if (currentPage === totalPages) {
        nextPageLink.addClass('disabled');
    } else {
        nextPageLink.removeClass('disabled');
    }
}
    // Show download button
    document.getElementById('downloadButton').style.display = 'inline-block';

    // Hide delete buttons
    document.getElementById('delete-toggle-link').style.display = 'inline-block';
    document.getElementById('delete-selected-link').style.display = 'none';
    document.getElementById('cancel-delete-link').style.display = 'none';


    $(document).ready(function () {
    // Initial button state
    toggleDeleteButton();

    // Show the confirm delete modal when delete selected link is clicked
    $('#delete-selected-link').click(function () {
        $('#confirmDeleteModal').modal('show');
    });

    // Handle cancel delete mode
    $('#cancel-delete-link').click(function () {
        cancelDeleteMode();
    });

    // Handle confirm delete action
    $('#confirm-delete').click(function () {
        // Trigger form submission
        $('#confirmDeleteButton').click();
    });
});


function changeSortCriteria(sort) {
    var academicYear = '<?php echo $selectedAcademicYear; ?>';
    var url = '?sort=' + sort + (academicYear ? '&academic_year=' + academicYear : '');
    window.location.href = url;
}
</script>

</body>

</html>
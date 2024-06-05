<?php
require_once ('includes/session-nurse.php');
require_once ('includes/connect.php');
require_once ('../vendors/tcpdf/tcpdf.php');

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
        $pdf->Cell(40, 10, $row['date'], 1, 1, 'C');
    }

    // Output the PDF as a download
    $pdf->Output('treatment_records.pdf', 'D');
    exit;
}

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
    <title>Records</title>
    <link rel="icon" type="image/png" href="images/heart-logo.png">
    <link rel="stylesheet" href="styles/dboardStyle.css">
    <link rel="stylesheet" href="styles/modals.css">
    <link rel="stylesheet" href="../vendors/bootstrap-5.0.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>

<body>
    <div class="loader">
        <img src="images/loader.gif">
    </div>

    <div class="overlay" id="overlay"></div>

    <div class="main-content">
        <?php
        include ('includes/sidebar/records.php');
        ?>

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
                    <button type="button" class="btn btn-secondary" id="cancel-download" data-dismiss="modal" style="background-color: #777777; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-right: 1.25rem;">Cancel</button>
                    <button type="button" class="btn btn-secondary" id="confirm-download" data-dismiss="modal" style="background-color: #058789; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem;">Confirm</button>
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
                            style="background-color: #23B26D; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-top: 1rem;">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="content" id="content">
            <div class="med-reports-header">
                <div class="med-reports-header-box">
                    <div class="medreports-header-text">Medical Records Archive</div>
                    <div class="medreports-sorting-button" id="medReportsortingButton">
                        <form method="GET">
                            <select name="academic_year" id="medReportsortCriteria"
                                style="font-family: 'Poppins', sans-serif; font-weight: bold;"
                                onchange="this.form.submit()">
                                <option value="" disabled selected hidden>Academic Year</option>
                                <option value="2025">2024-2025</option>
                                <option value="2024">2023-2024</option>
                                <option value="2023">2022-2023</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <div class="header-middle" style="margin: 0 20px 0 20px;">Treatment Record</div>
            <!-- Table Container -->
            <div class="table-container">
                <table class="dashboard-table" style="margin-bottom: 80px;">
                    <tr>
                        <th>Patient Name</th>
                        <th>Course</th>
                        <th>Section</th>
                        <th>Gender</th>
                        <th>Date</th>
                    </tr>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo "<a href='patients-treatment-record.php?patient_id=" . $row["patient_id"] . "'>" . $row["full_name"] ?>
                                    </a></td>

                                <td><?php echo $row['course']; ?></td>
                                <td><?php echo $row['section']; ?></td>
                                <td><?php echo $row['sex']; ?></td>
                                <td><?php echo $row['date']; ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="5">No records found</td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td colspan="5"> <!-- Use colspan to span across all columns -->

                            <!-- Inside the table button container -->
                            <div class="table-button-container">
                                <div class="button-group">
                                    <div class="delete-records" onclick="window.location.href=''">
                                        <i class="bi bi-trash"
                                            style="color: #D22B2B; font-size: 1rem; margin-right: 0.625rem; vertical-align: middle;"></i>
                                        Delete Records
                                    </div>
                                    <div class="button-separator"></div>
                                    <div class="download-button">
                                        <a style="color: #058789; text-decoration: none;" href="?download=1">
                                            <i class="bi bi-download"
                                                style="color: #058789; font-size: 1rem; margin-right: 0.625rem; vertical-align: middle;"></i>
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
                                        <select id="sortCriteria"
                                            style="font-family: 'Poppins', sans-serif; font-weight: bold;">
                                            <option value="annually">Annually</option>
                                            <option value="monthly">Monthly</option>
                                            <option value="weekly">Weekly</option>
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
                                            style="text-decoration: none;"
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


            <div class="header-middle" style="margin: 0 20px 0 20px;">Quarterly Report</div>

            <!-- First Quarter -->
            <div class="quarterly-report-row" id="firstQuarter">
                <div class="quarterly-report-content">
                    <div class="quarterly-report-row-box">
                        <?php
                        // Fetch and display data for Second Quarter
                        $query = "SELECT diagnosis, COUNT(*) AS diagnosis_count 
                                FROM treatment_record 
                                WHERE MONTH(date) IN (1, 2, 3) ";

                        // Add condition to filter by academic year if selected
                        if (!empty($selectedAcademicYear)) {
                            $query .= "AND YEAR(date) = $selectedAcademicYear ";
                        }

                        $query .= "GROUP BY diagnosis 
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
                                <?php $total_sec_query = "SELECT diagnosis, COUNT(*) AS diagnosis_count 
                                FROM treatment_record 
                                WHERE MONTH(date) IN (1, 2, 3)";

                                $result = mysqli_query($conn, $total_sec_query);
                                $row = mysqli_fetch_assoc($result);
                                echo $row['diagnosis_count'];  ?>
                                
                            </div>
                                <div class="total-sub-text" style="font-size: 10px;">Diagnoses
                                </div>
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
                                        <div class="leading-diagnosis-text" style="font-size: 35px;">LEADING DIAGNOSIS
                                        </div>
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
                                        // Fetch and display the count of unique patient IDs for April
                                        $query = "SELECT diagnosis, COUNT(*) AS count FROM treatment_record WHERE MONTH(date) = 1";
                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);
                                        echo $row['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 1
                                    </div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">February</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        // Fetch and display the count of unique patient IDs for April
                                        $query = "SELECT diagnosis, COUNT(*) AS count FROM treatment_record WHERE MONTH(date) = 2";
                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);
                                        echo $row['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 2
                                    </div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">March</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        // Fetch and display the count of unique patient IDs for April
                                        $query = "SELECT diagnosis, COUNT(*) AS count FROM treatment_record WHERE MONTH(date) = 3";
                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);
                                        echo $row['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 3
                                    </div>
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
                        // Fetch and display data for Second Quarter
                        $query = "SELECT diagnosis, COUNT(*) AS diagnosis_count 
                                FROM treatment_record 
                                WHERE MONTH(date) IN (4, 5, 6) ";

                        // Add condition to filter by academic year if selected
                        if (!empty($selectedAcademicYear)) {
                            $query .= "AND YEAR(date) = $selectedAcademicYear ";
                        }

                        $query .= "GROUP BY diagnosis 
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
                                <?php $total_sec_query = "SELECT diagnosis, COUNT(*) AS diagnosis_count 
                                FROM treatment_record 
                                WHERE MONTH(date) IN (4, 5, 6)";

                                $result = mysqli_query($conn, $total_sec_query);
                                $row = mysqli_fetch_assoc($result);
                                echo $row['diagnosis_count'];  ?>
                                
                            </div>
                                <div class="total-sub-text" style="font-size: 10px;">Diagnoses
                                </div>
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
                                        <div class="leading-diagnosis-text" style="font-size: 35px;">LEADING DIAGNOSIS
                                        </div>
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
                                        // Fetch and display the count of unique patient IDs for April
                                        $query = "SELECT diagnosis, COUNT(*) AS count FROM treatment_record WHERE MONTH(date) = 4";
                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);
                                        echo $row['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 1
                                    </div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">May</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        // Fetch and display the count of unique patient IDs for April
                                        $query = "SELECT diagnosis, COUNT(*) AS count FROM treatment_record WHERE MONTH(date) = 5";
                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);
                                        echo $row['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 2
                                    </div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">June</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        // Fetch and display the count of unique patient IDs for April
                                        $query = "SELECT diagnosis, COUNT(*) AS count FROM treatment_record WHERE MONTH(date) = 6";
                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);
                                        echo $row['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 3
                                    </div>
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
                        // Fetch and display data for Second Quarter
                        $query = "SELECT diagnosis, COUNT(*) AS diagnosis_count 
                                FROM treatment_record 
                                WHERE MONTH(date) IN (7, 8, 9) ";

                        // Add condition to filter by academic year if selected
                        if (!empty($selectedAcademicYear)) {
                            $query .= "AND YEAR(date) = $selectedAcademicYear ";
                        }

                        $query .= "GROUP BY diagnosis 
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
                                <div class="total-number" style="font-size: 35px;"><?php echo $diagnosis_count; ?></div>
                                <div class="total-sub-text" style="font-size: 10px;"><?php echo $leading_diagnosis; ?>
                                </div>
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
                                        <div class="leading-diagnosis-text" style="font-size: 35px;">LEADING DIAGNOSIS
                                        </div>
                                        <div class="leading-diagnosis-subtext" style="font-size: 10px;">MOST COMMON
                                            MEDICAL CONDITION FOR THE QUARTER</div>
                                    </div>
                                </div>

                                <div class="total-diagnosis-box" style="background-color: #E13F3D;">
                                    <div class="total-diagnosis-box-text" style="color: white;">
                                        <div class="total-number" style="font-size: 35px;">
                                            <?php $total_sec_query = "SELECT diagnosis, COUNT(*) AS diagnosis_count 
                                FROM treatment_record 
                                WHERE MONTH(date) IN (7, 8, 9)";

                                            $result = mysqli_query($conn, $total_sec_query);
                                            $row = mysqli_fetch_assoc($result);
                                            echo $row['diagnosis_count']; ?>

                                        </div>
                                        <div class="total-sub-text" style="font-size: 10px;">Diagnoses
                                        </div>
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
                                        // Fetch and display the count of unique patient IDs for April
                                        $query = "SELECT diagnosis, COUNT(*) AS count FROM treatment_record WHERE MONTH(date) = 7";
                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);
                                        echo $row['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 1
                                    </div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">August</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        // Fetch and display the count of unique patient IDs for April
                                        $query = "SELECT diagnosis, COUNT(*) AS count FROM treatment_record WHERE MONTH(date) = 8";
                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);
                                        echo $row['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 2
                                    </div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">September</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        // Fetch and display the count of unique patient IDs for April
                                        $query = "SELECT diagnosis, COUNT(*) AS count FROM treatment_record WHERE MONTH(date) = 9";
                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);
                                        echo $row['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 3
                                    </div>
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
                        // Fetch and display data for Second Quarter
                        $query = "SELECT diagnosis, COUNT(*) AS diagnosis_count 
                                FROM treatment_record 
                                WHERE MONTH(date) IN (10, 11, 12) ";

                        // Add condition to filter by academic year if selected
                        if (!empty($selectedAcademicYear)) {
                            $query .= "AND YEAR(date) = $selectedAcademicYear ";
                        }

                        $query .= "GROUP BY diagnosis 
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
                                <?php $total_sec_query = "SELECT diagnosis, COUNT(*) AS diagnosis_count 
                                FROM treatment_record 
                                WHERE MONTH(date) IN (10, 11, 12)";

                                $result = mysqli_query($conn, $total_sec_query);
                                $row = mysqli_fetch_assoc($result);
                                echo $row['diagnosis_count'];  ?>
                                
                            </div>
                                <div class="total-sub-text" style="font-size: 10px;">Diagnoses
                                </div>
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
                                        <div class="leading-diagnosis-text" style="font-size: 35px;">LEADING DIAGNOSIS
                                        </div>
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
                                        // Fetch and display the count of unique patient IDs for April
                                        $query = "SELECT diagnosis, COUNT(*) AS count FROM treatment_record WHERE MONTH(date) = 10";
                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);
                                        echo $row['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 1
                                    </div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">November</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        // Fetch and display the count of unique patient IDs for April
                                        $query = "SELECT diagnosis, COUNT(*) AS count FROM treatment_record WHERE MONTH(date) = 11";
                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);
                                        echo $row['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 2
                                    </div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">December</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        // Fetch and display the count of unique patient IDs for April
                                        $query = "SELECT diagnosis, COUNT(*) AS count FROM treatment_record WHERE MONTH(date) = 12";
                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);
                                        echo $row['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 3
                                    </div>
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
</script>



</body>

</html>
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
        include ('includes/sidebar/reports.php');
        ?>



        <div class="content" id="content">
            <div class="med-reports-header">
                <div class="med-reports-header-box">
                    <div class="medreports-header-text">Quarterly Reports</div>
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
                                    echo $row['diagnosis_count']; ?>

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
                                            MEDICAL
                                            CONDITION FOR THE QUARTER</div>
                                    </div>
                                </div>

                                <div class="total-diagnosis-box" style="background-color: #E13F3D;">
                                    <div class="total-diagnosis-box-text" style="color: white;">
                                        <div class="total-number" style="font-size: 35px;">
                                            <?php echo $diagnosis_count; ?>
                                        </div>
                                        <div class="total-sub-text" style="font-size: 10px;">
                                            <?php echo $leading_diagnosis; ?>
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
                                    echo $row['diagnosis_count']; ?>

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
                                            MEDICAL
                                            CONDITION FOR THE QUARTER</div>
                                    </div>
                                </div>

                                <div class="total-diagnosis-box" style="background-color: #E13F3D;">
                                    <div class="total-diagnosis-box-text" style="color: white;">
                                        <div class="total-number" style="font-size: 35px;">
                                            <?php echo $diagnosis_count; ?>
                                        </div>
                                        <div class="total-sub-text" style="font-size: 10px;">
                                            <?php echo $leading_diagnosis; ?>
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
                            <div class="total-number" style="font-size: 35px;">
                                <?php $total_sec_query = "SELECT diagnosis, COUNT(*) AS diagnosis_count 
                                FROM treatment_record 
                                WHERE MONTH(date) IN (7, 8, 9)";

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
                                            MEDICAL
                                            CONDITION FOR THE QUARTER</div>
                                    </div>
                                </div>

                                <div class="total-diagnosis-box" style="background-color: #E13F3D;">
                                    <div class="total-diagnosis-box-text" style="color: white;">
                                        <div class="total-number" style="font-size: 35px;">
                                            <?php echo $diagnosis_count; ?>
                                        </div>
                                        <div class="total-sub-text" style="font-size: 10px;">
                                            <?php echo $leading_diagnosis; ?>
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
                                            MEDICAL
                                            CONDITION FOR THE QUARTER</div>
                                    </div>
                                </div>

                                <div class="total-diagnosis-box" style="background-color: #E13F3D;">
                                    <div class="total-diagnosis-box-text" style="color: white;">
                                        <div class="total-number" style="font-size: 35px;">
                                            <?php echo $diagnosis_count; ?>
                                        </div>
                                        <div class="total-sub-text" style="font-size: 10px;">
                                            <?php echo $leading_diagnosis; ?>
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
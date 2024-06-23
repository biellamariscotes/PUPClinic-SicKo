<?php
require_once('includes/session-nurse.php');
require_once('includes/connect.php');

$selectedAcademicYear = isset($_GET['academic_year']) ? $_GET['academic_year'] : '';

if ($selectedAcademicYear) {
    $startYear = $selectedAcademicYear - 1;
    $endYear = $selectedAcademicYear;
    $startDate = "$startYear-09-01";
    $endDate = "$endYear-07-30";
}
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

<style>
    select:focus {
        outline: none;
        border-color: transparent;
        box-shadow: none;
    }
</style>

<div>

    <div class="loader">
        <img src="images/loader.gif">
    </div>

    <div class="overlay" id="overlay"></div>

    <div class="main-content">
        <?php
        include('includes/sidebar/reports.php');
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
                                <option value="" selected>Academic Year</option>
                                <option value="2025" <?php echo $selectedAcademicYear == '2025' ? 'selected' : ''; ?>>2024-2025</option>
                                <option value="2024" <?php echo $selectedAcademicYear == '2024' ? 'selected' : ''; ?>>2023-2024</option>
                                <option value="2023" <?php echo $selectedAcademicYear == '2023' ? 'selected' : ''; ?>>2022-2023</option>
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
                                        // Query and display all diagnoses count for April
                                        $query_jan_all = "SELECT COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 1";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_jan_all .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $result_jan_all = mysqli_query($conn, $query_jan_all);
                                        $row_jan_all = mysqli_fetch_assoc($result_jan_all);
                                        echo $row_jan_all['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_jan_lead = "SELECT diagnosis, COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 1";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_jan_lead .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_jan_lead .= " GROUP BY diagnosis 
                                                            ORDER BY count DESC 
                                                            LIMIT 1";

                                        $result_jan_lead = mysqli_query($conn, $query_jan_lead);
                                        if ($row_jan_lead = mysqli_fetch_assoc($result_jan_lead)) {
                                            echo $row_jan_lead['diagnosis'];
                                        } else {
                                            echo "No data";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">February</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_feb_all = "SELECT COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 2";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_feb_all .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $result_feb_all = mysqli_query($conn, $query_feb_all);
                                        $row_feb_all = mysqli_fetch_assoc($result_feb_all);
                                        echo $row_feb_all['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_feb_lead = "SELECT diagnosis, COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 2";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_feb_lead .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_feb_lead .= " GROUP BY diagnosis 
                                                            ORDER BY count DESC 
                                                            LIMIT 1";

                                        $result_feb_lead = mysqli_query($conn, $query_feb_lead);
                                        if ($row_feb_lead = mysqli_fetch_assoc($result_feb_lead)) {
                                            echo $row_feb_lead['diagnosis'];
                                        } else {
                                            echo "No data";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">March</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        // Query and display all diagnoses count for June
                                        $query_mar_all = "SELECT COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 3";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_mar_all .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $result_mar_all = mysqli_query($conn, $query_mar_all);
                                        $row_mar_all = mysqli_fetch_assoc($result_mar_all);
                                        echo $row_mar_all['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_mar_lead = "SELECT diagnosis, COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 3";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_mar_lead .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_mar_lead .= " GROUP BY diagnosis 
                                                            ORDER BY count DESC 
                                                            LIMIT 1";

                                        $result_mar_lead = mysqli_query($conn, $query_mar_lead);
                                        if ($row_mar_lead = mysqli_fetch_assoc($result_mar_lead)) {
                                            echo $row_mar_lead['diagnosis'];
                                        } else {
                                            echo "No data";
                                        }
                                        ?>
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
                                        // Query and display all diagnoses count for April
                                        $query_apr_all = "SELECT COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 4";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_apr_all .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $result_apr_all = mysqli_query($conn, $query_apr_all);
                                        $row_apr_all = mysqli_fetch_assoc($result_apr_all);
                                        echo $row_apr_all['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">
                                        <!-- Display the leading diagnosis for April -->
                                        <?php
                                        $query_apr_lead = "SELECT diagnosis, COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 4";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_apr_lead .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_apr_lead .= " GROUP BY diagnosis 
                                                            ORDER BY count DESC 
                                                            LIMIT 1";

                                        $result_apr_lead = mysqli_query($conn, $query_apr_lead);
                                        if ($row_apr_lead = mysqli_fetch_assoc($result_apr_lead)) {
                                            echo $row_apr_lead['diagnosis'];
                                        } else {
                                            echo "No data";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">May</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        // Query and display all diagnoses count for May
                                        $query_may_all = "SELECT COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 5";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_may_all .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $result_may_all = mysqli_query($conn, $query_may_all);
                                        $row_may_all = mysqli_fetch_assoc($result_may_all);
                                        echo $row_may_all['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">
                                        <!-- Display the leading diagnosis for May -->
                                        <?php
                                        $query_may_lead = "SELECT diagnosis, COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 5";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_may_lead .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_may_lead .= " GROUP BY diagnosis 
                                                            ORDER BY count DESC 
                                                            LIMIT 1";

                                        $result_may_lead = mysqli_query($conn, $query_may_lead);
                                        if ($row_may_lead = mysqli_fetch_assoc($result_may_lead)) {
                                            echo $row_may_lead['diagnosis'];
                                        } else {
                                            echo "No data";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">June</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        // Query and display all diagnoses count for June
                                        $query_jun_all = "SELECT COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 6";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_jun_all .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $result_jun_all = mysqli_query($conn, $query_jun_all);
                                        $row_jun_all = mysqli_fetch_assoc($result_jun_all);
                                        echo $row_jun_all['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">
                                        <!-- Display the leading diagnosis for June -->
                                        <?php
                                        $query_jun_lead = "SELECT diagnosis, COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 6";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_jun_lead .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_jun_lead .= " GROUP BY diagnosis 
                                                            ORDER BY count DESC 
                                                            LIMIT 1";

                                        $result_jun_lead = mysqli_query($conn, $query_jun_lead);
                                        if ($row_jun_lead = mysqli_fetch_assoc($result_jun_lead)) {
                                            echo $row_jun_lead['diagnosis'];
                                        } else {
                                            echo "No data";
                                        }
                                        ?>
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
                                        $query_jul_all = "SELECT COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 7";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_jul_all .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $result_jul_all = mysqli_query($conn, $query_jul_all);
                                        $row_jul_all = mysqli_fetch_assoc($result_jul_all);
                                        echo $row_jul_all['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_jul_lead = "SELECT diagnosis, COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 7";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_jul_lead .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_jul_lead .= " GROUP BY diagnosis 
                                                            ORDER BY count DESC 
                                                            LIMIT 1";

                                        $result_jul_lead = mysqli_query($conn, $query_jul_lead);
                                        if ($row_jul_lead = mysqli_fetch_assoc($result_jul_lead)) {
                                            echo $row_jul_lead['diagnosis'];
                                        } else {
                                            echo "No data";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">August</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_aug_all = "SELECT COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 8";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_aug_all .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $result_aug_all = mysqli_query($conn, $query_aug_all);
                                        $row_aug_all = mysqli_fetch_assoc($result_aug_all);
                                        echo $row_aug_all['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_aug_lead = "SELECT diagnosis, COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 8";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_aug_lead .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_aug_lead .= " GROUP BY diagnosis 
                                                            ORDER BY count DESC 
                                                            LIMIT 1";

                                        $result_aug_lead = mysqli_query($conn, $query_aug_lead);
                                        if ($row_aug_lead = mysqli_fetch_assoc($result_aug_lead)) {
                                            echo $row_aug_lead['diagnosis'];
                                        } else {
                                            echo "No data";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">September</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_sep_all = "SELECT COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 9";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_sep_all .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $result_sep_all = mysqli_query($conn, $query_sep_all);
                                        $row_sep_all = mysqli_fetch_assoc($result_sep_all);
                                        echo $row_sep_all['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_sep_lead = "SELECT diagnosis, COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 9";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_sep_lead .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_sep_lead .= " GROUP BY diagnosis 
                                                            ORDER BY count DESC 
                                                            LIMIT 1";

                                        $result_sep_lead = mysqli_query($conn, $query_sep_lead);
                                        if ($row_sep_lead = mysqli_fetch_assoc($result_sep_lead)) {
                                            echo $row_sep_lead['diagnosis'];
                                        } else {
                                            echo "No data";
                                        }
                                        ?>
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
                                        $query_oct_all = "SELECT COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 10";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_oct_all .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $result_oct_all = mysqli_query($conn, $query_oct_all);
                                        $row_oct_all = mysqli_fetch_assoc($result_oct_all);
                                        echo $row_oct_all['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_oct_lead = "SELECT diagnosis, COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 10";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_oct_lead .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_oct_lead .= " GROUP BY diagnosis 
                                                            ORDER BY count DESC 
                                                            LIMIT 1";

                                        $result_oct_lead = mysqli_query($conn, $query_oct_lead);
                                        if ($row_oct_lead = mysqli_fetch_assoc($result_oct_lead)) {
                                            echo $row_oct_lead['diagnosis'];
                                        } else {
                                            echo "No data";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">November</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_nov_all = "SELECT COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 11";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_nov_all .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $result_nov_all = mysqli_query($conn, $query_nov_all);
                                        $row_nov_all = mysqli_fetch_assoc($result_nov_all);
                                        echo $row_nov_all['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_nov_lead = "SELECT diagnosis, COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 11";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_nov_lead .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_nov_lead .= " GROUP BY diagnosis 
                                                            ORDER BY count DESC 
                                                            LIMIT 1";

                                        $result_nov_lead = mysqli_query($conn, $query_nov_lead);
                                        if ($row_nov_lead = mysqli_fetch_assoc($result_nov_lead)) {
                                            echo $row_nov_lead['diagnosis'];
                                        } else {
                                            echo "No data";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">December</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_dec_all = "SELECT COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 12";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_dec_all .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $result_dec_all = mysqli_query($conn, $query_dec_all);
                                        $row_dec_all = mysqli_fetch_assoc($result_dec_all);
                                        echo $row_dec_all['count'];
                                        ?>
                                    </div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">
                                        <?php
                                        $query_dec_lead = "SELECT diagnosis, COUNT(*) AS count 
                                                        FROM treatment_record 
                                                        WHERE MONTH(date) = 12";

                                        if (!empty($selectedAcademicYear)) {
                                            $query_dec_lead .= " AND date BETWEEN '$startDate' AND '$endDate'";
                                        }

                                        $query_dec_lead .= " GROUP BY diagnosis 
                                                            ORDER BY count DESC 
                                                            LIMIT 1";

                                        $result_dec_lead = mysqli_query($conn, $query_dec_lead);
                                        if ($row_dec_lead = mysqli_fetch_assoc($result_dec_lead)) {
                                            echo $row_dec_lead['diagnosis'];
                                        } else {
                                            echo "No data";
                                        }
                                        ?>
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
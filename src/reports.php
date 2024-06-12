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
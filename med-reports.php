<?php
require_once('src/includes/session-nurse.php');
require_once('src/includes/connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Med Reports</title>
    <link rel="icon" type="image/png" href="src/images/sicko-logo.png">
    <link rel="stylesheet" href="src/styles/dboardStyle.css">
    <link rel="stylesheet" href="src/styles/modals.css">
    <link rel="stylesheet" href="vendors/bootstrap-5.0.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="overlay" id="overlay"></div>

<?php
    include ('src/includes/sidebar/med-reports.php');
    ?>

    <div class="content" id="content" style="margin-bottom: 100px;">
        <div class="med-reports-header">
            <div class="med-reports-header-box">
                <div class="medreports-header-text">Medical Records Archive</div>
                <div class="medreports-sorting-button" id="medReportsortingButton">
                                    <select id="medReportsortCriteria" style="font-family: 'Poppins', sans-serif; font-weight: bold;">
                                        <option value="" disabled selected hidden>Academic Year</option>
                                        <option value="ay1">2024-2025</option>
                                        <option value="ay2">2023-2024</option>
                                        <option value="ay2">2022-2023</option>
                                    </select>
                                </div>
            </div>
        </div>

    <div class="header-middle" style="margin: 0 20px 0 20px;">Daily Treatment Record</div>
    <!-- Table Container -->
        <div class="table-container">
            <table class="dashboard-table" style="margin-bottom: 80px;">
                <tr>
                    <th>Patient Name</th>
                    <th>Course</th>
                    <th>Section</th>
                    <th>Gender</th>
                </tr>
                <tr>
                    <td class="nameColumn" onclick="window.location.href='patients-treatment-record.php'">Apolo L. Trasmonte</td>
                    <td>Information Technology</td>
                    <td>BSIT 3-1</td>
                    <td>Male</td>
                </tr>
                <tr>
                    <td class="nameColumn" onclick="redirectToInfoPage()">Mikaela Tahum</td>
                    <td>Information Technology</td>
                    <td>BSIT 3-1</td>
                    <td>Female</td>
                </tr>
                <tr>
                    <td class="nameColumn" onclick="redirectToInfoPage()">Biella Requina</td>
                    <td>Information Technology</td>
                    <td>BSIT 3-1</td>
                    <td>Female</td>
                </tr>
                <tr>
                    <td class="nameColumn" onclick="redirectToInfoPage()">Andrei Matibag</td>
                    <td>Information Technology</td>
                    <td>BSIT 3-1</td>
                    <td>Male</td>
                </tr>
                <tr>
                    <td class="nameColumn" onclick="redirectToInfoPage()">Bobby Morante</td>
                    <td>Information Technology</td>
                    <td>BSIT 3-1</td>
                    <td>Male</td>
                </tr>
                <tr>
                    <td colspan="4"> <!-- Use colspan to span across all columns -->

                        <!-- Inside the table button container -->
                        <div class="table-button-container">
                            <div class="edit-delete-records" onclick="window.location.href=''">Edit/Delete Records</div>

                            <!-- Sorting and Pagination Container -->
                            <div class="sorting-pagination-container">
                                <!-- Sorting button box -->
                                <div class="sorting-button-box" id="sortingButtonBox">
                                    <!-- Sort text -->
                                    Sort by:
                                    <select id="sortCriteria" style="font-family: 'Poppins', sans-serif; font-weight: bold;">
                                        <option value="annually">Annually</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="weekly">Weekly</option>
                                    </select>
                                </div>
                                <!-- Pagination buttons -->
                                <div class="pagination-buttons">
                                    <!-- Previous button -->
                                    <button class="pagination-button" id="previousButton">
                                        &lt;
                                    </button>
                                    <!-- Next button -->
                                    <button class="pagination-button" id="nextButton">
                                        &gt;
                                    </button>
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
                        <div class="row-first-content">
                            <div class="extend-down-icon" onclick="toggleQuarter('firstQuarter')">
                                <img src="src/images/extend-down.svg" alt="Extend Down Icon" class="extend-down-icon">
                            </div>
                            <div class="quarterly-report-title">
                                <div class="quarter-number" id="">First Quarter</div>
                                <div class="month-name">JANUARY - MARCH</div>
                            </div>
                        </div>
                        <div class="total-diagnosis-box">
                            <div class="total-diagnosis-box-text">
                                <div class="total-number" style="font-size: 35px;">35</div>
                                <div class="total-sub-text" style="font-size: 10px;">DIAGNOSIS</div>
                            </div>
                        </div>
                </div>

                <div class="quarterly-report-alter collapsed">
                    <div class="alter-report-content">
                    <div class="alter-first-row">
                        <div class="alter-report-header"> 
                                <div class="alter-header-content">
                                    <div class="extended-down-icon" onclick="toggleQuarter('firstQuarter')">
                                    <img src="src/images/extended-down.svg" alt="Extended Down Icon" class="extended-down-icon">
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
                                    <div class="leading-diagnosis-text" style="font-size: 35px;">LEADING DIAGNOSIS</div>
                                    <div class="leading-diagnosis-subtext" style="font-size: 10px;">MOST COMMON MEDICAL CONDITION FOR THE QUARTER</div>
                                </div>
                            </div>

                            <div class="total-diagnosis-box" style="background-color: #E13F3D;">
                                <div class="total-diagnosis-box-text" style="color: white;">
                                    <div class="total-number" style="font-size: 35px;">35</div>
                                    <div class="total-sub-text" style="font-size: 10px;">DIAGNOSIS</div>
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
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">18</div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 1</div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">February</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">7</div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 2</div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">March</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">10</div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 3</div>
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
                        <div class="row-first-content">
                            <div class="extend-down-icon" onclick="toggleQuarter('secondQuarter')">
                                <img src="src/images/extend-down.svg" alt="Extend Down Icon" class="extend-down-icon">
                            </div>
                            <div class="quarterly-report-title">
                                <div class="quarter-number" id="">Second Quarter</div>
                                <div class="month-name">APRIL - JUNE</div>
                            </div>
                        </div>
                        <div class="total-diagnosis-box">
                            <div class="total-diagnosis-box-text">
                                <div class="total-number" style="font-size: 35px;">35</div>
                                <div class="total-sub-text" style="font-size: 10px;">DIAGNOSIS</div>
                            </div>
                        </div>
                </div>

                <div class="quarterly-report-alter collapsed">
                    <div class="alter-report-content">
                    <div class="alter-first-row">
                        <div class="alter-report-header"> 
                                <div class="alter-header-content">
                                    <div class="extended-down-icon" onclick="toggleQuarter('secondQuarter')">
                                    <img src="src/images/extended-down.svg" alt="Extended Down Icon" class="extended-down-icon">
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
                                    <div class="leading-diagnosis-text" style="font-size: 35px;">LEADING DIAGNOSIS</div>
                                    <div class="leading-diagnosis-subtext" style="font-size: 10px;">MOST COMMON MEDICAL CONDITION FOR THE QUARTER</div>
                                </div>
                            </div>

                            <div class="total-diagnosis-box" style="background-color: #E13F3D;">
                                <div class="total-diagnosis-box-text" style="color: white;">
                                    <div class="total-number" style="font-size: 35px;">35</div>
                                    <div class="total-sub-text" style="font-size: 10px;">DIAGNOSIS</div>
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
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">18</div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 1</div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">May</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">7</div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 2</div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">June</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">10</div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 3</div>
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
                        <div class="row-first-content">
                            <div class="extend-down-icon" onclick="toggleQuarter('thirdQuarter')">
                                <img src="src/images/extend-down.svg" alt="Extend Down Icon" class="extend-down-icon">
                            </div>
                            <div class="quarterly-report-title">
                                <div class="quarter-number" id="">Third Quarter</div>
                                <div class="month-name">JULY - SEPTEMBER</div>
                            </div>
                        </div>
                        <div class="total-diagnosis-box">
                            <div class="total-diagnosis-box-text">
                                <div class="total-number" style="font-size: 35px;">35</div>
                                <div class="total-sub-text" style="font-size: 10px;">DIAGNOSIS</div>
                            </div>
                        </div>
                </div>

                <div class="quarterly-report-alter collapsed">
                    <div class="alter-report-content">
                    <div class="alter-first-row">
                        <div class="alter-report-header"> 
                                <div class="alter-header-content">
                                    <div class="extended-down-icon" onclick="toggleQuarter('thirdQuarter')">
                                    <img src="src/images/extended-down.svg" alt="Extended Down Icon" class="extended-down-icon">
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
                                    <div class="leading-diagnosis-text" style="font-size: 35px;">LEADING DIAGNOSIS</div>
                                    <div class="leading-diagnosis-subtext" style="font-size: 10px;">MOST COMMON MEDICAL CONDITION FOR THE QUARTER</div>
                                </div>
                            </div>

                            <div class="total-diagnosis-box" style="background-color: #E13F3D;">
                                <div class="total-diagnosis-box-text" style="color: white;">
                                    <div class="total-number" style="font-size: 35px;">35</div>
                                    <div class="total-sub-text" style="font-size: 10px;">DIAGNOSIS</div>
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
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">18</div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 1</div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">August</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">7</div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 2</div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">September</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">10</div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 3</div>
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
                        <div class="row-first-content">
                            <div class="extend-down-icon" onclick="toggleQuarter('fourthQuarter')">
                                <img src="src/images/extend-down.svg" alt="Extend Down Icon" class="extend-down-icon">
                            </div>
                            <div class="quarterly-report-title">
                                <div class="quarter-number" id="">Fourth Quarter</div>
                                <div class="month-name">OCTOBER - DECEMBER</div>
                            </div>
                        </div>
                        <div class="total-diagnosis-box">
                            <div class="total-diagnosis-box-text">
                                <div class="total-number" style="font-size: 35px;">35</div>
                                <div class="total-sub-text" style="font-size: 10px;">DIAGNOSIS</div>
                            </div>
                        </div>
                </div>

                <div class="quarterly-report-alter collapsed">
                    <div class="alter-report-content">
                    <div class="alter-first-row">
                        <div class="alter-report-header"> 
                                <div class="alter-header-content">
                                    <div class="extended-down-icon" onclick="toggleQuarter('fourthQuarter')">
                                    <img src="src/images/extended-down.svg" alt="Extended Down Icon" class="extended-down-icon">
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
                                    <div class="leading-diagnosis-text" style="font-size: 35px;">LEADING DIAGNOSIS</div>
                                    <div class="leading-diagnosis-subtext" style="font-size: 10px;">MOST COMMON MEDICAL CONDITION FOR THE QUARTER</div>
                                </div>
                            </div>

                            <div class="total-diagnosis-box" style="background-color: #E13F3D;">
                                <div class="total-diagnosis-box-text" style="color: white;">
                                    <div class="total-number" style="font-size: 35px;">35</div>
                                    <div class="total-sub-text" style="font-size: 10px;">DIAGNOSIS</div>
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
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">18</div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 1</div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">November</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">7</div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 2</div>
                                </div>

                                <div class="alter-third-row-result">
                                    <div class="alter-month" style="font-size: 25px; font-weight: bold;">December</div>
                                    <div class="alter-count" style="font-size: 15px; font-weight: 500;">10</div>
                                    <div class="alter-diagnosis" style="font-size: 15px; font-weight: 500;">Diagnosis 3</div>
                                </div>
                        </div>
                    </div>


                    </div>
                </div>
            </div>
 
        </div>
    </div>
</div>

    <?php
    include ('src/includes/footer.php');
    ?>
        <script src="vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="src/scripts/script.js"></script>
</body>
</html>

<?php
require_once('src/includes/session-nurse.php');
require_once('src/includes/connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Patients</title>
    <link rel="icon" type="image/png" href="src/images/sicko-logo.png"> 
    <link rel="stylesheet" href="src/styles/dboardStyle.css">
</head>
<body>
    <div class="overlay" id="overlay"></div>

<?php
    include ('src/includes/sidebar.php');
    ?>

    <div class="content" id="content">
    <div class="left-header" style="margin-top: 40px;">
        <p>
            <span style="color: #E13F3D;">List of</span>
            <span style="color: #058789;">Patients</span>
        </p>
    </div>

    <!-- Table Container -->
        <div class="table-container" id="">
            <table class="dashboard-table" style="margin-bottom: 80px;">
                <tr>
                    <th>Patient Name</th>
                    <th>Course</th>
                    <th>Section</th>
                    <th>Gender</th>
                </tr>
                <tr>
                    <td class="nameColumn" onclick="redirectToInfoPage()">Apolo L. Trasmonte</td>
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
                            <button class="delete-button">
                                <img src="src/images/trash-icon.svg" alt="Delete Icon" class="delete-icon">
                                Delete
                            </button>

                            <!-- Sorting and Pagination Container -->
                            <div class="sorting-pagination-container">
                                <!-- Sorting button box -->
                                <div class="sorting-button-box" id="sortingButtonBox">
                                    <!-- Sort text -->
                                    Sort by:
                                    <select id="sortCriteria" style="font-family: 'Poppins', sans-serif; font-weight: bold;">
                                        <option value="name">Name</option>
                                        <option value="course">Course</option>
                                        <option value="section">Section</option>
                                        <option value="gender">Gender</option>
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
    </div>
</div>


    <?php
    include ('src/includes/footer.php');
    ?>
    <script src="src/scripts/script.js"></script>
</body>
</html>

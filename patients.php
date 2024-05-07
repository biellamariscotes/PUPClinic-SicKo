<?php
require_once('src/includes/session-nurse.php');
require_once('src/includes/connect.php');

$sql = "SELECT * FROM patient";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Patients</title>
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
    include ('src/includes/sidebar/patients.php');
    ?>

                <!-- Log Out Modal -->
                <div class="modal" id="logOut" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <!-- Modal content -->
                            <div class="modal-middle-icon">
                                <i class="bi bi-box-arrow-right" style="color:#058789; font-size:5rem"></i>
                            </div>
                            <div class="modal-title" style="color: black;">Are you leaving?</div>
                            <div class="modal-subtitle" style="justify-content: center; ">Are you sure you want to log out?</div>
                        </div>
                        <div class="modal-buttons">
                            <button type="button" class="btn btn-secondary" id="logout-close-modal" data-dismiss="modal" style="background-color: #777777; 
                            font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-right: 1.25rem;">Cancel</button>
                            <button type="button" class="btn btn-secondary" id="logout-confirm-button" style="background-color: #058789; 
                            font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem;">Log Out</button>
                        </div>
                    </div>
                </div>
            </div>

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
                    <?php
                    // Check if there are any records
                    if (mysqli_num_rows($result) > 0) {
                        // Output data of each row
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td class='nameColumn'><a href='patients-treatment-record.php?patient_id=" . $row["patient_id"] . "'>" . $row["first_name"] . " " . $row["last_name"] . "</a></td>";
                            echo "<td>" . $row["course"] . "</td>";
                            echo "<td>" . $row["section"] . "</td>";
                            echo "<td>" . $row["gender"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No records found</td></tr>";
                    }
                    ?>
                <tr>
                    <td colspan="4" style="height: 97px;"> <!-- Use colspan to span across all columns -->

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
    <script src="vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="src/scripts/script.js"></script>
    <script>
        $(document).ready(function () {
            // Show Modal when Log Out menu item is clicked
            $("#logout-menu-item").click(function (event) {
                $("#logOut").modal("show");
            });

            // Close the Modal with the close button
                $("#logout-close-modal").click(function (event) {
                $("#logOut").modal("hide");
            });

            // Handle logout when Log Out button on modal is clicked
            $("#logout-confirm-button").click(function (event) {
                // Perform logout action
                window.location.href = "logout.php";
            });
        });
    </script>
</body>
</html>

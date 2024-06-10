<?php
// Include necessary files and establish database connection
require_once('includes/session-nurse.php');
require_once('includes/connect.php');

// Check if the form is submitted for deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmDeleteButton'])) {
    if (!empty($_POST['delete_patient'])) {
        foreach ($_POST['delete_patient'] as $patient_id) {
            // Prepare a delete statement for treatment records
            $sql_treatment = "DELETE FROM treatment_record WHERE patient_id = ?";
            if ($stmt_treatment = mysqli_prepare($conn, $sql_treatment)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt_treatment, "i", $patient_id);

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt_treatment)) {
                    // Successfully deleted treatment records, now delete patient record
                    $sql_patient = "DELETE FROM patient WHERE patient_id = ?";
                    if ($stmt_patient = mysqli_prepare($conn, $sql_patient)) {
                        mysqli_stmt_bind_param($stmt_patient, "i", $patient_id);

                        if (mysqli_stmt_execute($stmt_patient)) {
                            // Deletion successful
                            echo "Record deleted successfully: Patient ID " . $patient_id . "<br>";
                        } else {
                            // Error handling
                            echo "Error deleting patient record: " . mysqli_stmt_error($stmt_patient) . "<br>";
                        }

                        // Close statement
                        mysqli_stmt_close($stmt_patient);
                    } else {
                        echo "Error preparing statement: " . mysqli_error($conn) . "<br>";
                    }
                } else {
                    // Error handling
                    echo "Error deleting treatment records: " . mysqli_stmt_error($stmt_treatment) . "<br>";
                }

                // Close statement
                mysqli_stmt_close($stmt_treatment);
            } else {
                echo "Error preparing statement: " . mysqli_error($conn) . "<br>";
            }
        }
        // Redirect to the same page to reflect changes
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

$recordsPerPage = 5;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $recordsPerPage;

$totalRecordsQuery = "SELECT COUNT(*) AS total FROM patient";
$totalRecordsResult = mysqli_query($conn, $totalRecordsQuery);
$totalRecordsRow = mysqli_fetch_assoc($totalRecordsResult);
$totalRecords = $totalRecordsRow['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Sorting criteria
$sortCriteria = isset($_GET['sort']) ? $_GET['sort'] : 'first_name'; // Default sorting by name if not specified
// SQL query with sorting
$query = "SELECT * FROM patient ORDER BY $sortCriteria LIMIT $offset, $recordsPerPage";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients</title>
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

    .checkbox-align {
        /*vertical-align: middle;  Adjust the alignment */
        margin-left: 20px;
        margin-bottom: -19px; /* Adjust this value to fine-tune the alignment */
    }
</style>

<body>
    <div class="loader">
        <img src="images/loader.gif">
    </div>

    <div class="overlay" id="overlay"></div>

    <div class="main-content">
        <?php
        include('includes/sidebar/patients.php');
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
                <form id="deleteForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
                                echo "<td class='nameColumn'>";
                                echo "<input type='checkbox' class='checkbox-align' name='delete_patient[]' value='" . $row["patient_id"] . "' style='display:none;'>"; // Checkbox initially hidden
                                echo "<a href='patients-treatment-record.php?patient_id=" . $row["patient_id"] . "'>" . $row["first_name"] . " " . $row["last_name"] . "</a></td>"; // Patient name
                                echo "<td>" . $row["course"] . "</td>";
                                echo "<td>" . $row["section"] . "</td>";
                                echo "<td>" . $row["sex"] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No records found</td></tr>";
                        }
                        ?>
                        <tr>
                            <td colspan="5" style="height: 97px;"> <!-- Use colspan to span across all columns -->

                                <!-- Inside the table button container -->
                                <div class="table-button-container" id="tableButtonContainer">
                                    <!-- Delete button with toggle for checkboxes -->
                                    <button class="delete-button" id="toggleCheckboxButton" type="button">
                                        <img src="images/trash-icon.svg" alt="Delete Icon" class="delete-icon">
                                        Delete Records
                                    </button>
                                    <!-- Sorting and Pagination Container -->
                                    <div class="sorting-pagination-container">
                                        <!-- Sorting button box -->
                                        <div class="sorting-button-box" id="sortingButtonBox">
                                            <!-- Sort text -->
                                            Sort by:
                                            <select id="sortCriteria" style="font-family: 'Poppins', sans-serif; font-weight: bold;">
                                                <option value="first_name">Name</option>
                                                <option value="course">Course</option>
                                                <option value="section">Section</option>
                                                <option value="sex">Gender</option>
                                            </select>
                                        </div>
                                        <!-- Pagination buttons -->
                                        <div class="pagination-buttons">
                                            <!-- Previous button -->
                                            <a href="?page=<?php echo max(1, $currentPage - 1); ?>" class="pagination-button <?php echo ($currentPage == 1) ? 'disabled' : ''; ?>">
                                                &lt;
                                            </a>

                                            <!-- Next button -->
                                            <a href="?page=<?php echo min($totalPages, $currentPage + 1); ?>" class="pagination-button <?php echo ($currentPage == $totalPages || $totalRecords == 0) ? 'disabled' : ''; ?>">
                                                &gt;
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="confirmDeleteButton" value="1">
                </form>
            </div>
        </div>

        <!-- Modal HTML -->
        <div id="confirmationModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>Are you sure you want to delete the selected patients?</p>
                <div class="button-container">
                    <button type="button" class="delete-button" id="confirmDeleteButton">Yes, Delete</button>
                    <button type="button" class="cancel-button close">Cancel</button>
                </div>
            </div>
        </div>

        <?php
        include('includes/footer.php');
        ?>
    </div>
    <script src="../vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/script.js"></script>
    <script src="scripts/loader.js"></script>

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

        // Function to show the modal
        function showModal() {
            document.getElementById('confirmationModal').style.display = 'block';
        }

        // Function to hide the modal
        function hideModal() {
            document.getElementById('confirmationModal').style.display = 'none';
        }

        // Create Delete Selected button
        document.getElementById('toggleCheckboxButton').addEventListener('click', function () {
            var checkboxes = document.getElementsByName('delete_patient[]');
            var buttonContainer = document.getElementById('tableButtonContainer');
            var deleteButton = document.getElementById('toggleCheckboxButton');
            var deleteSelectedButton = document.createElement('button');
            var cancelButton = document.createElement('button');

            deleteSelectedButton.textContent = 'Delete Selected';
            deleteSelectedButton.classList.add('delete-button');
            deleteSelectedButton.id = 'deleteSelectedButton';
            deleteSelectedButton.type = 'button';
            deleteSelectedButton.addEventListener('click', function () {
                showModal();
            });

            cancelButton.textContent = 'Cancel';
            cancelButton.classList.add('delete-button');
            cancelButton.id = 'cancelButton';
            cancelButton.type = 'button';
            cancelButton.addEventListener('click', function () {
                for (var i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].style.display = 'none';
                    checkboxes[i].checked = false;
                }
                deleteSelectedButton.remove();
                cancelButton.remove();
                deleteButton.style.display = 'block';
            });

            deleteButton.style.display = 'none';
            buttonContainer.insertBefore(deleteSelectedButton, deleteButton.nextSibling);
            buttonContainer.insertBefore(document.createTextNode(' '), deleteButton.nextSibling);
            buttonContainer.insertBefore(cancelButton, deleteButton.nextSibling);

            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].style.display = 'block';
            }
        });

        document.getElementsByClassName('close')[0].addEventListener('click', function () {
            hideModal();
        });

        window.onclick = function (event) {
            var modal = document.getElementById('confirmationModal');
            if (event.target == modal) {
                hideModal();
            }
        }

        document.getElementById('confirmDeleteButton').addEventListener('click', function () {
            document.getElementById('deleteForm').submit();
        });

        function handleSort() {
            var sortCriteria = document.getElementById('sortCriteria').value;
            window.location.href = window.location.pathname + '?sort=' + sortCriteria;
        }

        document.getElementById('sortCriteria').addEventListener('change', handleSort);

        function getQueryVariable(variable) {
            var query = window.location.search.substring(1);
            var vars = query.split("&");
            for (var i = 0; i < vars.length; i++) {
                var pair = vars[i].split("=");
                if (pair[0] === variable) {
                    return pair[1];
                }
            }
            return false;
        }

        var currentSortCriteria = getQueryVariable('sort');
        if (currentSortCriteria) {
            document.getElementById('sortCriteria').value = currentSortCriteria;
        }
    </script>
</body>

</html>

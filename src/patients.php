<?php
// Include necessary files and establish database connection
require_once('includes/session-nurse.php');
require_once('includes/connect.php');

// Check if the form is submitted for deletion and if the deletion was successful
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmDeleteButton'])) {
    if (!empty($_POST['delete_patient'])) {
        $deletionSuccessful = true; // Flag to indicate successful deletion
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
                            $deletionSuccessful = false; // Set flag to false if deletion fails
                            echo "Error deleting patient record: " . mysqli_stmt_error($stmt_patient) . "<br>";
                        }

                        // Close statement
                        mysqli_stmt_close($stmt_patient);
                    } else {
                        echo "Error preparing statement: " . mysqli_error($conn) . "<br>";
                        $deletionSuccessful = false; // Set flag to false if preparation fails
                    }
                } else {
                    // Error handling
                    $deletionSuccessful = false; // Set flag to false if execution fails
                    echo "Error deleting treatment records: " . mysqli_stmt_error($stmt_treatment) . "<br>";
                }

                // Close statement
                mysqli_stmt_close($stmt_treatment);
            } else {
                echo "Error preparing statement: " . mysqli_error($conn) . "<br>";
                $deletionSuccessful = false; // Set flag to false if preparation fails
            }
        }
        // Redirect to the same page to reflect changes
        if ($deletionSuccessful) {
            // Show the delete successful modal if deletion was successful
            echo "<script>$(document).ready(function() { $('#delete-successful-modal').modal('show'); });</script>";
        }
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
    .cancel-button {
        font-family: 'Poppins';
        font-weight: 600;
        background: #D4D4D4;
        color: black;
    }

    .dashboard-table tbody tr {
        border-bottom: 1px solid #D3D3D3; 
    }

    .dashboard-table tbody tr:last-child {
        border-bottom: 1px solid #D3D3D3; 
    }

    .dashboard-table th {
        padding: 0px;
    }

    .delete-records-link.disabled {
        opacity: 0.5; /* Reduce opacity to visually indicate it's disabled */
        cursor: not-allowed; /* Change cursor to indicate it's not clickable */
        color: #ccc; /* Adjust color to visually indicate disabled state */
        pointer-events: none; /* Disable pointer events to prevent clicks */
    }

    .vertical-bar {
    display: inline-block;
    vertical-align: middle;
    margin: 0 10px; /* Adjust the margin as needed */
    }

    #delete-selected-link,
    #cancel-delete-link {
        margin-left: 0;
    }

    .button-group span {
        display: inline-block;
        vertical-align: middle;
    }

    .delete-cancel-container {
        display: flex;
        justify-content: flex-start;
    }

    .delete-records-link {
        cursor: pointer;
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

        <!-- Delete Modal -->
        <div class="modal" id="deleteModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-middle-icon">
                            <i class="bi bi-trash-fill" style="color:#E13F3D; font-size:5rem"></i>
                        </div>
                        <div class="modal-title" style="color: black;">Confirm Delete?</div>
                        <div class="modal-subtitle" style="justify-content: center; ">Are you sure you want to delete the selected patient?</div>
                    </div>
                    <div class="modal-buttons" style="padding-bottom: 2rem;">
                        <button type="button" class="btn btn-secondary" id="cancel-delete-modal" data-bs-dismiss="modal" style="background-color: #777777; 
                        font-family: 'Poppins'; font-weight: 600; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-right: 1.25rem;">Cancel</button>
                        <button type="button" class="btn btn-secondary" id="confirmDeleteButton" style="background-color: #E13F3D; 
                        font-family: 'Poppins'; font-weight: 600; padding: 0.070rem 1.25rem 0.070rem 1.25rem;">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Successful Modal 
        <div class="modal" id="delete-successful-modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-middle-icon">
                            <img src="images/check.gif" style="width: 10rem; height: auto;" alt="Check Icon">
                        </div>
                        <div class="modal-title" style="color: black;">Deleted Successfully</div>
                        <div class="modal-subtitle" style="text-wrap: pretty; justify-content: center;">Selected patients has been deleted successfully.</div>
                    </div>
                    <div class="modal-buttons">
                        <button type="button" class="btn btn-secondary" id="delete-successful-close-modal" data-dismiss="modal"
                            style="background-color: #23B26D; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-top: 1rem;">Close</button>
                    </div>
                </div>
            </div>
        </div>
        -->

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
                                echo "<input type='checkbox' name='delete_patient[]' value='" . $row["patient_id"] . "' style='display:none;'>"; // Checkbox initially hidden
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
                            <td colspan="5" style="height: 97px; background-color: white;"> <!-- Use colspan to span across all columns -->

                                <!-- Inside the table button container -->
                                <div class="table-button-container" id="tableButtonContainer">

                                    <!-- Separate container for delete selected and cancel buttons -->
                                    <div class="delete-cancel-container" style="display: none;">
                                        <span class="delete-records-link" id="delete-selected-link" style="color: #D22B2B; margin-right: 10px;" onclick="$('#confirmDeleteModal').modal('show');">Delete Selected</span>
                                        <span class="vertical-bar">|</span>
                                        <span class="delete-records-link" id="cancel-delete-link" style="margin-left: 0;" onclick="cancelDeleteMode()">Cancel</span>
                                    </div>

                                    <!-- Delete button with toggle for checkboxes -->
                                    <div class="button-group" style="justify-content: space-between; gap: 35rem;">
                                        <span class="delete-records-link" id="delete-toggle-link" onclick="toggleDeleteMode()" style="color: #D22B2B;">
                                            <i class="bi bi-trash" style="color: #D22B2B; font-size: 1rem; margin-right: 0.625rem; vertical-align: middle;"></i>
                                            Delete Patients
                                        </span>

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
                                </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="confirmDeleteButton" value="1">
                </form>
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
    // Function to handle deletion confirmation
    $('#confirmDeleteButton').click(function () {
        $('#deleteModal').modal('hide'); // Hide delete confirmation modal
        $('#delete-successful-modal').modal('show'); // Show delete successful modal
    });

    // Function to handle closing of delete successful modal
    $('#delete-successful-close-modal').click(function () {
        // Hide delete successful modal
        $('#delete-successful-modal').modal('hide');
    });

    // Function to handle form submission when closing delete successful modal
    $('#delete-successful-close-modal').click(function () {
        // Delay form submission by 1 second (1000 milliseconds)
        setTimeout(function () {
            $('#deleteForm').submit(); // Submit the form
        }, 4000); // Adjust the delay as needed
    });

    // Function to handle sorting
    function handleSort() {
        var sortCriteria = $('#sortCriteria').val();
        window.location.href = window.location.pathname + '?sort=' + sortCriteria;
    }

    $('#sortCriteria').change(handleSort);

    // Function to get query variable from URL
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

    // Set current sort criteria in the dropdown
    var currentSortCriteria = getQueryVariable('sort');
    if (currentSortCriteria) {
        $('#sortCriteria').val(currentSortCriteria);
    }

    // Function to update the "Delete Selected" button visibility
    function updateDeleteButton() {
        var checkboxes = $('input[name="delete_patient[]"]');
        var deleteSelectedLink = $('#delete-selected-link');

        // Check if any checkboxes are checked
        if (checkboxes.filter(':checked').length > 0) {
            deleteSelectedLink.removeClass('disabled'); // Enable the button
        } else {
            deleteSelectedLink.addClass('disabled'); // Disable the button
        }
    }

    // Initial check when document is ready
    updateDeleteButton();

    // Event handler for checkbox change
    $('input[name="delete_patient[]"]').change(updateDeleteButton);

    // Function to toggle delete mode
    function toggleDeleteMode() {
        var checkboxes = $('input[name="delete_patient[]"]');
        var deleteSelectedLink = $('#delete-selected-link');
        var cancelDeleteLink = $('#cancel-delete-link');
        var deleteToggleLink = $('#delete-toggle-link');
        var verticalBar = $('.vertical-bar'); // Reference to the vertical bar
        var deleteCancelContainer = $('.delete-cancel-container'); // Reference to the new container

        checkboxes.toggle(); // Show or hide checkboxes
        deleteCancelContainer.toggle(); // Show or hide the delete-cancel-container
        deleteToggleLink.toggle(); // Hide or show "Delete Records" link

        // Show the sorting-pagination-container when cancel button is clicked
        if (cancelDeleteLink.is(':visible')) {
            $('.sorting-pagination-container').hide();
        } else {
            $('.sorting-pagination-container').show();
        }
    }

    // Function to cancel delete mode
    function cancelDeleteMode() {
        var checkboxes = $('input[name="delete_patient[]"]');
        var deleteSelectedLink = $('#delete-selected-link');
        var cancelDeleteLink = $('#cancel-delete-link');
        var deleteToggleLink = $('#delete-toggle-link');
        var verticalBar = $('.vertical-bar'); // Reference to the vertical bar
        var deleteCancelContainer = $('.delete-cancel-container'); // Reference to the new container

        checkboxes.hide().prop('checked', false); // Hide checkboxes and uncheck them
        deleteCancelContainer.hide(); // Hide the delete-cancel-container
        deleteToggleLink.show(); // Show "Delete Records" link

        $('.sorting-pagination-container').show(); // Show the sorting-pagination-container
    }

    // Function to show delete confirmation modal
    function showDeleteModal() {
        $('#deleteModal').modal('show');
    }

    // Event listeners
    $('#delete-toggle-link').click(toggleDeleteMode);
    $('#cancel-delete-link').click(cancelDeleteMode);
    $('#delete-selected-link').click(showDeleteModal);
    $('#confirmDeleteButton').click(function () {
        $('#deleteForm').submit();
    });
    $('#delete-successful-close-modal').click(function () {
        $('#delete-successful-modal').modal('hide');
        $('#deleteForm').submit();
    });
});
</script>

</body>

</html>
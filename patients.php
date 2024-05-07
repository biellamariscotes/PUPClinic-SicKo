<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Patients</title>
    <link rel="icon" type="image/png" href="src/images/sicko-logo.png"> 
    <link rel="stylesheet" href="src/styles/dboardStyle.css">
<style>
    .button-container {
        display: flex;
        align-items: center;
    }

    .sorting-pagination-container {
        display: flex;
        align-items: center;
        margin-left: auto; /* Move to the right */
    }

    .sorting-button-box {
        margin-right: 20px; /* Add margin to separate from the buttons */
    }

    .delete-button {
        margin-right: 10px; /* Add space between buttons */
        padding: 8px 20px; /* Resize buttons */
        background-color: #E13F3D; /* Change background color */
        color: white; /* Change text color */
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .cancel-button {
        background-color: #058789; /* Change background color */
        color: white; /* Change text color */
        border: none;
        border-radius: 5px;
        padding: 8px 20px; /* Resize buttons */
        cursor: pointer;
    }

        /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .button-container {
        text-align: center;
        margin-top: 20px;
    }

</style>

</head>

<body>
    <div class="overlay" id="overlay"></div>

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Patients</title>
    <link rel="icon" type="image/png" href="src/images/sicko-logo.png"> 
    <link rel="stylesheet" href="src/styles/dboardStyle.css">
    <style>
        /* Your CSS styles */
    </style>
</head>
<body>
    <div class="overlay" id="overlay"></div>

    <?php
    // Include necessary files and establish database connection
    require_once('src/includes/session-nurse.php');
    require_once('src/includes/connect.php');

    // Check if the form is submitted for deletion
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmDeleteButton'])) {
        if (!empty($_POST['delete_patient'])) {
            foreach ($_POST['delete_patient'] as $patient_id) {
                // Prepare a delete statement
                $sql = "DELETE FROM patient WHERE patient_id = ?";
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "i", $param_patient_id);
                    
                    // Set parameters
                    $param_patient_id = $patient_id;
                    
                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        // Deletion successful
                    } else {
                        // Error handling
                        echo "Error deleting record: " . mysqli_error($conn);
                    }

                    // Close statement
                    mysqli_stmt_close($stmt);
                }
            }
            // Redirect to the same page to reflect changes
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        }
    }

    // Sorting criteria
    $sortCriteria = isset($_GET['sort']) ? $_GET['sort'] : 'first_name'; // Default sorting by name if not specified
    // SQL query with sorting
    $sql = "SELECT * FROM patient ORDER BY $sortCriteria";
    // Execute the query
    $result = mysqli_query($conn, $sql);
?>

<?php
    include ('src/includes/sidebar/patients.php');
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
                echo "<tr><td colspan='5'>No records found</td></tr>";
            }
            ?>
            <tr>
                <td colspan="5" style="height: 97px;"> <!-- Use colspan to span across all columns -->

                    <!-- Inside the table button container -->
                    <div class="table-button-container" id="tableButtonContainer">
                        <!-- Delete button with toggle for checkboxes -->
                        <button class="delete-button" id="toggleCheckboxButton">
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
                                    <option value="first_name">Name</option>
                                    <option value="course">Course</option>
                                    <option value="section">Section</option>
                                    <option value="sex">Gender</option>
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

<!-- Modal HTML -->
<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>Are you sure you want to delete the selected patients?</p>
        <div class="button-container">
            <!-- Change the form action to the current page -->
            <form id="deleteForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <!-- Add a hidden input field to pass the confirm delete action -->
                <input type="hidden" name="confirmDelete" value="true">
                <button type="submit" class="delete-button" name="confirmDeleteButton">Yes, Delete</button>
                <button type="button" class="cancel-button close">Cancel</button>
            </form>
        </div>
    </div>
</div>

<?php
include ('src/includes/footer.php');
?>
<script src="src/scripts/script.js"></script>

<script>
    // Function to show the modal
    function showModal() {
        document.getElementById('confirmationModal').style.display = 'block';
    }

    // Function to hide the modal
    function hideModal() {
        document.getElementById('confirmationModal').style.display = 'none';
    }

    // Create Delete Selected button
    document.getElementById('toggleCheckboxButton').addEventListener('click', function() {
        var checkboxes = document.getElementsByName('delete_patient[]');
        var buttonContainer = document.getElementById('tableButtonContainer');
        var deleteButton = document.getElementById('toggleCheckboxButton');
        var deleteSelectedButton = document.createElement('button');
        var cancelButton = document.createElement('button');

        // Create Delete Selected button
        deleteSelectedButton.textContent = 'Delete Selected';
        deleteSelectedButton.classList.add('delete-button');
        deleteSelectedButton.id = 'deleteSelectedButton';
        deleteSelectedButton.addEventListener('click', function() {
            // Prompt a confirmation dialog
            showModal();
        });

        // Create Cancel button
        cancelButton.textContent = 'Cancel';
        cancelButton.classList.add('delete-button');
        cancelButton.id = 'cancelButton';
        cancelButton.addEventListener('click', function() {
            // Hide checkboxes and show Delete button
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].style.display = 'none';
            }
            deleteSelectedButton.remove();
            cancelButton.remove();
            deleteButton.style.display = 'block';
        });

        // Hide Delete button and show Delete Selected and Cancel buttons
        deleteButton.style.display = 'none';
        buttonContainer.insertBefore(deleteSelectedButton, deleteButton.nextSibling);
        buttonContainer.insertBefore(document.createTextNode(' '), deleteButton.nextSibling); // Add space
        buttonContainer.insertBefore(cancelButton, deleteButton.nextSibling);

        // Show checkboxes
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].style.display = 'block';
        }
    });

    // Close the modal when the close button is clicked
    document.getElementsByClassName('close')[0].addEventListener('click', function() {
        hideModal();
    });

    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        var modal = document.getElementById('confirmationModal');
        if (event.target == modal) {
            hideModal();
        }
    }

     // Function to handle sorting
     function handleSort() {
        var sortCriteria = document.getElementById('sortCriteria').value;
        // Redirect to the same page with the selected sorting criteria as a query parameter
        window.location.href = window.location.pathname + '?sort=' + sortCriteria;
    }

    // Add event listener for the sort criteria select element
    document.getElementById('sortCriteria').addEventListener('change', handleSort);

    // Function to extract query parameters from URL
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

    // Get the current sort criteria from URL parameter and select the corresponding option
    var currentSortCriteria = getQueryVariable('sort');
    if (currentSortCriteria) {
        document.getElementById('sortCriteria').value = currentSortCriteria;
    }

    // Function to handle form submission
    function handleFormSubmission(event) {
        // Prevent the default form submission behavior
        event.preventDefault();
        // Trigger the modal to confirm the deletion
        showModal();
    }

    // Add event listener for the form submission
    document.getElementById('deleteForm').addEventListener('submit', handleFormSubmission);

</script>



</body>
</html>

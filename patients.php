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
</body>
</html>

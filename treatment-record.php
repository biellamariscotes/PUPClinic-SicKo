<?php
require_once ('src/includes/session-nurse.php');
require_once ('src/includes/connect.php');

// NOT YET WORKING.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $symptoms = mysqli_real_escape_string($conn, $_POST['symptoms']);
    $diagnosis = mysqli_real_escape_string($conn, $_POST['diagnosis']);
    $treatments = mysqli_real_escape_string($conn, $_POST['treatments']);

    $sql = "INSERT INTO treatment_record (patient_id, full_name, gender, age, course, section, symptoms, diagnosis, treatments) 
            VALUES ('$patient_id', '$full_name', '$gender', '$age', '$course', '$section', '$symptoms', '$diagnosis', '$treatments')";

    if (mysqli_query($conn, $sql)) {
        header("Location: treatment-record-confirmation.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    $query_params = http_build_query([
        'patient_id' => $patient_id,
        'full_name' => $full_name,
        'gender' => $gender,
        'age' => $age,
        'course' => $course,
        'section' => $section,
        'symptoms' => $symptoms,
        'diagnosis' => $diagnosis,
        'treatments' => $treatments
    ]);
    header("Location: treatment-record-confirmation.php?$query_params");
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Treatment Record</title>
    <link rel="icon" type="image/png" href="src/images/sicko-logo.png">
    <link rel="stylesheet" href="src/styles/dboardStyle.css">
    <link rel="stylesheet" href="src/styles/modals.css">
    <link rel="stylesheet" href="vendors/bootstrap-5.0.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>

<!-- <style>
    #search-results {
    position: absolute;
    top: calc(100% + 5px);
    width: 30%;
    background-color: #fff;
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
    z-index: 9999; /* Ensure the dropdown appears above other elements */
    }

    .input-row {
        position: relative; /* Ensure relative positioning for the parent */
    }

    /* Adjust input field position */
    .input-row input[type="text"],
    .input-row input[type="number"],
    .input-row select {
        width: calc(100% - 30px); /* Adjust the width to accommodate the dropdown */
        padding-right: 30px; /* Space for dropdown icon */
    }

</style> -->

<body>
    <div class="overlay" id="overlay"></div>

    <?php
    include ('src/includes/sidebar/patients-treatment-record.php');
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

                <!-- Submit Form Modal -->
                <div class="modal" id="confirmModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                            <div class="modal-body">
                                <div class="modal-middle-icon">
                                    <i class="bi bi-check-circle-fill" style="color:#058789; font-size:5rem"></i>
                                </div>
                                <div class="modal-title" style="color: black;">Confirmation</div>
                                <div class="modal-subtitle" style="justify-content: center; ">Are you sure you want to submit?</div>
                            </div>
                            <div class="modal-buttons">
                                <button type="button" class="btn btn-secondary" id="cancel-confirm-modal" data-dismiss="modal" style="background-color: #777777; 
                                font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-right: 1.25rem;">Cancel</button>
                                <button type="button" class="btn btn-secondary" id="submit-form-modal" data-dismiss="modal" style="background-color: #058789; 
                                font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem;">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>

    <div class="content" id="content">
        <div class="left-header">
            <p>
                <span style="color: #E13F3D;">Treatment</span>
                <span style="color: #058789;">Record</span>
            </p>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <form id="treatment-form" action="treatment-record.php" method="post">
                <div class="input-row">
                <input type="hidden" id="patient_id" name="patient_id">
                <input type="text" id="full-name" name="full_name" placeholder="Full Name" autocomplete="off" required onkeyup="searchPatients(this.value)">
                <div id="search-results"></div>
                    <select id="gender" name="gender" required>
                        <option value="" disabled selected hidden>Birth Sex</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                    <input type="number" name="age" id="age" placeholder="Age" maxlength="2" required>
                </div>
                <div class="input-row">
                    <input type="text" id="course" name="course" placeholder="Course/Organization" autocomplete="off" required>
                    <select id="section" name="section" required>
                        <option value="" disabled selected hidden>Block Section</option>
                        <option value="1-1">1-1</option>
                        <option value="1-2">1-2</option>
                        <option value="2-1">2-1</option>
                        <option value="2-2">2-2</option>
                        <option value="3-1">3-1</option>
                        <option value="3-2">3-2</option>
                        <option value="4-1">4-1</option>
                        <option value="4-2">4-2</option>
                    </select>
                </div>
                <div class="right-row">
                    <p class="bold" onclick="window.location.href='ai-basedSDT.php'">Use AI Symptoms Diagnostic Tool</p>
                </div>
                <div class="input-row">
                    <input type="text" id="symptoms" name="symptoms" placeholder="Symptoms" autocomplete="off" value="<?php echo isset($_GET['symptoms']) ? $_GET['symptoms'] : ''; ?>" required>
                </div>
                <div class="input-row">
                    <input type="text" id="diagnosis" name="diagnosis" placeholder="Diagnosis" autocomplete="off" value="<?php echo isset($_GET['diagnosis']) ? $_GET['diagnosis'] : ''; ?>" required>
                    <input type="text" id="treatments" name="treatments" placeholder="Treatments/Medicines" autocomplete="off" value="<?php echo isset($_GET['treatments']) ? $_GET['treatments'] : ''; ?>" required>
                </div>
                <div class="right-row">
                    <button type="submit" id="submit-form-button" name="record-btn">Submit Form</button>
                </div>
            </form>
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

    <script>
        $(document).ready(function () {
            // Show Modal when Submit button is clicked
            $("#submit-form-button").click(function (event) {
                event.preventDefault(); // Prevent default form submission
                $("#confirmModal").modal("show");
            });

            // Close the Modal with the close button
                $("#cancel-confirm-modal").click(function (event) {
                $("#confirmModal").modal("hide");
            });

            // Handle form submission when user confirms in the modal
            $("#submit-form-modal").click(function (event) {
                $("#treatment-form").submit(); // Submit the form
            });
        });
    </script>

    <script>
    // function searchPatients(keyword) {
    //     if(keyword.length > 0) {
    //         // Perform an AJAX request
    //         var xhttp = new XMLHttpRequest();
    //         xhttp.onreadystatechange = function() {
    //             if (this.readyState == 4 && this.status == 200) {
    //                 // Display search results in the search-results div
    //                 document.getElementById("search-results").innerHTML = this.responseText;
    //             }
    //         };
    //         xhttp.open("GET", "search-patients.php?keyword=" + keyword, true);
    //         xhttp.send();
    //     } else {
    //         document.getElementById("search-results").innerHTML = "";
    //     }
    // }

    function selectPatient(patient_id, fullName, gender, age, course, section) {
        document.getElementById("full-name").value = fullName;
        document.getElementById("gender").value = gender;
        document.getElementById("age").value = age;
        document.getElementById("course").value = course;
        document.getElementById("section").value = section;
        document.getElementById("patient_id").value = patient_id;
    }
    </script>

    <script>
        function validateForm() {
            // Validate Full Name
            var fullName = document.getElementById("full-name").value.trim();
            if (fullName.length === 0 || fullName.length > 30 || /^\s+$/.test(fullName)) {
                alert("Please enter a valid full name (up to 30 characters without leading or trailing spaces).");
                return false;
            }

            // Validate Age
            var age = document.getElementById("age").value.trim();
            if (!/^\d{2}$/.test(age) || parseInt(age) < 17 || parseInt(age) > 65) {
                alert("Please enter a valid age (between 17 and 65 years old).");
                return false;
            }

            return true; // Form is valid
        }

        // Add event listener to form submission
        document.getElementById("treatment-form").addEventListener("submit", function(event) {
            if (!validateForm()) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        });
    </script>

</body>

</html>
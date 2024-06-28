<?php
require_once('includes/session-nurse.php');
require_once('includes/connect.php');

date_default_timezone_set('Asia/Manila');
$current_date = date('Y-m-d H:i:s');

$symptoms = isset($_GET['symptoms']) ? htmlspecialchars($_GET['symptoms']) : '';
$diagnosis = isset($_GET['diagnosis']) ? htmlspecialchars($_GET['diagnosis']) : '';
$treatments = isset($_GET['treatments']) ? htmlspecialchars($_GET['treatments']) : '';

// Set aiToolUsed to true only if symptoms, diagnosis, and treatments are not empty
$aiToolUsed = !empty($symptoms) && !empty($diagnosis) && !empty($treatments);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate if patient_id is set
    if (isset($_POST['patient_id'])) {
        $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);

        // Check if the patient exists
        $check_patient_query = "SELECT * FROM patient WHERE patient_id = '$patient_id'";
        $result = mysqli_query($conn, $check_patient_query);

        if (mysqli_num_rows($result) == 0) {
            echo "Error: Patient does not exist!";
            exit();
        }

        // Retrieve and sanitize form data
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $sex = mysqli_real_escape_string($conn, $_POST['sex']);
        $age = mysqli_real_escape_string($conn, $_POST['age']);
        $course = mysqli_real_escape_string($conn, $_POST['course']);
        $section = mysqli_real_escape_string($conn, $_POST['section']);
        $symptoms = mysqli_real_escape_string($conn, $_POST['symptoms']);
        $diagnosis = mysqli_real_escape_string($conn, $_POST['diagnosis']);
        $treatments = mysqli_real_escape_string($conn, $_POST['treatments']);

        // Insert data into the database
        $sql = "INSERT INTO treatment_record (patient_id, full_name, sex, age, course, section, symptoms, diagnosis, treatments, date, excuse_letter, clearance_letter, referral_letter) 
                VALUES ('$patient_id', '$full_name', '$sex', '$age', '$course', '$section', '$symptoms','$diagnosis', '$treatments', '$current_date' 'No', 'No', 'No')";

        if (mysqli_query($conn, $sql)) {

            $record_id = mysqli_insert_id($conn);

            $url = "treatment-record-confirmation.php?";
            $url .= "patient_id=" . urlencode($patient_id) . "&";
            $url .= "full_name=" . urlencode($full_name) . "&";
            $url .= "sex=" . urlencode($sex) . "&";
            $url .= "age=" . urlencode($age) . "&";
            $url .= "course=" . urlencode($course) . "&";
            $url .= "section=" . urlencode($section) . "&";
            $url .= "symptoms=" . urlencode($symptoms) . "&";
            $url .= "diagnosis=" . urlencode($diagnosis) . "&";
            $url .= "treatments=" . urlencode($treatments) . "&";
            $url .= "record_id=" . urlencode($record_id);
            header("Location: $url");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error: Patient ID is missing!";
        exit();
    }
}

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treatment Record</title>
    <link rel="icon" type="image/png" href="images/heart-logo.png"> 
    <link rel="stylesheet" href="styles/dboardStyle.css">
    <link rel="stylesheet" href="styles/modals.css">
    <link rel="stylesheet" href="../vendors/bootstrap-5.0.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

</head>

<style>
    
    /* Add custom styles for the autocomplete list */
    .list {
        background-color: white;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 5px;
        list-style-type: none;
        margin-top: 5px;
        /* Adjust margin to give space between input field and list */
        width: calc(100% - 10px);
        /* Adjust width to match the input field */
        max-height: 150px;
        overflow-y: auto;
        font-size: 14px;
        position: absolute;
        z-index: 1;
    }

    .list li {
        cursor: pointer;
        padding: 8px 12px;
    }

    .list li:hover {
        background-color: #f9f9f9;
    }

    .input-container {
        position: relative;
    }

    #sex {
        height: 83px;
        border-radius: 15px;
        background-color: white;
        padding: 0.625rem;
        padding-left: 2rem;
        padding-right: 1.5rem;
        box-sizing: border-box;
        border: none;
        box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 3px 0px, rgba(0, 0, 0, 0.06) 0px 1px 2px 0px;
        transition: border-color 0.3s;
        /* Added transition for smoother effect */
    }

    button:disabled,
    button:disabled:hover {
        background-color: #D4D4D4 !important;
        color: #fff !important;
        cursor: default !important;
        border: none !important;
    }

    .grayed-out {
        background-color: #f2f2f2; /* Gray background */
        color: #999; /* Light gray text color */
        pointer-events: none; /* Disable interaction */
    }

    select:focus {
        outline: none;
        transition: border 0.3s, box-shadow 0.3s;
        }

    select {
        cursor: pointer;
          /* General styling */
            height: 30px;
            width: 100px;
            border-radius: 0;
            padding-left: 10px;

            /* Removes the default <select> styling */
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;

            /* Positions background arrow image */
            background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAh0lEQVQ4T93TMQrCUAzG8V9x8QziiYSuXdzFC7h4AcELOPQAdXYovZCHEATlgQV5GFTe1ozJlz/kS1IpjKqw3wQBVyy++JI0y1GTe7DCBbMAckeNIQKk/BanALBB+16LtnDELoMcsM/BESDlz2heDR3WePwKSLo5eoxz3z6NNcFD+vu3ij14Aqz/DxGbKB7CAAAAAElFTkSuQmCC');
            background-repeat: no-repeat;
            background-position: calc(100% - 20px) center; /* Adjust '20px' to the amount of padding you want */
    }
</style>

<body>
    <div class="loader">
        <img src="images/loader.gif">
    </div>
    
    <div class="overlay" id="overlay"></div>

    <?php
    include ('includes/sidebar/patients-treatment-record.php');
    ?>

<!-- Preview Modal -->
<div class="modal" id="previewModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content"> <!-- Put padding here -->
            <div class="modal-body"> <!-- Main div -->
                <div class="content-wrapper"> <!-- Wrapper for body and scrollbar -->
                    <div class="custom-scrollbar"> <!-- Div for the scrollbar -->
                        <div class="body-content"> <!-- Div for the body content -->
                            <div class="modal-title">
                                <span style="color: #E13F3D; font-size: 2rem;">Treatment</span>
                                <span style="color: #058789; font-size: 2rem;">Record</span>
                            </div>
                            <div class="modal-description" style="justify-content: center;">Please confirm the following details before submitting.</div>
                            <div class="modal-student-info-title">
                                <span class="student-text" style="color: #E13F3D;">Student </span>
                                <span class="info-text" style="color: #058789;">Information</span>
                            </div>
                            <!-- Text labels and values of name, age, and sex -->
                            <div class="modal-student-info">
                                <div class="text-label" style="font-family: 'Poppins'; font-size: 0.938rem; color: #333; text-align: left; display: inline-block; width: 40%; ">Full Name</div>
                                <div class="text-label" style="font-family: 'Poppins'; font-size: 0.938rem; color: #333; text-align: left; display: inline-block; width: 30%; ">Gender</div>
                                <div class="text-label" style="font-family: 'Poppins'; font-size: 0.938rem; color: #333; text-align: left; display: inline-block; width: 20%; ">Age</div>
                            </div>
                            <div class="modal-student-info">
                                <span id="preview-full-name" style="font-family: 'Poppins'; font-size: 1.125rem; font-weight: 600; color: #000; text-align: left; display: inline-block; width: 40%; "><?php echo isset($full_name) ? $full_name : ''; ?></span>
                                <span id="preview-sex" style="font-family: 'Poppins'; font-size: 1.125rem; font-weight: 600; color: #000; text-align: left; display: inline-block; width: 30%; "><?php echo isset($sex) ? $sex : ''; ?></span>
                                <span id="preview-age" style="font-family: 'Poppins'; font-size: 1.125rem; font-weight: 600; color: #000; text-align: left; display: inline-block; width: 20%; "><?php echo isset($age) ? $age : ''; ?></span>
                            </div>
                            <!-- Text labels and values of course and section -->
                            <div class="modal-student-info">
                                <div class="text-label" style="font-family: 'Poppins'; font-size: 0.938rem; color: #333; text-align: left; display: inline-block; width: 60%; margin-top: 0.625rem; ">Course</div>
                                <div class="text-label" style="font-family: 'Poppins'; font-size: 0.938rem; color: #333; text-align: left; display: inline-block; width: 40%; margin-top: 0.625rem; ">Block Section</div>
                            </div>
                            <div class="modal-student-info">
                                <span id="preview-course" style="font-family: 'Poppins'; font-size: 1.125rem; font-weight: 600; color: #000; text-align: left; display: inline-block; width: 60%; "><?php echo isset($course) ? $course : ''; ?></span>
                                <span id="preview-section" style="font-family: 'Poppins'; font-size: 1.125rem; font-weight: 600; color: #000; text-align: left; display: inline-block; width: 40%; "><?php echo isset($section) ? $section : ''; ?></span>
                            </div>
                            <!-- Horizontal Break Bar -->
                            <div class="modal-student-info">
                                <hr class="horizontal-break-bar">
                            </div>
                            <div class="modal-student-info-title">
                                <span class="student-text" style="color: #E13F3D;">Medical </span>
                                <span class="info-text" style="color: #058789;">Treatment</span>
                            </div>
                            <!-- Text labels and values of symptoms -->
                            <div class="modal-student-info">
                                <div class="text-label" style="font-family: 'Poppins'; font-size: 0.938rem; color: #333; text-align: left; display: inline-block; width: 100%;">Symptoms</div>
                            </div>
                            <div class="modal-student-info">
                                <span id="preview-symptoms" style="font-family: 'Poppins'; font-size: 1.125rem; font-weight: 600; color: #000; text-align: left; display: inline-block; width: 100%; "><?php echo isset($symptoms) ? $symptoms : ''; ?></span>
                            </div>
                            <!-- Text labels and values of diagnosis and treatments -->
                            <div class="modal-student-info">
                                <div class="text-label" style="font-family: 'Poppins'; font-size: 0.938rem; color: #333; text-align: left; display: inline-block; width: 40%; margin-top: 0.625rem; ">Diagnosis</div>
                                <div class="text-label" style="font-family: 'Poppins'; font-size: 0.938rem; color: #333; text-align: left; display: inline-block; width: 60%; margin-top: 0.625rem; ">Treatment</div>
                            </div>
                            <div class="modal-student-info">
                                <span id="preview-diagnosis" style="font-family: 'Poppins'; font-size: 1.125rem; font-weight: 600; color: #000; text-align: left; display: inline-block; width: 40%; "><?php echo isset($diagnosis) ? $diagnosis : ''; ?></span>
                                <span id="preview-treatment" style="font-family: 'Poppins'; font-size: 1.125rem; font-weight: 600; color: #000; text-align: left; display: inline-block; width: 60%; "><?php echo isset($treatments) ? $treatments : ''; ?></span>
                            </div>
                            <!-- Cancel and Submit Buttons -->
                            <div class="modal-buttons">
                                <button type="button" class="btn btn-secondary" id="cancel-confirm-modal" data-dismiss="modal"
                                    style="background-color: #777777; 
                                            font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-right: 1.25rem;">Cancel</button>
                                <button type="button" class="btn btn-secondary" id="submit-form-modal" data-dismiss="modal"
                                    style="background-color: #058789; 
                                            font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem;">Submit</button>
                                    <input type="hidden" id="user-fullname" name="user-fullname" value="<?php echo htmlspecialchars($_SESSION['full_name']); ?>">
                            </div>
                        </div>
                    </div>
                </div>
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
                    <div class="input-container">
                        <input type="text" id="full-name" name="full_name" placeholder="Full Name" autocomplete="off"
                            required onkeyup="searchPatients(this.value)">
                        <ul id="search-results" class="list" style="display: none;"></ul>
                    </div>
                    <input type="hidden" id="patient_id" name="patient_id">
                    <select id="sex" name="sex" required>
                        <option value="" disabled selected hidden>Sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                    <input type="number" name="age" id="age" placeholder="Age" required>
                </div>
                <div class="input-row">
                    <input type="text" id="course" name="course" placeholder="Course/Organization" autocomplete="off"
                        required>
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
                    <input type="text" id="symptoms" name="symptoms" placeholder="Symptoms" autocomplete="off"
                        value="<?php echo isset($_GET['symptoms']) ? htmlspecialchars($_GET['symptoms']) : ''; ?>" required>
                </div>
                <div class="input-row">
                    <input type="text" id="diagnosis" name="diagnosis" placeholder="Diagnosis" autocomplete="off"
                        value="<?php echo isset($_GET['diagnosis']) ? htmlspecialchars($_GET['diagnosis']) : ''; ?>" required>
                    <input type="text" id="treatments" name="treatments" placeholder="Treatments/Medicines"
                        autocomplete="off" value="<?php echo isset($_GET['treatments']) ? htmlspecialchars($_GET['treatments']) : ''; ?>"
                        required>
                </div>
                <div class="right-row">
                    <button type="submit" id="submit-form-button" name="record-btn">Submit Form</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    include ('includes/footer.php');
    ?>
    <script src="../vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/script.js"></script>
    <script src="scripts/loader.js"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const aiToolUsed = <?php echo json_encode($aiToolUsed); ?>;
            if (aiToolUsed) {
                // Add the 'grayed-out' class to the input fields when AI tool is used
                document.getElementById('symptoms').classList.add('grayed-out');
                document.getElementById('diagnosis').classList.add('grayed-out');
                document.getElementById('treatments').classList.add('grayed-out');
            }
        });
    </script>

<script>
$(document).ready(function () {
    const patientList = $('#patient-list');

    // Function to clear and enable fields
    function clearAndEnableFields() {
        $('#age, #sex, #course, #section').val('').removeClass('grayed-out').removeAttr('readonly');
        checkFormCompletion();
    }

    // Function to check form completion and enable/disable the submit button
    function checkFormCompletion() {
        var fullName = $("#full-name").val().trim();
        var sex = $("#sex").val().trim();
        var age = $("#age").val().trim();
        var course = $("#course").val().trim();
        var section = $("#section").val().trim();
        var symptoms = $("#symptoms").val().trim();
        var diagnosis = $("#diagnosis").val().trim();
        var treatments = $("#treatments").val().trim();

        var allFieldsCompleted = fullName !== '' && sex !== '' && age !== '' && course !== '' && section !== '' &&
            symptoms !== '' && diagnosis !== '' && treatments !== '';

        $("#submit-form-button").prop('disabled', !allFieldsCompleted);
    }

    // Event handler for input changes in the full name field
    $('#full-name').on('input', function () {
        let fullName = $(this).val();
        if (fullName.length < 2) {
            patientList.hide();
        } else {
            $.ajax({
                url: 'get_patient_info.php',
                method: 'GET',
                data: { full_name: fullName },
                success: function (data) {
                    patientList.empty();
                    if (data.length > 0) {
                        data.forEach(patient => {
                            patientList.append(`<li data-id="${patient.patient_id}" data-age="${patient.age}" data-sex="${patient.sex}" data-course="${patient.course}" data-section="${patient.section}">${patient.full_name}</li>`);
                        });
                        patientList.show();
                    } else {
                        patientList.hide();
                    }
                }
            });
        }

        // Clear and enable fields if full name is changed
        clearAndEnableFields();
    });

    // Event handler for selecting a patient from the list
    patientList.on('click', 'li', function () {
        let patientId = $(this).data('id');
        let age = $(this).data('age');
        let sex = $(this).data('sex');
        let course = $(this).data('course');
        let section = $(this).data('section');

        $('#patient-id').val(patientId);
        $('#age').val(age);
        $('#sex').val(sex);
        $('#course').val(course);
        $('#section').val(section);

        patientList.hide();
        checkFormCompletion();
    });

    // Event listeners for all input fields to check form completion
    $('#full-name, #sex, #age, #course, #section, #symptoms, #diagnosis, #treatments').on('input', checkFormCompletion);

    // Show Modal when Submit button is clicked
    $("#submit-form-button").click(function (event) {
        event.preventDefault(); // Prevent default form submission
        populatePreviewModal(); // Populate the preview modal with form data
        $("#previewModal").modal("show"); // Show the preview modal
    });

    // Function to populate the preview modal with form data
    function populatePreviewModal() {
        // Fetch input values from form fields
        var fullName = $("#full-name").val();
        var sex = $("#sex").val();
        var age = $("#age").val();
        var course = $("#course").val();
        var section = $("#section").val();
        var symptoms = $("#symptoms").val();
        var diagnosis = $("#diagnosis").val();
        var treatments = $("#treatments").val();

        // Populate the preview modal with the fetched data
        $("#preview-full-name").text(fullName);
        $("#preview-sex").text(sex);
        $("#preview-age").text(age);
        $("#preview-course").text(course);
        $("#preview-section").text(section);
        $("#preview-symptoms").text(symptoms);
        $("#preview-diagnosis").text(diagnosis);
        $("#preview-treatment").text(treatments);
    }

    // Close the Modal with the close button
    $("#cancel-confirm-modal").click(function (event) {
        $("#previewModal").modal("hide");
    });

    // Handle form submission when user confirms in the modal
    $("#submit-form-modal").click(function (event) {
        $("#treatment-form").submit(); // Submit the form
        logActivity();
    });

    // Function to log activity
    function logActivity() {
        var fullName = document.getElementById('user-fullname').value.trim();
        var action = " created a new Treatment Record";

        // AJAX call to log activity
        $.ajax({
            type: 'POST',
            url: 'log_activity.php', // Create a PHP file to handle logging
            data: { fullname: fullName, action: action },
            success: function(response) {
                console.log('Activity logged successfully.');
            },
            error: function(xhr, status, error) {
                console.error('Error logging activity:', error);
            }
        });
    }

    // Initial call to set the initial state of the submit button
    checkFormCompletion();
});
</script>

<script>
function selectPatient(patient_id, fullName, gender, age, course, section) {
    document.getElementById("full-name").value = fullName;
    document.getElementById("sex").value = gender;
    document.getElementById("age").value = age;
    document.getElementById("course").value = course;
    document.getElementById("section").value = section;
    document.getElementById("patient_id").value = patient_id;

    // Add 'grayed-out' class to the fields
    document.getElementById("sex").classList.add('grayed-out');
    document.getElementById("age").classList.add('grayed-out');
    document.getElementById("course").classList.add('grayed-out');
    document.getElementById("section").classList.add('grayed-out');

    // Trigger the checkFormCompletion function after auto-filling the fields
    checkFormCompletion();
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
document.getElementById("treatment-form").addEventListener("submit", function (event) {
    if (!validateForm()) {
        event.preventDefault(); // Prevent form submission if validation fails
    }
});

// JavaScript
function searchPatients(input) {
    if (input.length == 0) {
        document.getElementById("search-results").innerHTML = "";
        document.getElementById("search-results").style.display = "none";
        return;
    } else {
        $.ajax({
            type: 'POST',
            url: 'autocomplete.php',
            data: { input: input },
            success: function (data) {
                console.log(data);
                try {
                    var suggestions = JSON.parse(data);
                    if (Array.isArray(suggestions) && suggestions.length > 0) {
                        var listHtml = '';
                        suggestions.forEach(function (person) {
                            var fullName = person.first_name + ' ' + person.last_name;
                            listHtml += '<li onclick="selectFullName(\'' + fullName + '\', \'' + person.patient_id + '\')">' + fullName + '</li>';
                        });
                        $('#search-results').html(listHtml);
                        document.getElementById("search-results").style.display = "block";
                    } else {
                        document.getElementById("search-results").innerHTML = "";
                        document.getElementById("search-results").style.display = "none"; // Hide the list box if there are no suggestions
                    }
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
}

function selectFullName(fullName, patientId) {
    document.getElementById("full-name").value = fullName;
    document.getElementById("patient_id").value = patientId; // Set the patient_id
    document.getElementById("search-results").innerHTML = ""; // Clear suggestions
    document.getElementById("search-results").style.display = "none"; // Hide the search results

    // Now, fetch additional patient data from the server using patientId
    $.ajax({
        type: 'POST',
        url: 'autocomplete.php',
        data: { patient_id: patientId },
        success: function (data) {
            // Assuming 'data' contains JSON with patient details
            try {
                var patientData = JSON.parse(data);
                document.getElementById("sex").value = patientData.sex;
                document.getElementById("age").value = patientData.age;
                document.getElementById("course").value = patientData.course;
                document.getElementById("section").value = patientData.section;
                document.getElementById("symptoms").focus(); // Move focus to the next input field

                // Add 'grayed-out' class to the fields
                document.getElementById("sex").classList.add('grayed-out');
                document.getElementById("age").classList.add('grayed-out');
                document.getElementById("course").classList.add('grayed-out');
                document.getElementById("section").classList.add('grayed-out');

                // Trigger the checkFormCompletion function after auto-completing patient credentials
                checkFormCompletion();
            } catch (error) {
                console.error('Error parsing JSON:', error);
            }
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText); // Log any errors to the console
        }
    });
}

// Trigger autocomplete only when input length > 0
$(document).ready(function () {
    $('#full-name').keyup(function () {
        var input = $(this).val().trim();
        if (input.length > 0) {
            searchPatients(input);
        }
    });
});

// Function to check the completion status of all required fields
function checkFormCompletion() {
    var fullName = document.getElementById("full-name").value.trim();
    var sex = document.getElementById("sex").value.trim();
    var age = document.getElementById("age").value.trim();
    var course = document.getElementById("course").value.trim();
    var section = document.getElementById("section").value.trim();
    var symptoms = document.getElementById("symptoms").value.trim();
    var diagnosis = document.getElementById("diagnosis").value.trim();
    var treatments = document.getElementById("treatments").value.trim();

    // Check if all required fields are completed
    var allFieldsCompleted = fullName !== '' && sex !== '' && age !== '' && course !== '' && section !== '' &&
        symptoms !== '' && diagnosis !== '' && treatments !== '';

    // Enable or disable the submit button based on completion status
    var submitButton = document.getElementById("submit-form-button");
    submitButton.disabled = !allFieldsCompleted;
}

// Add event listeners to input fields to trigger the checkFormCompletion function
document.getElementById("full-name").addEventListener("input", function() {
    clearAndEnableFields(); // Clear and enable fields when full_name input changes
    checkFormCompletion(); // Check form completion status after full_name input changes
});
document.getElementById("sex").addEventListener("input", checkFormCompletion);
document.getElementById("age").addEventListener("input", checkFormCompletion);
document.getElementById("course").addEventListener("input", checkFormCompletion);
document.getElementById("section").addEventListener("input", checkFormCompletion);
document.getElementById("symptoms").addEventListener("input", checkFormCompletion);
document.getElementById("diagnosis").addEventListener("input", checkFormCompletion);
document.getElementById("treatments").addEventListener("input", checkFormCompletion);

// Call the checkFormCompletion function initially to set the initial state of the submit button
checkFormCompletion();
</script>

<script>
    // JavaScript function to clear and enable fields when full_name input changes
    function clearAndEnableFields() {
        // Clear the values of the fields
        document.getElementById("sex").value = "";
        document.getElementById("age").value = "";
        document.getElementById("course").value = "";
        document.getElementById("section").value = "";

        // Enable the fields
        document.getElementById("sex").classList.remove('grayed-out');
        document.getElementById("age").classList.remove('grayed-out');
        document.getElementById("course").classList.remove('grayed-out');
        document.getElementById("section").classList.remove('grayed-out');
    }

    // Add event listener to the full_name input field to trigger the clearAndEnableFields function
    document.getElementById("full-name").addEventListener("input", clearAndEnableFields);
</script>

</body>

</html> 
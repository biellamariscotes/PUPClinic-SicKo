<?php
require_once ('src/includes/session-nurse.php');
require_once ('src/includes/connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $check_patient_query = "SELECT * FROM patient WHERE patient_id = '$patient_id'";
    $result = mysqli_query($conn, $check_patient_query);
    if (mysqli_num_rows($result) == 0) {
        // Patient does not exist
        echo "Error: Patient does not exist!";
        echo "Patient ID: " . $patient_id;
        exit();
    }
    
    $full_name = isset($_POST['full_name']) ? $_POST['full_name'] : '';
    $sex = isset($_POST['sex']) ? $_POST['sex'] : '';
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $symptoms = mysqli_real_escape_string($conn, $_POST['symptoms']);
    $diagnosis = mysqli_real_escape_string($conn, $_POST['diagnosis']);
    $treatments = mysqli_real_escape_string($conn, $_POST['treatments']);

    $sql = "INSERT INTO treatment_record (patient_id, full_name, sex, age, course, section, symptoms, diagnosis, treatments) 
            VALUES ('$patient_id', '$full_name', '$sex', '$age', '$course', '$section', '$symptoms', '$diagnosis', '$treatments')";

if (mysqli_query($conn, $sql)) {
    // Redirect to confirmation page with form data
    $url = "treatment-record-confirmation.php?";
    $url .= "full_name=" . urlencode($full_name) . "&";
    $url .= "sex=" . urlencode($sex) . "&";
    $url .= "age=" . urlencode($age) . "&";
    $url .= "course=" . urlencode($course) . "&";
    $url .= "section=" . urlencode($section) . "&";
    $url .= "symptoms=" . urlencode($symptoms) . "&";
    $url .= "diagnosis=" . urlencode($diagnosis) . "&";
    $url .= "treatments=" . urlencode($treatments);
    header("Location: $url");
    exit();
} else {
    echo "Error: " . mysqli_error($conn);
}

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        margin-top: 5px; /* Adjust margin to give space between input field and list */
        width: calc(100% - 10px); /* Adjust width to match the input field */
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
        padding: 10px;
        box-sizing: border-box;
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: border-color 0.3s; /* Added transition for smoother effect */
        }
</style>

<body>
    <div class="overlay" id="overlay"></div>

    <?php
    include ('src/includes/sidebar.php');
    ?>

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
                    <input type="text" id="full-name" name="full_name" placeholder="Full Name" autocomplete="off" required onkeyup="searchPatients(this.value)">
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
                    <button type="submit" id="submit-form-button"
                        name="record-btn">Submit Form</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    include ('src/includes/footer.php');
    ?>
    <script src="src/scripts/script.js"></script>
    <script>
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
                success: function(data) {
                    console.log(data); 
                    try {
                        var suggestions = JSON.parse(data);
                        if (Array.isArray(suggestions) && suggestions.length > 0) {
                            var listHtml = '';
                            suggestions.forEach(function(person) {
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
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    }
    
    function selectFullName(fullName, patientId) {
    document.getElementById("full-name").value = fullName;
    document.getElementById("patient_id").value = patientId; // Set the patient_id
    document.getElementById("search-results").innerHTML = ""; // Clear suggestions
    // Now, fetch additional patient data from the server using patientId
    $.ajax({
        type: 'POST',
        url: 'autocomplete.php',
        data: { patient_id: patientId },
        success: function(data) {
            // Assuming 'data' contains JSON with patient details
            try {
                var patientData = JSON.parse(data);
                document.getElementById("sex").value = patientData.sex;
                document.getElementById("age").value = patientData.age;
                document.getElementById("course").value = patientData.course;
                document.getElementById("section").value = patientData.section;
                document.getElementById("symptoms").focus(); // Move focus to the next input field
            } catch (error) {
                console.error('Error parsing JSON:', error);
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText); // Log any errors to the console
        }
    });
}


    // Trigger autocomplete only when input length > 0
    $(document).ready(function() {
        $('#full-name').keyup(function() {
            var input = $(this).val().trim();
            if (input.length > 0) {
                searchPatients(input);
            }
        });
    });
</script>


    
</body>

</html>
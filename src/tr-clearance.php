<?php
require_once('includes/session-nurse.php');
require_once('includes/connect.php');

// Retrieve record_id from query parameters
$record_id = isset($_GET['record_id']) ? $_GET['record_id'] : '';

// Initialize variables to store fetched data
$full_name = '';
$sex = '';
$age = '';
$course = '';
$section = '';
$symptoms = '';
$diagnosis = '';
$treatments = '';

// Fetch data from database based on record_id
if (!empty($record_id)) {
    $sql = "SELECT * FROM treatment_record WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $record_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $full_name = $row['full_name'];
        $sex = $row['sex'];
        $age = $row['age'];
        $course = $row['course'];
        $section = $row['section'];
        $symptoms = $row['symptoms'];
        $diagnosis = $row['diagnosis'];
        $treatments = $row['treatments'];
    } else {
        // Handle case where no record found
        echo "No record found with record_id = " . $record_id;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Treatment Record </title>
    <link rel="icon" type="image/png" href="images/heart-logo.png"> 
    <link rel="stylesheet" href="styles/dboardStyle.css">
</head>

<style>
input, select {
    color: gray;
    cursor: default;
    pointer-events: none;
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
    transition: border-color 0.3s; /* Added transition for smoother effect */
}

#generate-clearance-button,
#generate-endorsement-button {
    width: 307px;
    padding: 10px;
    background-color: #058789;
    color: #FFFFFF;
    border: none;
    border-radius: 15px;
    font-weight: 600;
    font-size: 20px;
    line-height: 30px;
    letter-spacing: 0.02em;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin: 20px 0 50px 0;
}

#generate-clearance-button:hover,
#generate-endorsement-button:hover {
    background-color: #1D434E;
}

select {
        appearance: none;
    }

</style>

<body>
    <div class="loader d-flex">
        <img src="images/loader.gif">
    </div>

    <div class="overlay" id="overlay"></div>

    <?php include ('includes/sidebar/patients-treatment-record.php'); ?>

    <div class="content" id="content">
        <div class="left-header">
            <p>
                <span style="color: #E13F3D;">Treatment</span>
                <span style="color: #058789;">Record</span>
            </p>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <form id="treatment-form" action="" method="post">
                <div class="input-row">
                <input type="hidden" name="record_id" value="<?php echo htmlspecialchars($record_id); ?>">
                    <input type="text" id="full-name" name="full_name" placeholder="Full Name" autocomplete="off" required value="<?php echo htmlspecialchars($full_name); ?>" >
                    <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($record_id); ?>" > <!-- Use record_id as patient_id -->
                    <select id="sex" name="sex" required >
                        <option value="" disabled hidden>Gender</option>
                        <option value="Male" <?php if($sex == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if($sex == 'Female') echo 'selected'; ?>>Female</option>
                        <option value="Other" <?php if($sex == 'Other') echo 'selected'; ?>>Other</option>
                    </select>
                    <input type="number" id="age" name="age" placeholder="Age" required value="<?php echo htmlspecialchars($age); ?>" >
                </div>
                <div class="input-row">
                    <input type="text" id="course" name="course" placeholder="Course/Organization" autocomplete="off" required value="<?php echo htmlspecialchars($course); ?>" >
                    <select id="section" name="section" required >
                        <option value="" disabled hidden>Block Section</option>
                        <option value="1-1" <?php if($section == '1-1') echo 'selected'; ?>>1-1</option>
                        <option value="1-2" <?php if($section == '1-2') echo 'selected'; ?>>1-2</option>
                        <option value="2-1" <?php if($section == '2-1') echo 'selected'; ?>>2-1</option>
                        <option value="2-2" <?php if($section == '2-2') echo 'selected'; ?>>2-2</option>
                        <option value="3-1" <?php if($section == '3-1') echo 'selected'; ?>>3-1</option>
                        <option value="3-2" <?php if($section == '3-2') echo 'selected'; ?>>3-2</option>
                        <option value="4-1" <?php if($section == '4-1') echo 'selected'; ?>>4-1</option>
                        <option value="4-2" <?php if($section == '4-2') echo 'selected'; ?>>4-2</option>
                    </select>
                </div>
                <div class="input-row">
                    <input type="text" id="symptoms" name="symptoms" placeholder="Symptoms" autocomplete="off" required value="<?php echo htmlspecialchars($symptoms); ?>" >
                </div>
                <div class="input-row">
                    <input type="text" id="diagnosis" name="diagnosis" placeholder="Diagnosis" autocomplete="off" required value="<?php echo htmlspecialchars($diagnosis); ?>" >
                    <input type="text" id="treatments" name="treatments" placeholder="Treatments/Medicines" autocomplete="off" required value="<?php echo htmlspecialchars($treatments); ?>"  >
                </div>
                <div class="button-row" style="display: flex; justify-content: center; gap: 3rem;">
                    <input type="hidden" name="record_id" value="<?php echo htmlspecialchars($record_id); ?>">
                    <button type="button" id="generate-excuse-letter-button" name="record-btn" onclick="submitForm('excuse-letter.php')">Generate Excuse Letter</button>
                    <button type="button" id="generate-clearance-button" name="record-btn" onclick="submitForm('clearance.php')">Generate Clearance</button>
                    <button type="button" id="generate-endorsement-button" name="hello-btn" onclick="submitForm('endorsement.php')">Generate Endorsement</button>
                </div>
            </form>
        </div>
    </div>

    <?php include ('includes/footer.php'); ?>  
    <script src="../vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/script.js"></script>

    <!-- LOADER -->
    <script>
        function simulateContentLoading() {
            showLoader();
            setTimeout(function () {
                hideLoader();
                showContent();
            }, 3000);
        }

        function showLoader() {
            console.log("Showing loader.");
            document.querySelector('.loader').classList.add('visible');
        }

        function hideLoader() {
            console.log("Hiding loader with transition.");
            const loader = document.querySelector('.loader');
            loader.style.transition = 'opacity 0.5s ease-out';
            loader.style.opacity = '0';
            loader.addEventListener('transitionend', function (event) {
                if (event.propertyName === 'opacity') {
                    loader.classList.remove('d-flex');
                    loader.style.display = 'none';
                }
            });
        }

        function showContent() {
            console.log("Showing content.");
            const body = document.querySelector('.main-content');
            body.style.display = 'block';
        }

        simulateContentLoading();

    </script>
    <!-- END OF LOADER -->
        
    <script>
        function submitForm(action) {
            const form = document.getElementById('treatment-form');
            form.action = action;
            form.method = 'post';
            form.submit();
        }
    </script>

</body>
</html>

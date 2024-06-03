<?php
require_once('includes/session-nurse.php');
require_once('includes/connect.php');

$patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : '';
$full_name = isset($_GET['full_name']) ? $_GET['full_name'] : '';
$sex = isset($_GET['sex']) ? $_GET['sex'] : '';
$age = isset($_GET['age']) ? $_GET['age'] : '';
$course = isset($_GET['course']) ? $_GET['course'] : '';
$section = isset($_GET['section']) ? $_GET['section'] : '';
$symptoms = isset($_GET['symptoms']) ? $_GET['symptoms'] : '';
$diagnosis = isset($_GET['diagnosis']) ? $_GET['diagnosis'] : '';
$treatments = isset($_GET['treatments']) ? $_GET['treatments'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Treatment Record Confirmation </title>
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
    include ('includes/sidebar/patients-treatment-record.php');
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
        <form id="treatment-form" action="excuse-letter.php" method="post">
            <div class="input-row">
            <input type="text" id="full-name" name="full_name" placeholder="Full Name" autocomplete="off" required value="<?php echo $full_name; ?>" >
            <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>" >
                <select id="sex" name="sex" required >
                    <option value="" disabled hidden>Gender</option>
                    <option value="Male" <?php if($sex == 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if($sex == 'Female') echo 'selected'; ?>>Female</option>
                    <option value="Other" <?php if($sex == 'Other') echo 'selected'; ?>>Other</option>
                </select>
                <input type="number" id="age" name="age" placeholder="Age" required value="<?php echo $age; ?>" >
            </div>
            <div class="input-row">
                <input type="text" id="course" name="course" placeholder="Course/Organization" autocomplete="off" required value="<?php echo $course; ?>" >
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
                <input type="text" id="symptoms" name="symptoms" placeholder="Symptoms" autocomplete="off" required value="<?php echo $symptoms; ?>" >
            </div>
            <div class="input-row">
                <input type="text" id="diagnosis" name="diagnosis" placeholder="Diagnosis" autocomplete="off" required value="<?php echo $diagnosis; ?>" >
                <input type="text" id="treatments" name="treatments" placeholder="Treatments/Medicines" autocomplete="off" required value="<?php echo $treatments; ?>"  >
            </div>
            <div class="middle-row">
                <button type="submit" id="generate-excuse-letter-button" name="record-btn">Generate Excuse Letter</button>
            </div>
    </form>
    </div>
</div>


    <?php
    include ('includes/footer.php');
    ?>  
    <script src="scripts/script.js"></script>
</body>
</html>

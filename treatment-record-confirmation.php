<?php
require_once('src/includes/session-nurse.php');
require_once('src/includes/connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Treatment Record Confirmation </title>
    <link rel="icon" type="image/png" href="src/images/sicko-logo.png"> 
    <link rel="stylesheet" href="src/styles/dboardStyle.css">
</head>
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
        <form id="treatment-form">
            <div class="input-row">
                <input type="text" id="full-name" placeholder="Full Name" autocomplete="off" required>
                <select id="gender" required>
                    <option value="" disabled selected hidden>Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <input type="number" id="age" placeholder="Age" required>
            </div>
            <div class="input-row">
                <input type="text" id="course" placeholder="Course/Organization" autocomplete="off" required>
                <select id="section" required>
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
            <div class="input-row">
                <input type="text" id="symptoms" placeholder="Symptoms" autocomplete="off" required>
            </div>
            <div class="input-row">
                <input type="text" id="diagnosis" placeholder="Diagnosis" autocomplete="off" required>
                <input type="text" id="treatments" placeholder="Treatments/Medicines" autocomplete="off"required>
            </div>
            <div class="middle-row">
                    <button type="submit" id="generate-excuse-letter-button" onclick="window.location.href='#'">Generate Excuse Letter</button>
            </div>
        </form>
    </div>
</div>


    <?php
    include ('src/includes/footer.php');
    ?>  
    <script src="src/scripts/script.js"></script>
</body>
</html>

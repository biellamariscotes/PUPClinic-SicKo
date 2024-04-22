<?php
require_once('src/includes/session-nurse.php');
require_once('src/includes/connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Generated Diagnosis</title>
    <link rel="icon" type="image/png" href="src/images/sicko-logo.png"> 
    <link rel="stylesheet" href="src/styles/dboardStyle.css">
</head>
<body>
    <div class="overlay" id="overlay"></div>

    <?php
    include ('src/includes/sidebar.php');
    ?>

    <div class="content" id="content">
        <div class="ai-header-content">
        <div class="ai-header-image-container">
            <img src="src/images/ai-header.svg" alt="AI Header" class="ai-header">
        </div>
        <div class="ai-header-text-container">
            <div class="ai-header-text">
                <div class="ai-text">
                    <p>AI-Based,<span class="bold"> Symptoms</span></p>
                    <p class="bold" style="color: #E13F3D; font-size: 50px; font-family: 'Poppins', sans-serif;">Diagnostic Tool</p>
                    <p style="color: black; font-size: 17px; font-family: 'Poppins', sans-serif; text-align: justify;">Detects and generates possible diagnosis <br> based on patient symptoms.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="left-header">
        <p style="color: #E13F3D; font-size: 40px;">Symptoms</p>
    </div>

    <!-- Symptoms Container -->
    <div class="symptoms-input-container">
        <input type="text" id="symptoms-input" placeholder="Type symptoms keywords..." autocomplete="off">
        <div class="tags-container" id="tags-container"></div>
    </div>

    <div class="left-header">
        <p style="color: #E13F3D; font-size: 40px;">Diagnosis</p>
    </div>
    
    <!-- Diagnosis Container -->
    <div class="diagnosis-container">
        <div class="diagnosis-box">
            <div class="medical-condition">
                <h2 class="medical-condition-header">Medical Condition</h2>
                <p class="sub-text">Definition of the diagnosed sickness. Further definition of the sickness. And another additional details about the sickness.</p>
            </div>
            <div class="treatment-options-container">
                <div class="vertical-line"></div>
                    <div class="treatment-options">
                        <h2 class="treatment-options-header">Treatment Options</h2>
                        <ul class="options-list">
                            <li>Treatment Option 1</li>
                            <li>Treatment Option 2</li>
                            <li>Treatment Option 3</li>
                        </ul>
                    </div>
            </div>
        </div>    
      
    <!-- Keyword Tags Container -->
    <div class="symptoms-input-container">
            <?php
            if(isset($_POST['symptoms'])) {
                $symptoms = $_POST['symptoms'];
                echo "Received symptoms: ".$_POST['symptoms'];
                // Logic to determine diagnosis based on symptoms
                $diagnosis_result = array();

                if (strpos($symptoms, 'dizziness') !== false) {
                    $diagnosis_result[] = 'Possible diagnosis: Low blood pressure or Anemia';
                }

                // Display the diagnosis results
                if (!empty($diagnosis_result)) {
                    echo '<div class="diagnosis-header">';
                    echo '<p class="bold" style="color: #E13F3D; font-size: 40px; font-family: \'Poppins\', sans-serif; text-align: left; margin-left: 200px; margin-bottom: 0;">Diagnosis</p>';
                    echo '</div>';
                    
                    echo '<!-- Diagnosis Container -->';
                    echo '<div class="diagnosis-container">';
                    
                    foreach ($diagnosis_result as $diagnosis) {
                        echo '<div class="diagnosis-box">';
                        echo '<div class="medical-condition">';
                        echo '<h2 class="medical-condition-header">Medical Condition</h2>';
                        echo '<p class="sub-text">' . $diagnosis . '</p>';
                        echo '</div>';
                        echo '<div class="treatment-options-container">';
                        echo '<div class="vertical-line"></div>';
                        echo '<div class="treatment-options">';
                        echo '<h2 class="treatment-options-header">Treatment Options</h2>';
                        echo '<ul class="options-list">';
                        // Treatment options here
                        echo '<li>Rest</li>';
                        echo '<li>Drink plenty of fluids</li>';
                        echo '<li>Take iron supplements if anemia is diagnosed</li>';
                        echo '</ul>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }

                    echo '</div>';
                } else {
                    echo "<p>No diagnosis found for the given symptoms.</p>";
                }
            }
            ?>
        </div>
    </div>

    <!-- New container for the two boxes -->
    <div class="new-boxes-container">
        <!-- First box -->
        <div class="back-button" onclick="window.location.href='ai-basedSDT.php'">
            <div class="box-content">
                <p class="box-text">Back to AI-SDT</p>
            </div>
        </div>

        <!-- Second box -->
        <div class="record-treatment-button" onclick="window.location.href='treatment-record.php'">
            <div class="box-content">
                <p class="box-text">Record Treatment</p>
                <img src="src/images/arrow-icon.svg" alt="Arrow Icon">
            </div>

        </div>
    </div>

    <?php
    include ('src/includes/footer.php');
    ?>
    <script src="src/scripts/script.js"></script>
    <script>
        
        function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }

        var symptomsParam = getUrlParameter('symptoms');
        if (symptomsParam !== '') {
            document.getElementById('symptoms-input').value = symptomsParam;
        }
    </script>
</body>
</html>

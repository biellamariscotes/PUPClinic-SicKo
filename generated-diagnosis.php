<?php
require_once('src/includes/session-nurse.php');
require_once('src/includes/connect.php');

// Define the PHP logic for symptom diagnosis and treatment recommendation
if (isset($_POST['symptoms'])) {
        
    // Format the symptoms input by replacing spaces with underscores and converting to lowercase
    $formattedSymptoms = str_replace(' ', '_', strtolower($_POST['symptoms']));

    // Define the symptoms data array
    $symptomsData = [
        "dizziness" => strpos($formattedSymptoms, 'dizziness') !== false,
        "fever" => strpos($formattedSymptoms, 'fever') !== false,
        "cough" => strpos($formattedSymptoms, 'cough') !== false,
        "pale_lips" => strpos($formattedSymptoms, 'pale_lips') !== false,
        "pale_skin" => strpos($formattedSymptoms, 'pale_skin') !== false,
        "headache" => strpos($formattedSymptoms, 'headache') !== false,
        "abdominal_pain" => strpos($formattedSymptoms, 'abdominal_pain') !== false,
        "shortness_of_breath" => strpos($formattedSymptoms, 'shortness_of_breath') !== false,
        "nausea" => strpos($formattedSymptoms, 'nausea') !== false,
        "vomiting" => strpos($formattedSymptoms, 'vomiting') !== false
    ];

    // Analyze symptoms and predict sickness
    $predictedSicknesses = predictSickness($symptomsData);

    // Recommend treatment based on predicted sickness
    $suggestedTreatments = recommendTreatment($predictedSicknesses);
}

// Function to predict sickness based on symptoms
function predictSickness($symptomsData) {
    // Example logic: if certain symptoms are present, predict a specific sickness
    if ($symptomsData['dizziness'] && $symptomsData['pale_skin']) {
        return ["Low Blood Pressure or Anemia"];
    } elseif ($symptomsData['fever'] && $symptomsData['cough']) {
        return ["Flu"];
    } elseif ($symptomsData['headache']) {
        return ["Migraine"];
    } elseif ($symptomsData['abdominal_pain']) {
        return ["Gastroenteritis"];
    } elseif ($symptomsData['shortness_of_breath']) {
        return ["Asthma"];
    } elseif ($symptomsData['nausea'] && $symptomsData['vomiting']) {
        return ["Gastroenteritis"];
    } else {
        return ["Unknown"]; // If sickness cannot be confidently predicted
    }
}

// Function to recommend treatment based on predicted sickness
function recommendTreatment($predictedSicknesses) {
    // Example treatment recommendations based on predicted sickness
    $treatments = [];
    foreach ($predictedSicknesses as $sickness) {
        switch ($sickness) {
            case "Low Blood Pressure or Anemia":
                $treatments[] = "Take prescribed medication and visit a doctor. Consider iron supplements and proper sleep.";
                break;
            case "Flu":
                $treatments[] = "Rest, drink plenty of fluids, and take over-the-counter flu medications.";
                break;
            case "Migraine":
                $treatments[] = "Pain-relieving medications, preventive medications, lifestyle changes such as stress management and regular sleep schedule.";
                break;
            case "Gastroenteritis":
                $treatments[] = "Fluid and electrolyte replacement, antiemetic medications, bland diet.";
                break;
            case "Asthma":
                $treatments[] = "Bronchodilator medications like albuterol, corticosteroids, and avoidance of triggers such as allergens and smoke).";
                break;
            default:
                $treatments[] = "No specific treatment recommended.";
                break;
        }
    }
    return $treatments;
}
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

    <?php include ('src/includes/sidebar.php'); ?>

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
            <?php if(isset($_POST['symptoms'])): ?>
                <input type="text" id="symptoms-input" value="<?= htmlspecialchars($_POST['symptoms']) ?>" autocomplete="off" disabled>
                <div class="tags-container" id="tags-container"></div>
            <?php endif; ?>
        </div>

        <div class="left-header">
            <p style="color: #E13F3D; font-size: 40px;">Diagnosis</p>
        </div>

        <!-- Diagnosis Container -->
        <?php if(isset($predictedSicknesses)): ?>
            <?php foreach ($predictedSicknesses as $index => $sickness): ?>
                <div class="diagnosis-container">
                    <div class="diagnosis-box">
                        <div class="medical-condition">
                            <h2 class="medical-condition-header">Medical Condition</h2>
                            <p class="sub-text">Predicted Sickness: <?= htmlspecialchars($sickness) ?></p>
                        </div>
                        <div class="treatment-options-container">
                            <div class="vertical-line"></div>
                            <div class="treatment-options">
                                <h2 class="treatment-options-header">Treatment Options</h2>
                                <ul class="options-list">
                                    <li><?= htmlspecialchars($suggestedTreatments[$index]) ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No diagnosis found for the given symptoms.</p>
        <?php endif; ?>

        <!-- New container for the two boxes -->
        <div class="new-boxes-container">
            <!-- First box -->
            <div class="back-button" onclick="window.location.href='ai-basedSDT.php'">
                <div class="box-content">
                    <p class="box-text">Back to AI-SDT</p>
                </div>
            </div>

            <!-- Second box -->
            <div class="record-treatment-button" onclick="recordTreatment()">
                <div class="box-content">
                    <p class="box-text">Record Treatment</p>
                    <img src="src/images/arrow-icon.svg" alt="Arrow Icon">
                </div>
            </div>
        </div>
    </div>

    <?php include ('src/includes/footer.php'); ?>
    <script src="src/scripts/script.js"></script>
    <script>
        function recordTreatment() {
            <?php
            // Get the symptoms, diagnosis, and treatments
            $symptoms = isset($_POST['symptoms']) ? $_POST['symptoms'] : '';
            $diagnosis = isset($predictedSicknesses) ? implode(",", $predictedSicknesses) : '';
            $treatments = isset($suggestedTreatments) ? implode(",", $suggestedTreatments) : '';

            // Encode the data for passing in the URL
            $symptoms = urlencode($symptoms);
            $diagnosis = urlencode($diagnosis);
            $treatments = urlencode($treatments);
            ?>
            window.location.href = `treatment-record.php?symptoms=<?php echo $symptoms; ?>&diagnosis=<?php echo $diagnosis; ?>&treatments=<?php echo $treatments; ?>`;
        }
    </script>
</body>
</html>

<?php
require_once('includes/session-nurse.php');
require_once('includes/connect.php');
require_once('includes/algorithm/naive-bayes.php');

// Assuming you have a function to perform diagnosis
// and return predicted sicknesses and suggested treatments
function performDiagnosis($symptoms) {
    global $naiveBayes;
    $predictedSicknesses = [];
    $suggestedTreatments = [];

    // Predict sickness based on symptoms
    $predictedSickness = $naiveBayes->predict($symptoms);
    
    // Recommend treatment based on predicted sickness
    switch ($predictedSickness) {
        case "common cold":
            $suggestedTreatments[] = "Bioflu/Decolgen";
            break;
        case "flu":
            $suggestedTreatments[] = "Tamiflu/Relenza/Rapivab";
            break;
        case "food poisoning":
            $suggestedTreatments[] = "Loperamide";
            break;
        case "strep throat":
            $suggestedTreatments[] = "Penicilin/Amoxicillin";
            break;
        case "pneumonia":
            $suggestedTreatments[] = "Zithromax";
            break;
        case "malaria":
            $suggestedTreatments[] = "Malarone";
            break;
        case "chikungunya":
            $suggestedTreatments[] = "No Medication";
            break;
        case "typhoid fever":
            $suggestedTreatments[] = "Ciprofloxacin/Azithromycin";
            break;
        case "dengue fever":
            $suggestedTreatments[] = "Acetaminophen";
            break;
        case "tonsillitis":
            $suggestedTreatments[] = "Penicillin";
            break;
        case "dysentery":
            $suggestedTreatments[] = "Loperamide";
            break;
        case "rheumatoid arthritis":
            $suggestedTreatments[] = "Nabumetone";
            break;
        case "infectious mononucleosis":
            $suggestedTreatments[] = "No Medicine";
            break;
        case "bronchitis":
            $suggestedTreatments[] = "Solmux";
            break;
        case "meningitis":
            $suggestedTreatments[] = "Cefotaxime";
            break;
        case "measles":
            $suggestedTreatments[] = "No Medicine";
            break;
        case "urticaria":
            $suggestedTreatments[] = "Cetirizine";
            break;
        case "zika virus":
            $suggestedTreatments[] = "No Medicine";
            break;
        case "hepatitis":
            $suggestedTreatments[] = "Revovir";
            break;
        case "hemorrhagic fever":
            $suggestedTreatments[] = "No Medicine";
            break;
        case "tetanus":
            $suggestedTreatments[] = "Metronidazole";
            break;
        case "urinary tract infection":
            $suggestedTreatments[] = "Ciprofloxacin/Levofloxacin";
            break;
        case "heart failure":
            $suggestedTreatments[] = "Angiotensin";
            break;
        case "migraine":
            $suggestedTreatments[] = "Sumatriptan";
            break;
        default:
            $suggestedTreatments[] = "No specific treatment recommended.";
            break;
    }

    return [$predictedSickness, $suggestedTreatments];
}

$predictedSickness = "";
$suggestedTreatments = [];

if(isset($_POST['symptoms'])) {
    // Get symptoms input
    $symptoms = explode(",", strtolower($_POST['symptoms']));

    
    // Perform diagnosis
    list($predictedSickness, $suggestedTreatments) = performDiagnosis($symptoms);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated Diagnosis</title>
    <link rel="icon" type="image/png" href="images/heart-logo.png">  
    <link rel="stylesheet" href="styles/dboardStyle.css">
</head>
<body>
    <div class="overlay" id="overlay"></div>

    <?php include ('includes/sidebar/ai-basedSDT.php'); ?>

    <div class="content" id="content">
        <div class="ai-header-content">
            <div class="ai-header-image-container">
                <img src="images/ai-header.svg" alt="AI Header" class="ai-header">
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
        <?php if(!empty($predictedSickness)): ?>
            <div class="diagnosis-container">
                <div class="diagnosis-box">
                    <div class="medical-condition">
                    <h2 class="medical-condition-header">Medical Condition: <span style="color: #E13F3D;"><?= ucfirst(htmlspecialchars($predictedSickness)) ?></span></h2>
                        <p class="sub-text">Predicted Sickness: <?= htmlspecialchars($predictedSickness) ?></p>
                    </div>
                    <div class="treatment-options-container">
                        <div class="vertical-line"></div>
                        <div class="treatment-options">
                            <h2 class="treatment-options-header">Treatment Options</h2>
                            <ul class="options-list">
                                <?php foreach ($suggestedTreatments as $treatment): ?>
                                    <li><?= htmlspecialchars($treatment) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
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
                    <img src="images/arrow-icon.svg" alt="Arrow Icon">
                </div>
            </div>
        </div>
    </div>

    <?php include ('includes/footer.php'); ?>
    <script src="scripts/script.js"></script>
    <script>
        function recordTreatment() {
            <?php
            // Get the symptoms, diagnosis, and treatments
            $symptoms = isset($_POST['symptoms']) ? $_POST['symptoms'] : '';
            $diagnosis = isset($predictedSickness) ? $predictedSickness : '';
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

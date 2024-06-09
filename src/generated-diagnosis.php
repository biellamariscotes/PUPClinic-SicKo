<?php
require_once('includes/session-nurse.php');
require_once('includes/connect.php');
require_once('includes/algorithm/naive-bayes.php');

function performDiagnosis($symptoms) {
    global $naiveBayes;
    $predictedSicknesses = [];
    $suggestedTreatments = [];

    // Predict sickness based on symptoms
    $predictedSickness = $naiveBayes->predict($symptoms);
    
    // Recommend treatment based on predicted sickness
    switch ($predictedSickness) {
        case "common cold":
            $suggestedTreatments[] = "Antihistamine";
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
            $suggestedTreatments[] = "Carbocisteine";
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

function getSicknessMeaning($sickness) {
    $meanings = [
        "common cold" => "A viral infection causing congestion, cough, and sore throat in your nose, sinuses, throat and windpipe.",
        "flu" => "Also called influenza, is an infection of the nose, throat and lungs, which are part of the respiratory system. The flu is caused by a virus.",
        "food poisoning" => "An infection or irritation of your digestive tract that spreads through food or drinks. Viruses, bacteria, and parasites cause most food poisoning. Harmful chemicals may also cause food poisoning.",
        "strep throat" => "A bacterial infection that can make your throat feel sore and scratchy. Strep throat accounts for only a small portion of sore throats. If untreated, strep throat can cause complications, such as kidney inflammation or rheumatic fever.",
        "pneumonia" => "An infection that affects one or both lungs. It causes the air sacs, or alveoli, of the lungs to fill up with fluid or pus. Bacteria, viruses, or fungi may cause pneumonia.",
        "malaria" => "A disease caused by a parasite. The parasite is spread to humans through the bites of infected mosquitoes. People who have malaria usually feel very sick with a high fever and shaking chills. While the disease is uncommon in temperate climates, malaria is still common in tropical and subtropical countries.",
        "chikungunya" => "Derives from a word in the Kimakonde language of southern Tanzania, meaning “to become contorted”, and describes the stooped appearance of sufferers with joint pain (arthralgia). Chikungunya is transmitted to humans by the bites of infected female mosquitoes.",
        "typhoid fever" => "A life-threatening infection caused by the bacterium Salmonella Typhi. It is usually spread through contaminated food or water. Once Salmonella Typhi bacteria are ingested, they multiply and spread into the bloodstream.",
        "dengue fever" => "A viral infection that spreads from mosquitoes to people. It is more common in tropical and subtropical climates.A mosquito-borne viral disease causing high fever, rash, and muscle and joint pain.",
        "tonsillitis" => "Inflammation of the tonsils, two oval-shaped pads of tissue at the back of the throat — one tonsil on each side. Signs and symptoms of tonsillitis include swollen tonsils, sore throat, difficulty swallowing and tender lymph nodes on the sides of the neck.",
        "dysentery" => "An infection in your intestines that causes bloody diarrhea. It can be caused by a parasite or bacteria.",
        "rheumatoid arthritis" => "An autoimmune and inflammatory disease, which means that your immune system attacks healthy cells in your body by mistake, causing inflammation (painful swelling) in the affected parts of the body.",
        "infectious mononucleosis" => "A type of infection. It causes swollen lymph glands, fever, sore throat, and often extreme fatigue. It's often spread through contact with infected saliva from the mouth. Symptoms can take between 4 to 6 weeks to appear.",
        "bronchitis" => "A condition that develops when the airways in the lungs, called bronchial tubes, become inflamed and cause coughing, often with mucus production.",
        "meningitis" => "An infection and inflammation of the fluid and membranes surrounding the brain and spinal cord. These membranes are called meninges.",
        "measles" => "A very contagious disease that causes fever, a red rash, cough and watery eyes. It can have serious complications in some people. ",
        "urticaria" => "A raised, itchy rash that appears on the skin. Children are often affected by the condition, as well as women aged 30 to 60, and people with a history of allergies. Hives rashes usually improve within a few minutes to a few days.",
        "zika virus" => "A mosquito-borne virus, similar to dengue fever, yellow fever and West Nile virus. The infection is associated with a birth defect called microcephaly, which can affect babies born to people who become infected with Zika while pregnant.",
        "hepatitis" => "Inflammation of the liver. The liver is a vital organ that processes nutrients, filters the blood, and fights infections. When the liver is inflamed or damaged, its function can be affected. Heavy alcohol use, toxins, some medications, and certain medical conditions can cause hepatitis.",
        "hemorrhagic fever" => " infectious diseases that can cause severe, life-threatening illness. They can damage the walls of tiny blood vessels, making them leak, and can hamper the blood's ability to clot. The resulting internal bleeding is usually not life-threatening, but the diseases can be.",
        "tetanus" => "A serious disease of the nervous system caused by a toxin-producing bacterium. The disease causes muscle contractions, particularly of your jaw and neck muscles. Tetanus is commonly known as lockjaw. Severe complications of tetanus can be life-threatening. There's no cure for tetanus.",
        "urinary tract infection" => "An infection in any part of the urinary system.",
        "heart failure" => "Occurs when the heart muscle doesn't pump blood as well as it should. Blood often backs up and causes fluid to build up in the lungs and in the legs. The fluid buildup can cause shortness of breath and swelling of the legs and feet. Poor blood flow may cause the skin to appear blue or gray.",
        "migraine" => "a headache that can cause severe throbbing pain or a pulsing sensation, usually on one side of the head. It's often accompanied by nausea, vomiting, and extreme sensitivity to light and sound.",
    ];

    return $meanings[$sickness] ?? "No information available.";
}

$predictedSickness = "";
$suggestedTreatments = [];
$meaning = "";

if (isset($_POST['symptoms'])) {
    // Get symptoms input
    $symptoms = explode(",", strtolower($_POST['symptoms']));
    
    // Perform diagnosis
    list($predictedSickness, $suggestedTreatments) = performDiagnosis($symptoms);
    $meaning = getSicknessMeaning($predictedSickness);
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
    <link rel="stylesheet" href="../vendors/bootstrap-5.0.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <style>
        .generate-diagnosis-box[disabled="true"] {
            background-color: #D4D4D4;
            color: #fff !important;
            cursor: default;
        }
    </style>
</head>
<body>
    <div class="loader d-flex">
        <img src="images/loader.gif">
    </div>

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
                <div class="tags-container" id="tags-container">
                    <?php foreach(explode(",", htmlspecialchars($_POST['symptoms'])) as $symptom): ?>
                        <span class="tag"><?= trim($symptom) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <input type="text" id="symptoms-input" name="symptoms" placeholder="Type symptoms keywords..." autocomplete="off">
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
                        <p class="sub-text"><?= htmlspecialchars($meaning) ?></p>
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
        function recordTreatment() {
            <?php
            // Get the symptoms, diagnosis, and treatments
            $symptoms = isset($_POST['symptoms']) ? $_POST['symptoms'] : '';
            $diagnosis = $predictedSickness;
            $treatments = implode(", ", $suggestedTreatments);
            ?>

            // Redirect to the next page and pass the parameters
            window.location.href = 'treatment-record.php?symptoms=<?= urlencode($symptoms) ?>&diagnosis=<?= urlencode($diagnosis) ?>&treatments=<?= urlencode($treatments) ?>';
        }

        $(document).ready(function() {
            var symptoms = [];

            $('#symptoms-input').keypress(function(e) {
                if(e.which == 13) {
                    var symptom = $(this).val().trim();
                    if(symptom !== "" && !symptoms.includes(symptom)) {
                        symptoms.push(symptom);
                        $('#tags-container').append('<span class="tag">' + symptom + '</span>');
                        $(this).val('');
                    }
                }
            });
        });
    </script>
</body>
</html>

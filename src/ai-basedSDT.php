<?php
require_once ('includes/session-nurse.php');
require_once ('includes/connect.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Symptoms Diagnosis Tool</title>
    <link rel="icon" type="image/png" href="images/heart-logo.png">
    <link rel="stylesheet" href="styles/dboardStyle.css">
    <link rel="stylesheet" href="styles/modals.css">
    <link rel="stylesheet" href="../vendors/bootstrap-5.0.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <style>
        .generate-diagnosis-box[disabled="true"] {
            background-color: #D4D4D4;
            color: #fff !important;
            cursor: none;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <div class="loader d-flex">
        <img src="images/loader.gif">
    </div>

    <div class="main-content">
        <div class="overlay" id="overlay"></div>

        <?php include ('includes/sidebar/ai-basedSDT.php'); ?>

        <div class="content" id="content" style="overflow-x: hidden;">
            <div class="dashboard-header-container">
                <img src="images/ai-sdt-header.png" alt="Dashboard Header" class="dashboard-header">
                <div class="dashboard-text">
                    <p>AI-Based, <span class="bold">Symptoms</span></p>
                    <p class="bold" style="color: #E13F3D; font-size: 50px; font-family: 'Poppins', sans-serif;">
                        Diagnostic Tool</p>
                    <p style="color: black; font-size: 17px; font-family: 'Poppins', sans-serif; text-align: justify;">
                        Detects and generates possible diagnosis<br> based on patient symptoms.</p>
                </div>
            </div>

            <div class="left-header">
                <p style="color: #E13F3D;">Type symptoms...</p>
            </div>

            <div class="suggestion-row" style="display: flex; justify-content: flex-start; margin: 20px 0 20px 210px;">
                <img src="images/idea-icon.svg" alt="Suggestion Icon" class="idea-icon"
                    style="width: 20px; margin-right: 5px; margin-bottom: 2px;">
                <p style="color: gray; font-size: 12px; margin: 0; font-weight: 500;">Provide at least <b>3 symptoms</b>
                    to ensure an accurate diagnosis.
                    You can input up to <b>15 symptoms</b> for the most precise results.</p>
            </div>

            <div style="display: flex; justify-content: center;">
                <div id="tag-warning" style="color: #058789; display: none; font-style: 'Poppins'; font-weight: 600;">The limit has been reached. If you like to type again, please remove a tag.</div>
            </div>

            <!-- Keyword Tags Container -->
            <form id="diagnosis-form" method="post" action="generated-diagnosis.php">
                <div class="symptoms-input-container">
                    <input type="text" id="symptoms-input" name="symptom" placeholder="Type symptoms keywords..."
                        autocomplete="off" oninput="this.value = this.value.replace(/[0-9]/g, '')">
                    <input type="hidden" id="hidden-symptoms" name="symptoms">
                    <div class="tags-container" id="tags-container"></div>
                </div>

                <div class="generate-diagnosis-box" id="generate-diagnosis-box" disabled="true">
                    <div class="generate-diagnosis-text" id="generate-diagnosis-btn">Generate Diagnosis</div>
                </div>

                <input type="hidden" id="user-fullname" name="user-fullname"
                    value="<?php echo htmlspecialchars($_SESSION['full_name']); ?>">
            </form>

            <?php include ('includes/footer.php'); ?>
        </div> <!-- End of content -->

    </div> <!-- End of main-content -->

    <script src="../vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/script.js"></script>
    <script src="scripts/ai-sdt.js"></script>

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
            const content = document.querySelector('.main-content');
            content.style.visibility = 'visible'; // Use visibility to show content
        }
        simulateContentLoading();
    </script>

</body>

</html>
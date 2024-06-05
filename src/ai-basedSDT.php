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
            cursor: default;
        }
    </style>
</head>

<body>
    <div class="loader d-flex">
        <img src="images/loader.gif">
    </div>

    <div class="main-content">
        <div class="overlay" id="overlay"></div>

        <?php
        include ('includes/sidebar/ai-basedSDT.php');
        ?>

        <div class="content" id="content">
            <div class="ai-header-content">
                <div class="ai-header-image-container">
                    <img src="images/ai-header.svg" alt="AI Header" class="ai-header">
                </div>
                <div class="ai-header-text-container">
                    <div class="ai-header-text">
                        <div class="ai-text">
                            <p>AI-Based,<span class="bold"> Symptoms</span></p>
                            <p class="bold"
                                style="color: #E13F3D; font-size: 50px; font-family: 'Poppins', sans-serif;">
                                Diagnostic Tool</p>
                            <p
                                style="color: black; font-size: 17px; font-family: 'Poppins', sans-serif; text-align: justify;">
                                Detects and generates possible diagnosis <br> based on patient symptoms.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="left-header">
                <p style="color: #E13F3D">Type symptoms...</p>
            </div>

            <!-- Keyword Tags Container -->
            <form id="diagnosis-form" method="post" action="generated-diagnosis.php">
                <div class="symptoms-input-container">
                    <input type="text" id="symptoms-input" name="symptoms" placeholder="Type symptoms keywords..."
                        autocomplete="off">
                    <div class="tags-container" id="tags-container"></div>
                </div>

                <div class="generate-diagnosis-box" id="generate-diagnosis-box">
                    <div class="generate-diagnosis-text" id="generate-diagnosis-btn">Generate Diagnosis</div>
                </div>
            </form>

            <?php
            include ('includes/footer.php');
            ?>
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
                // Function to handle form submission
                function submitForm() {
                    var input = document.getElementById('symptoms-input').value.trim();

                    // Remove leading spaces and replace double spaces with single space
                    input = input.replace(/^\s+|\s{2,}/g, ' ');

                    var tags = document.querySelectorAll('.tag');
                    var symptomsString = input; // Initialize with text input
                    // Concatenate symptoms from tags
                    tags.forEach(function (tag) {
                        symptomsString += tag.textContent.trim() + ', ';
                    });
                    // Remove the trailing comma and whitespace
                    symptomsString = symptomsString.replace(/,\s*$/, '');
                    // Set the concatenated symptoms string as the value of the hidden input field
                    document.getElementById('symptoms-input').value = symptomsString;
                    // Submit the form if there's text input or tags
                    if (symptomsString.length > 0) {
                        document.getElementById('diagnosis-form').submit();
                    }
                }

                // Event listener for clicking the generate button
                document.getElementById('generate-diagnosis-btn').addEventListener('click', submitForm);

                // Function to toggle the button state
                function toggleButton() {
                    var input = document.getElementById('symptoms-input').value.trim();
                    var tags = document.querySelectorAll('.tag');
                    var button = document.getElementById('generate-diagnosis-btn');
                    var buttonBox = document.getElementById('generate-diagnosis-box');
                    // Check if there's input in the text field or if tags exist
                    if (input.length > 0 || tags.length > 0) {
                        // Enable the button if there's input or tags
                        button.removeAttribute('disabled');
                        buttonBox.removeAttribute('disabled');
                        console.log("yet")
                    } else {
                        // Disable the button if there's no input or tags
                        button.setAttribute('disabled', true);
                        buttonBox.setAttribute('disabled', true);
                        console.log("not yet")
                    }
                }

                // Call toggleButton initially to set button state on page load
                toggleButton();

                // Event listener for input field
                document.getElementById('symptoms-input').addEventListener('input', function (event) {
                    var input = this.value;
                    var cursorPosition = this.selectionStart;

                    // Check if the input starts with a space or has consecutive spaces
                    if (input.startsWith(' ') || input.includes('  ')) {
                        // Remove leading spaces and replace consecutive spaces with a single space
                        this.value = input.trim().replace(/\s{2,}/g, ' ');
                        // Adjust cursor position after modification
                        this.setSelectionRange(cursorPosition - 1, cursorPosition - 1);
                    }

                    toggleButton();
                });

                // Event listener for tags using event delegation
                document.getElementById('tags-container').addEventListener('click', function (event) {
                    if (event.target.classList.contains('tag') || event.target.classList.contains('close')) {
                        toggleButton(); // Call toggleButton when a tag is added or removed
                    }
                });
            </script>

</body>

</html>
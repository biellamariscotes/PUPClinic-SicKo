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
        <div class="dashboard-header-container">
                <img src="images/ai-sdt-header.jpg" alt="Dashboard Header" class="dashboard-header">
                <div class="dashboard-text" >
                    <p>AI-Based, <span class="bold">Symptoms</span></p>
                    <p class="bold" style="color: #E13F3D; font-size: 50px; font-family: 'Poppins', sans-serif;">Diagnostic Tool</p>
                    <p style="color: black; font-size: 17px; font-family: 'Poppins', sans-serif; text-align: justify;">Detects and generates possible diagnosis<br> based on patient symptoms.</p>
                </div>
            </div>

            <div class="left-header">
                <p style="color: #E13F3D">Type symptoms...</p>
            </div>

            <!-- Keyword Tags Container -->
            <form id="diagnosis-form" method="post" action="generated-diagnosis.php">
                <div class="symptoms-input-container">
                <input type="text" id="symptoms-input" name="symptom" placeholder="Type symptoms keywords..." autocomplete="off"
                oninput="this.value = this.value.replace(/[0-9]/g, '')">
                    <input type="hidden" id="hidden-symptoms" name="symptoms">
                    <div class="tags-container" id="tags-container"></div>
                </div>

                <div class="generate-diagnosis-box" id="generate-diagnosis-box">
                    <div class="generate-diagnosis-text" id="generate-diagnosis-btn">Generate Diagnosis</div>
                </div>
                <input type="hidden" id="user-fullname" name="user-fullname"
                    value="<?php echo htmlspecialchars($_SESSION['full_name']); ?>">
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
        const content = document.querySelector('.main-content');
        content.style.visibility = 'visible'; // Use visibility to show content
    }
    simulateContentLoading();
</script>
<!-- END OF LOADER -->

<script>
    
    function submitForm() {
    var input = document.getElementById('symptoms-input').value.trim();

    // Remove leading spaces and replace double spaces with single space
    input = input.replace(/^\s+|\s{2,}/g, ' ');

    var tags = document.querySelectorAll('.tag');
    var symptomsArray = []; // Array to store symptoms

    // Push symptoms from tags into the array
    tags.forEach(function(tag) {
    symptomsArray.push(tag.textContent.trim());
    });

    // Combine input field value with concatenated tags
    var symptomsString = input;
    if (symptomsArray.length > 0) {
    symptomsString += (input.length > 0 ? ', ' : '') + symptomsArray.join(', ');
    }

    // Set the concatenated symptoms string as the value of the hidden input field
    document.getElementById('hidden-symptoms').value = symptomsString;

    // Submit the form
    document.getElementById('diagnosis-form').submit();
 }


    // Event listener for clicking the generate button
    document.getElementById('generate-diagnosis-btn').addEventListener('click', function () {
    submitForm(); // Call your form submission function
    });


    // Function to toggle the button state
function toggleButton() {
    var input = document.getElementById('symptoms-input').value.trim();
    var tags = document.querySelectorAll('.tag');
    var button = document.getElementById('generate-diagnosis-btn');
    var buttonBox = document.getElementById('generate-diagnosis-box');


    // Limit the number of tags to 5
    if (tags.length >= 5) {
        // Disable input field if maximum tags are reached
        document.getElementById('symptoms-input').setAttribute('disabled', true);
    } else {
        // Enable input field if less than 5 tags
        document.getElementById('symptoms-input').removeAttribute('disabled');
    }

    // Enable the button after 3 symptoms/tags are added
    if (tags.length < 3) {
        button.setAttribute('disabled', true);
        buttonBox.setAttribute('disabled', true);
    } else {
        button.removeAttribute('disabled');
        buttonBox.removeAttribute('disabled');
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

    // Check if there are already 5 tags
    var tags = document.querySelectorAll('.tag');
    if (tags.length >= 5) {
        // If there are 5 tags, prevent any further input
        this.value = ''; // Clear the input value
        toggleButton(); // Update button state
    }

    toggleButton(); // Update button state
});

// Event listener for tags using event delegation
document.getElementById('tags-container').addEventListener('click', function (event) {
    if (event.target.classList.contains('tag') || event.target.classList.contains('close')) {
        // Remove the tag from DOM
        event.target.parentNode.removeChild(event.target);
        toggleButton(); // Update button state after tag removal
    }
});

</script>

</body>

</html>

    document.addEventListener('DOMContentLoaded', function () {
        toggleButton(); // Call toggleButton when the page loads
    });

    // Event listener for tags using event delegation
    document.getElementById('tags-container').addEventListener('click', function (event) {
        if (event.target.classList.contains('tag') || event.target.classList.contains('close')) {
            toggleButton(); // Call toggleButton when a tag is added or removed
        }
    });

    document.getElementById('symptoms-input').addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            const input = this.value.trim();
            if (input) {
                if (document.querySelectorAll('.tag').length < 15) { 
                    this.value = '';
                    toggleButton();
                } else {
                    toggleButton();
                }
            }
            toggleButton();
        }
    });


     document.addEventListener('click', function(event) {
        if (event.target.classList.contains('tag-remove-icon')) {
                toggleButton();
        }
    });


    // Function to toggle the button state
    function toggleButton() {
        const tags = document.querySelectorAll('.tag');
        const button = document.getElementById('generate-diagnosis-btn');
        const buttonBox = document.getElementById('generate-diagnosis-box');
        const tagWarning = document.getElementById('tag-warning');
        const inputField = document.getElementById('symptoms-input');

        console.log("Number of tags:", tags.length); // Log the number of tags


        if (tags.length < 3) {
            button.setAttribute('disabled', true);
            buttonBox.setAttribute('disabled', true);
            console.log("Button state: Disabled");
            console.log("Button state: Not disabled");
        } else if (tags.length === 15) {
            tagWarning.style.display = 'block';
            inputField.style.display = 'none';
            inputField.setAttribute('disabled', true);
            console.log("15 Limits reached");
        } else {
            button.removeAttribute('disabled');
            buttonBox.removeAttribute('disabled');
            inputField.removeAttribute('disabled');
            inputField.style.display = 'block';
            tagWarning.style.display = 'none';
            console.log("Button state: Not disabled");
        }
    }



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
    });

    // Function to submit the form
    function submitForm() {
        var input = document.getElementById('symptoms-input').value.trim();

        // Remove leading spaces and replace double spaces with single space
        input = input.replace(/^\s+|\s{2,}/g, ' ').replace(/[^a-zA-Z, ]/g, '');

        var tags = document.querySelectorAll('.tag');
        var symptomsArray = []; // Array to store symptoms

        // Push symptoms from tags into the array
        tags.forEach(function (tag) {
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

    // Function to log activity
    function logActivity() {
        var fullName = document.getElementById('user-fullname').value.trim();
        var action = " used the AI Symptoms Diagnostic Tool.";

        // AJAX call to log activity
        $.ajax({
            type: 'POST',
            url: 'log_activity.php', // Create a PHP file to handle logging
            data: { fullname: fullName, action: action },
            success: function (response) {
                console.log('Activity logged successfully.');
            },
            error: function (xhr, status, error) {
                console.error('Error logging activity:', error);
            }
        });
    }

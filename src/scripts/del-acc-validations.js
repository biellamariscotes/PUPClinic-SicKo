const current_pass = document.getElementById("current_pass");
const submitButton = document.getElementById("delete-btn");
const checkbox = document.querySelector("input[type=checkbox]");

// Function to check if all inputs are filled and the checkbox is checked
function checkInputs() {
    const currentPass = current_pass.value.trim();

    if (currentPass === "" || !checkbox.checked || document.querySelector('.is-invalid') !== null) {
        submitButton.disabled = true;
    } else {
        submitButton.disabled = false;
    }
}

// Add event listeners for input and checkbox change
current_pass.addEventListener("input", checkInputs);
checkbox.addEventListener("change", checkInputs);

// Initialize on page load
checkInputs();

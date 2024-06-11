const current_pass = document.getElementById("current_pass");
const new_pass = document.getElementById("new_pass");
const confirm_pass = document.getElementById("confirm_pass");
const submitButton = document.getElementById("save-btn");
const passwordError = document.getElementById("password-error");

// Check if all inputs are filled
function checkInputs() {
  const currentPass = current_pass.value.trim();
  const newPass = new_pass.value.trim();
  const confirmPass = confirm_pass.value.trim();

  if (currentPass === "" || newPass === "" || confirmPass === "" || document.querySelector('.is-invalid') !== null) {
    submitButton.disabled = true;
  } else {
    submitButton.disabled = false;
  }
}

// Check if passwords match
function checkPasswordMatch() {
  const newPass = new_pass.value.trim();
  const confirmPass = confirm_pass.value.trim();

  if (newPass !== confirmPass) {
    passwordError.textContent = "Passwords do not match";
    confirm_pass.classList.add("is-invalid");
    submitButton.disabled = true;
  } else {
    passwordError.textContent = "";
    confirm_pass.classList.remove("is-invalid");
    checkInputs(); // Re-check inputs to potentially enable the submit button
  }
}

// Add event listeners
current_pass.addEventListener("input", checkInputs);
new_pass.addEventListener("input", checkInputs);
confirm_pass.addEventListener("input", checkInputs);
confirm_pass.addEventListener("blur", checkPasswordMatch); // Triggered when the user finishes typing


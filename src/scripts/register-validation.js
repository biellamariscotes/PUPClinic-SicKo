const student_id = document.getElementById("student_id");
const first_name = document.getElementById("first_name");
const last_name = document.getElementById("last_name");
const sex = document.getElementById("sex");
const date = document.getElementById("date");
const course = document.getElementById("course");
const section = document.getElementById("section");
const email = document.getElementById("email");
const password = document.getElementById("password");
const submitButton = document.getElementById("register-btn");



// VALIDATION FOR WHITESPACE

function preventWhitespaceInput(event) {
  if (event.key === " " || event.code === "Space") {
    event.preventDefault();
  }
}

function preventDoubleSpacing(event, inputElement) {
  // Check if the pressed key is a space
  if (event.key === " " || event.code === "Space") {
    if (inputElement.value.endsWith(" ")) {
      event.preventDefault();
    }
  }
}

first_name.addEventListener("keydown", function (event) {
  preventDoubleSpacing(event, this);
});

student_id.addEventListener("keydown", preventWhitespaceInput);
last_name.addEventListener("keydown", preventWhitespaceInput);
sex.addEventListener("keydown", preventWhitespaceInput);
date.addEventListener("keydown", preventWhitespaceInput);
course.addEventListener("keydown", preventWhitespaceInput);
section.addEventListener("keydown", preventWhitespaceInput);
email.addEventListener("keydown", preventWhitespaceInput);
password.addEventListener("keydown", preventWhitespaceInput);
// -- END WHITESPACE


// EMAIL VALIDATION
email.addEventListener('input', function() {
    const inputValue = this.value.trim();
    const regex = /^[^\s@]+@(yahoo\.com|gmail\.com||iskolarngbayan\.pup\.edu\.ph|hotmail\.com|aol\.com|hotmail\.co\.uk|hotmail\.fr|msn\.com|yahoo\.fr|wanadoo\.fr|orange\.fr|comcast\.net|yahoo\.co\.uk|yahoo\.com\.br|yahoo\.co\.in|live\.com|rediffmail\.com|free\.fr|gmx\.de|web\.de|yandex\.ru|ymail\.com|libero\.it|outlook\.com|uol\.com\.br|bol\.com\.br|mail\.ru|cox\.net|hotmail\.it|sbcglobal\.net|sfr\.fr|live\.fr|verizon\.net|live\.co\.uk|googlemail\.com|yahoo\.es|ig\.com\.br|live\.nl)$/;


  
    if (inputValue === "") {
      // If the input is empty, remove the is-invalid class
      this.classList.remove("is-invalid");
    } else if (!regex.test(inputValue)) {
      // If the input doesn't match the desired format, apply Bootstrap's is-invalid class
      this.classList.add("is-invalid");
    } else {
      // If the input matches the desired format, remove Bootstrap's is-invalid class
      this.classList.remove("is-invalid");
    }
});
// ----- END OF EMAIL




// STUDENT ID FORMAT
student_id.addEventListener("input", function () {
    const inputValue = this.value.trim();
    const regex = /^\d{4}-\d{5}-SR-\d$/; // Regular expression for the desired format
    
    if (inputValue === '') {
        // If the input is empty, remove the is-invalid class
        this.classList.remove('is-invalid');
    } else if (!regex.test(inputValue)) {
        // If the input doesn't match the desired format, apply Bootstrap's is-invalid class
        this.classList.add('is-invalid');
    } else {
        // If the input matches the desired format, remove Bootstrap's is-invalid class
        this.classList.remove('is-invalid');
    }
});
// ------ END OF STUDENT ID FORMAT



// SPECIAL CHARACTERS
function preventSpecialChars(event) {
    const allowedChars = /^[a-zA-Z0-9@_.-]*$/;
    if (!allowedChars.test(event.key)) {
        event.preventDefault();
    }
}

student_id.addEventListener("keydown", preventSpecialChars);
last_name.addEventListener("keydown", preventSpecialChars);
sex.addEventListener("keydown", preventSpecialChars);
date.addEventListener("keydown", preventSpecialChars);
course.addEventListener("keydown", preventSpecialChars);
section.addEventListener("keydown", preventSpecialChars);
email.addEventListener("keydown", preventSpecialChars);
password.addEventListener("keydown", preventSpecialChars);
// ---- SPECIAL CHARACTERS









// BUTTON DISABLE
function checkInputs() {
  const studentID = student_id.value.trim();
  const firstName = first_name.value.trim();
  const lastName = last_name.value.trim();
  const sexVal = sex.value.trim();
  const birthdayVal = date.value.trim();
  const courseVal = course.value.trim();
  const blockSec = section.value.trim();
  const emailAdd = email.value.trim();
  const passwordVal = password.value.trim();

  // If any field is empty, disable the submit button
  if (
    studentID === "" ||
    firstName === "" ||
    lastName === "" ||
    sexVal === "" ||
    birthdayVal === "" ||
    courseVal === "" ||
    blockSec === "" ||
    emailAdd === "" ||
    passwordVal === "" || document.querySelector('.is-invalid') !== null
  ) {
    submitButton.disabled = true;
    console.log("Disabled");
  } else {
    submitButton.disabled = false;
  }
}

student_id.addEventListener("input", checkInputs);
last_name.addEventListener("input", checkInputs);
sex.addEventListener("input", checkInputs);
date.addEventListener("input", checkInputs);
course.addEventListener("input", checkInputs);
section.addEventListener("input", checkInputs);
email.addEventListener("input", checkInputs);
password.addEventListener("input", checkInputs);

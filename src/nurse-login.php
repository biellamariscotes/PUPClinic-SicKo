<?php
session_start();

date_default_timezone_set('Asia/Manila');

require_once ('includes/connect.php');

if (!$conn) {
    die("Database connection failed");
}

$login_failed = false; // Initialize the variable

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_btn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM nurse WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $nurse_id = $user['nurse_id'];
        $first_name = $user['first_name'];
        $last_name = $user['last_name'];
        $full_name = $first_name . ' ' . $last_name;

        if ($password == $user['password']) {
            $_SESSION['message'] = "You are now Logged In";
            $_SESSION['nurse_id'] = $nurse_id;

            // Record the activity log
            recordActivityLog($conn, $full_name, 'Logged in');

            header("Location: dashboard.php");
            exit();
        } else {
            $login_failed = true; // Set the flag to true
        }
    } else {
        $login_failed = true; // Set the flag to true
    }
}

function recordActivityLog($conn, $fullname, $action) {
    date_default_timezone_set('Asia/Manila'); // Set timezone to Philippines
    $date = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO activity_log (fullname, date, action) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $date, $action);
    $stmt->execute();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="icon" type="image/png" href="images/heart-logo.png">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/modals.css">
    <link rel="stylesheet" href="../vendors/bootstrap-5.0.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <style>
        input::-ms-reveal,
        input::-ms-clear {
            display: none;
        }
    </style>
</head>
<body>

    <div class="loader">
        <img src="images/loader.gif">
    </div>

    <!-- Log In Failed Modal -->
    <div class="modal" id="loginFailed" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 1.563rem;">
            <div class="modal-body d-flex flex-column justify-content-center align-items-center">
                    <div class="modal-middle-icon">
                        <img src="images/x-mark.gif" style="width: 9rem; height: auto;" alt="Failed Icon">
                    </div>
                    <div class="modal-title">Login Failed</div>
                    <div class="modal-subtitle" style="text-wrap: pretty; width: auto; font-size: 0.875rem; padding: 0 2rem 0 2rem;">Authentication failed. Please check your
                        credentials and try again.</div>

                    <div class="pt-3"></div>
                    <button type="button" class="btn btn-secondary" id="login-close-modal" data-dismiss="modal"
                        style="background-color: #E13F3D; 
                                font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem;">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="container-login-cst">
            <div class="logo-container">
                <img class="logo" src="images/sicko-logo.png" alt="Sicko Logo">
                <h2 style="font-family: 'Poppins';"><span style="color: #058789;">Sic</span><span style="color: #E13F3D;">Ko</span> | Sign In</h2>
            </div>

            <div class="form-container-cst">
                <form method="post" class="needs-validation" novalidate>
                    <div class="input-container">
                        <input type="email" name="email" id="emailInput" maxlength="254" required style="width: 100%;">
                        <label for="emailInput">Email<span style="color: #E13F3D; padding-left: 0.188rem;">*</span></label>
                    </div>

                    <div class="input-container">
                        <input type="password" name="password" id="passwordInput" maxlength="50" required 
                        style="border-radius: 10px 0 0 10px; padding-right: 5px; width: 75%;">
                        
                        <label for="passwordInput">Password<span style="color: #E13F3D; padding-left: 0.188rem;">*</span></label>
                        <div class="toggle-password" onclick="togglePassword()">
                            Show
                        </div>
                    </div>

                    <div class="button-container">
                        <button type="submit" name="login_btn" id="submitButton" disable>Sign In</button>
                    </div>
                </form>
            </div>
        </div>

        <img class="vector-red" src="images/vector-red.png" alt="Red Vector">
        <img class="vector-green" src="images/vector-green.png" alt="Green Vector">
    </div>
    <script src="../vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/script.js"></script>
    <script src="scripts/loader.js"></script>


    <script>
        // Get references to the input fields and the submit button
        const emailInput = document.getElementById('emailInput');
        const passwordInput = document.getElementById('passwordInput');
        const submitButton = document.getElementById('submitButton');

        function preventWhitespaceInput(event) {
            if (event.key === ' ' || event.code === 'Space') {
                event.preventDefault();
            }
        }

        emailInput.addEventListener('keydown', preventWhitespaceInput);
        passwordInput.addEventListener('keydown', preventWhitespaceInput);
        // Function to check if any input field is empty
        function checkInputs() {
            const emailValue = emailInput.value.trim();
            const passwordValue = passwordInput.value.trim();

            // If any field is empty, disable the submit button
            if (emailValue === '' || passwordValue === '') {
                submitButton.disabled = true;
                console.log("Disabled");
            } else {
                submitButton.disabled = false;
            }
        }

        // Add event listeners to input fields to trigger checkInputs function on input
        emailInput.addEventListener('input', checkInputs);
        passwordInput.addEventListener('input', checkInputs);

        // Initially check inputs on page load
        checkInputs();
    </script>

    <script>
        $(document).ready(function () {
            <?php if ($login_failed): ?> // Check if login failed
                $("#loginFailed").modal("show"); // Show modal if login failed
            <?php endif; ?>

            // Close the Modal with the close button
            $("#login-close-modal").click(function (event) {
                $("#loginFailed").modal("hide");
            });
        });

        function togglePassword() {
            var passwordInput = document.getElementById("passwordInput");
            var toggleIcon = document.querySelector(".toggle-password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.textContent = "Hide";
            } else {
                passwordInput.type = "password";
                toggleIcon.textContent = "Show";
            }
        }
    </script>

</body>
</html>

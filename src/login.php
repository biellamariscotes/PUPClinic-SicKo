<?php
session_start();

require_once ('includes/connect.php');

if (isset($_SESSION['patient_id'])) {
    header("Location: home.php");
    exit();
}

if (!$conn) {
    die("Database connection failed");
}

$login_failed = false; // Initialize the variable

if (isset($_POST['login_btn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM patient WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $patient_id = $user['patient_id'];

        if ($password == $user['password']) {
            $_SESSION['message'] = "You are now Logged In";
            $_SESSION['patient_id'] = $patient_id;

            header("Location: home.php");
            exit();

        } else {
            $login_failed = true;
        }
    } else {
        $login_failed = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="icon" type="image/png" href="images/heart-logo.png">
    <link rel="stylesheet" href="styles/modals.css">
    <link rel="stylesheet" href="../vendors/bootstrap-5.0.2/dist/css/bootstrap.css">
    <link rel="stylesheet" href="styles/register.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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

    <!-- Navigation Bar -->
    <div class="main-content">
        <div class="container pt-4">
            <div class="row nav-bar">
                <div class="col-md-6  d-flex align-items-center">
                    <img src="images/sicko-logo.png" class="me-3">
                    <div class="fw-bold fs-4 d-flex align-items-center text-center" style="align-self: center"><span
                            class="green">Sic</span><span class="red">Ko</span></div>
                </div>
                <div class="col-md-6  d-flex justify-content-end">
                    <a href="login.php"><button class="sign-in">Sign In</button></a>
                    <a href="register.php"><button class="sign-up">Register</button></a>
                </div>
            </div>
        </div>

        <!-- Information -->
        <div class="container">
            <div class="row register">
                <div class="col-12 info  d-flex justify-content-center">
                    <div class="container-login-cst">
                        <div class="logo-container d-flex flex-wrap justify-content-center">
                            <div class="d-flex flex-wrap justify-content-center">
                            <img class=" logo" src="images/heart-logo.png" alt="Sicko Logo">
                            </div>
                            <div>
                                <p class="fw-bold fs-4"><span class="green">Sic</span><span class="red">Ko</span> | Sign
                                    In
                                </p>
                            </div>
                        </div>

                        <div class="form-container-cst">
                            <form method="post" class="needs-validation" novalidate>
                                <div class="input-container">
                                    <input type="email" name="email" id="emailInput" maxlength="50" required>
                                    <label for="emailInput">Email</label>
                                </div>
                                <div class="input-container">
                                    <input type="password" name="password" id="passwordInput" maxlength="50" required
                                        class="padding-right: 50px">
                                    <label for="passwordInput">Password</label>
                                    <span class="toggle-password" onclick="togglePassword()">Show</span>
                                </div>
                                <div class="button-container">
                                    <button type="submit" name="login_btn" id="submitButton" disable>Sign In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <footer>
            <img class="vector-green fixed-bottom" src="images/vector-green.png" alt="Green Vector">
        </footer>
    </div>

    <div class="modal" id="loginFailed" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-middle-icon">
                        <img src="images/x-mark.gif" style="width: 9rem; height: auto;" alt="Failed Icon">
                    </div>
                    <div class="modal-title">Login Failed</div>
                    <div class="modal-subtitle" style="text-wrap: pretty; ">Authentication failed. Please check your
                        credentials and try again.</div>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" id="login-close-modal" data-dismiss="modal"
                        style="background-color: #E13F3D; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-top: 1rem;">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/script.js"></script>
    <script src="sscripts/loader.js"></script>

    <script>

        $(document).ready(function () {
            <?php if ($login_failed): ?> // Check if login failed
                $("#loginFailed").modal("show");
                // Show modal if login failed
            <?php endif; ?>

            // Close the Modal with the close button
            $("#login-close-modal").click(function (event) {
                $("#loginFailed").modal("hide");
            });
        });

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

        emailInput.addEventListener('input', checkInputs);
        passwordInput.addEventListener('input', checkInputs);

        checkInputs();
    </script>

    <script>
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
<?php
session_start();

require_once ('src/includes/connect.php');

if (!$conn) {
    die("Database connection failed");
}

$login_failed = false; // Initialize the variable

if (isset($_POST['login_btn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM nurse WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $nurse_id = $user['nurse_id'];

        if ($password == $user['password']) {
            $_SESSION['message'] = "You are now Logged In";
            $_SESSION['nurse_id'] = $nurse_id;

            header("Location: dashboard.php");
            exit();
        } else {
            $login_failed = true; // Set the flag to true
        }
    } else {
        $login_failed = true; // Set the flag to true
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Sign In</title>
    <link rel="icon" type="image/png" href="src/images/sicko-logo.png">
    <link rel="stylesheet" href="src/styles/style.css">
    <link rel="stylesheet" href="src/styles/modals.css">
    <link rel="stylesheet" href="vendors/bootstrap-5.0.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>

<body>
    <div class="container-login-cst">
        <div class="logo-container">
            <img class="logo" src="src/images/sicko-logo.png" alt="Sicko Logo">
            <h2><span style="color: #058789;">Sic</span><span style="color: #E13F3D;">Ko</span> | Sign In</h2>
        </div>

        <div class="form-container-cst">
            <form method="post" action="nurse-login.php" class="needs-validation" novalidate>
                <div class="input-container">
                    <input type="email" name="email" id="emailInput" maxlength="254" required>
                    <label for="emailInput">Email</label>
                </div>
                <div class="input-container">
                    <input type="password" name="password" id="passwordInput" maxlength="50" required>
                    <label for="passwordInput">Password</label>
                    <span class="toggle-password" onclick="togglePassword()">Show</span>
                </div>
                <div class="button-container">
                    <button type="submit" name="login_btn">Sign In</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Log In Failed Modal -->
    <div class="modal" id="loginFailed" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" >
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-middle-icon">
                        <i class="bi bi-x-circle-fill" style="color:#E13F3D; font-size:5rem"></i>
                    </div>
                    <div class="modal-title">Login Failed</div>
                    <div class="modal-subtitle" style="text-wrap: pretty;">Authentication failed. Please check your credentials and try again.</div>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" id="login-close-modal" data-dismiss="modal" style="background-color: #E13F3D; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem;">Close</button>
                </div>
            </div>
        </div>
    </div>

    <img class="vector-red" src="src/images/vector-red.png" alt="Red Vector">
    <img class="vector-green" src="src/images/vector-green.png" alt="Green Vector">
    <script src="vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="src/scripts/script.js"></script>
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

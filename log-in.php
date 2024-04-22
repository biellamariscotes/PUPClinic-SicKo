<?php
session_start();
if (isset($_SESSION['email'])) {
    header("location:dashboard.php");
    die();
}

require_once('connect.php');

// Check if the connection was successful
if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['login_btn'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $hashed_password = md5($password);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$hashed_password'";

    $result = mysqli_query($db, $sql);

    if ($result) {

        if (mysqli_num_rows($result) >= 1) {
            $_SESSION['message'] = "You are now Logged In";
            $_SESSION['email'] = $email;

            echo '<script language="javascript">';
            echo 'document.addEventListener("DOMContentLoaded", function() {';
            echo 'var successModal = document.getElementById("successModal");';
            echo 'successModal.style.display = "block";';
            echo '});';
            echo '</script>';

            // Redirect to home page after a short delay (adjust as needed)
            echo '<script>setTimeout(function(){ window.location.href = "dashboard.php"; }, 3000);</script>';

        } else {
            $_SESSION['message'] = "Email and Password combination incorrect";

            echo '<script language="javascript">';
            echo 'document.addEventListener("DOMContentLoaded", function() {';
            echo 'var modal = document.getElementById("errorModal");';
            echo 'modal.style.display = "block";';
            echo '});';
            echo '</script>';
        }
    } else {
        // Debugging message
        echo "Login query failed: " . mysqli_error($db) . "<br>";
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
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img class="logo" src="src/images/sicko-logo.png" alt="Sicko Logo">
            <h2><span style="color: #058789;">Sic</span><span style="color: #E13F3D;">Ko</span> | Sign In</h2>
        </div>
        
        <div class="form-container">
            <form method="post" action="log-in.php" class="needs-validation" novalidate>
                <div class="input-container required">
                    <input type="email" name="email" id="emailInput" class="form-control" required maxlength="254">
                    <label for="emailInput">Email</label>
                    <div class="invalid-feedback">Email should not contain spaces.</div>
                </div>
                <div class="input-container required">
                    <input type="password" name="password" id="passwordInput" class="form-control" required maxlength="50">
                    <label for="passwordInput">Password</label>
                    <div class="invalid-feedback">Password should not contain spaces.</div>
                </div>
                <div class="button-container">
                    <button type="submit" class="btn btn-primary">Sign In</button>
                </div>
            </form>
        </div>
    </div>
    <img class="vector-red" src="src/images/vector-red.png" alt="Red Vector">
    <img class="vector-green" src="src/images/vector-green.png" alt="Green Vector">
    <script src="src/scripts/script.js"></script>
    <script>
        // Add JavaScript for custom form validation
        (function() {
            'use strict';

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation');

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }

                        // Perform custom validation for spaces in email and password fields
                        var emailInput = document.getElementById('emailInput');
                        var passwordInput = document.getElementById('passwordInput');

                        if (emailInput.value.includes(' ')) {
                            emailInput.classList.add('is-invalid');
                        } else {
                            emailInput.classList.remove('is-invalid');
                        }

                        if (passwordInput.value.includes(' ')) {
                            passwordInput.classList.add('is-invalid');
                        } else {
                            passwordInput.classList.remove('is-invalid');
                        }

                        form.classList.add('was-validated');
                    }, false);
                });
        })();
    </script>
</body>
</html>

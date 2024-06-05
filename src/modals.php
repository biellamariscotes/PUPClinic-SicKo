<?php
session_start();

require_once ('includes/connect.php');

if (!$conn) {
    die("Database connection failed");
}

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
            $_SESSION['message'] = "Email and password combination incorrect";
            header("Location: nurse-login.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Email not found";
        header("Location: nurse-login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modals</title>
    <link rel="icon" type="image/png" href="images/heart-logo.png">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/modals.css">
    <link rel="stylesheet" href="../vendors/bootstrap-5.0.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

</head>

<body>
    <div class="container-login-cst">
        <div class="logo-container">
            <img class="logo" src="images/sicko-logo.png" alt="Sicko Logo">
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

        <!-- Saved Successfully Modal Button -->
        <button type="button" class="btn btn-primary" id="saved-successful" data-toggle="modal" data-target="#saved-successfully">
            Saved Successfully Modal Button
        </button>

    <!-- Saved Successfully Modal -->
    <div class="modal" id="saved-successfully" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-middle-icon">
                            <img src="images/check.gif" style="width: 7rem; height: auto;" alt="Check Icon">
                        </div>
                        <div class="modal-title" style="color: black;">Saved Successfully</div>
                        <div class="modal-subtitle" style="justify-content: center;">Your changes have been successfully saved!</div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Show Modal
            $(document).ready(function () {
                $("#saved-successful").click(function (event) {
                $("#saved-successfully").modal("show");
            });

            // Close the Modal with the close button
            $("#savedSuccessfully-close-modal").click(function (event) {
                $("#saved-successfully").modal("hide");
            });
            });
        </script>



    <!-- Log In Failed Modal Button -->
    <button type="button" class="btn btn-primary" id="login-failed" data-toggle="modal" data-target="#loginFailed">
        Log In Failed Modal Button
    </button>


    <!-- Log In Failed Modal -->
    <div class="modal" id="loginFailed" tabindex="-1" role="dialog">
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

    <!-- Log out Modal Button -->
    <button type="button" class="btn btn-primary" id="logout-modal" data-toggle="modal" data-target="#logOut">
        Log out Modal Button
    </button>

    <!-- Log out Modal -->
    <div class="modal" id="logOut" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-middle-icon">
                        <i class="bi bi-box-arrow-right" style="color:#058789; font-size:5rem"></i>
                    </div>
                    <div class="modal-title" style="color: black;">Are you leaving?</div>
                    <div class="modal-subtitle" style="justify-content: center; ">Are you sure you want to log out?</div>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" id="logout-close-modal" data-dismiss="modal" style="background-color: #777777; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-right: 1.25rem;">Cancel</button>
                    <button type="button" class="btn btn-secondary" id="logout-close-modal" data-dismiss="modal" style="background-color: #058789; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem;">Log Out</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Save Changes Modal Button -->
    <button type="button" class="btn btn-primary" id="saveChanges-modal" data-toggle="modal" data-target="#cancelSaveChanges">
        Save Changes Modal Button
    </button>


    <!-- Save Changes Modal -->
    <div class="modal" id="cancelSaveChanges" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-middle-icon">
                        <i class="bi bi-check-circle-fill" style="color:#058789; font-size:5rem"></i>
                    </div>
                    <div class="modal-title" style="color: black;">Save Changes</div>
                    <div class="modal-subtitle" style="justify-content: center; ">Are you sure you want to save your changes?</div>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" id="cancel-saveChanges-modal" data-dismiss="modal" style="background-color: #777777; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-right: 1.25rem;">Cancel</button>
                    <button type="button" class="btn btn-secondary" id="logout-close-modal" data-dismiss="modal" style="background-color: #058789; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem;">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Discard Changes Modal Button -->
    <button type="button" class="btn btn-primary" id="discard-modal" data-toggle="modal" data-target="discardModal">
        Discard Changes Modal Button
    </button>


    <!-- Discard Changes Modal -->
    <div class="modal" id="discardModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-middle-icon">
                        <i class="bi bi-exclamation-circle-fill" style="color:#E13F3D; font-size:5rem"></i>
                    </div>
                    <div class="modal-title" style="color: black;">Discard Changes</div>
                    <div class="modal-subtitle" style="justify-content: center; ">Are you sure you want to discard all changes?</div>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" id="cancel-discard-modal" data-dismiss="modal" style="background-color: #777777; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-right: 1.25rem;">Cancel</button>
                    <button type="button" class="btn btn-secondary" id="logout-close-modal" data-dismiss="modal" style="background-color: #E13F3D; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem;">Discard</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Form Modal Button -->
    <button type="button" class="btn btn-primary" id="cancelConfirm-modal" data-toggle="modal" data-target="#cancelConfirm">
        Submit Form Modal Button
    </button>


    <!-- Submit Form Modal -->
    <div class="modal" id="cancelConfirm" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-middle-icon">
                        <i class="bi bi-check-circle-fill" style="color:#058789; font-size:5rem"></i>
                    </div>
                    <div class="modal-title" style="color: black;">Confirmation</div>
                    <div class="modal-subtitle" style="justify-content: center; ">Are you sure you want to submit?</div>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" id="cancel-confirm-modal" data-dismiss="modal" style="background-color: #777777; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-right: 1.25rem;">Cancel</button>
                    <button type="button" class="btn btn-secondary" id="logout-close-modal" data-dismiss="modal" style="background-color: #058789; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem;">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal Button -->
    <button type="button" class="btn btn-primary" id="delete-modal" data-toggle="modal" data-target="#deleteModal">
        Delete Modal Button
    </button>


    <!-- Delete Modal -->
    <div class="modal" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-middle-icon">
                        <i class="bi bi-trash-fill" style="color:#E13F3D; font-size:5rem"></i>
                    </div>
                    <div class="modal-title" style="color: black;">Confirm Delete?</div>
                    <div class="modal-subtitle" style="justify-content: center; ">Are you sure you want to delete this?</div>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" id="cancel-delete-modal" data-dismiss="modal" style="background-color: #777777; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-right: 1.25rem;">Cancel</button>
                    <button type="button" class="btn btn-secondary" id="logout-close-modal" data-dismiss="modal" style="background-color: #E13F3D; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem;">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- <img class="vector-red" src="src/images/vector-red.png" alt="Red Vector"> -->
    <img class="vector-green" src="images/vector-green.png" alt="Green Vector">
    <script src="../vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/script.js"></script>



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

    <script>
        $(document).ready(function () {

            // Show Modal
            $("#login-failed").click(function (event) {
                $("#loginFailed").modal("show");
            });

            // Close the Modal with the close button
            $("#login-close-modal").click(function (event) {
                $("#loginFailed").modal("hide");
            });
        });
    </script>

    <script>
        $(document).ready(function () {

            // Show Modal
            $("#logout-modal").click(function (event) {
                $("#logOut").modal("show");
            });

            // Close the Modal with the close button
            $("#logout-close-modal").click(function (event) {
                $("#logOut").modal("hide");
            });
        });
    </script>

    <script>
        $(document).ready(function () {

            // Show Modal
            $("#saveChanges-modal").click(function (event) {
                $("#cancelSaveChanges").modal("show");
            });

            // Close the Modal with the close button
            $("#cancel-saveChanges-modal").click(function (event) {
                $("#cancelSaveChanges").modal("hide");
            });
        });
    </script>

    <script>
        $(document).ready(function () {

            // Show Modal
            $("#discard-modal").click(function (event) {
                $("#discardModal").modal("show");
            });

            // Close the Modal with the close button
            $("#cancel-discard-modal").click(function (event) {
                $("#discardModal").modal("hide");
            });
        });
    </script>

    <script>
        $(document).ready(function () {

            // Show Modal
            $("#cancelConfirm-modal").click(function (event) {
                $("#cancelConfirm").modal("show");
            });

            // Close the Modal with the close button
            $("#cancel-confirm-modal").click(function (event) {
                $("#cancelConfirm").modal("hide");
            });
        });
    </script>

    <script>
        $(document).ready(function () {

            // Show Modal
            $("#delete-modal").click(function (event) {
                $("#deleteModal").modal("show");
            });

            // Close the Modal with the close button
            $("#cancel-delete-modal").click(function (event) {
                $("#deleteModal").modal("hide");
            });
        });
    </script>


</body>

</html>
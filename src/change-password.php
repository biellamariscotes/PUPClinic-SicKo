<?php
session_start();
require_once ('includes/connect.php');

if (isset($_SESSION['patient_id'])) {
    $patient_id = $_SESSION['patient_id'];
    $stmt = $conn->prepare("SELECT * FROM patient WHERE patient_id = ?");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $first_initial = strtoupper(substr($row['first_name'], 0, 1));
    $last_initial = strtoupper(substr($row['last_name'], 0, 1));
    $initials = $first_initial . $last_initial;

    // Echo out data
    $name = ucfirst(strtolower($row['first_name'])) . " " . ucfirst(strtolower(substr($row['middle_name'], 0, 1))) . ". " . ucfirst(strtolower($row['last_name']));
    $student_id = $row['student_id']; ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile</title>
        <link rel="icon" type="image/png" href="images/heart-logo.png">
        <link rel="stylesheet" href="styles/student-style.css">
        <link rel="stylesheet" href="styles/modals.css">
        <link rel="stylesheet" href="../vendors/bootstrap-5.0.2/dist/css/bootstrap.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- Font Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">

        <style>
            a {
                text-decoration: none !important;
                cursor: pointer;
            }
        </style>
    </head>

    <body>
        <div class="loader">
            <img src="images/loader.gif">
        </div>

        <div class="main-content">

            <?php
            include ('includes/student-topbar.php');
            ?>

            <!-- Content -->
            <div class="wrap-main-content container col-9 my-5">
                <div class="row d-flex flex-wrap align-items-stretch">

                    <!-- Left Section -->
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-box-col mt-8 d-flex flex-column justify-content-center align-items-center">
                                    <p class="stud-initials fw-semibold green">
                                        <?= $initials; ?>
                                    </p>

                                    <div class="name-div">
                                        <p class="fw-bold green fs-4 pt-3 text-center"><?= $name; ?></p>
                                    </div>

                                    <p class="fw-normal fs-7 pt-2 text-center">STUDENT ID: <?= $student_id; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card-box-col-3 d-flex align-items-center justify-content-center">
                                    <div class="change-password-div">
                                        <a class="fw-bold green text-center fs-7 pe-none"><i
                                                class="bi bi-person-lock me-2"></i>Change your password
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Right Section -->
                    <div class="col-md-8">
                        <div class="calendar-card-box calendar-box">
                            <section class="student-information px-3">
                                <!-- Header -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex flex-column justify-content-center align-items-center">

                                            <div>
                                                <h2 class="fw-bold ls-1 red">Change <span class="green"> Password</span>
                                                </h2>
                                            </div>

                                            <p>To change your password, please input your current password:</p>
                                        </div>
                                    </div>

                                </div>

                                <div class="px-5 pt-4">
                                    <form id="change-password-form">
                                        <div class="row">
                                            <div class="col-12">
                                                <p class="faded-black-2 fs-7 fw-normal pb-2">Current Password</p>
                                                <input type="password" class="form-control" placeholder="•••••••••••••••••"
                                                    name="current_pass" id="current_pass">
                                            </div>
                                        </div>
                                        <div class="row pt-4">
                                            <div class="col-12">
                                                <p class="faded-black-2 fs-7 fw-normal pb-2">New Password</p>
                                                <input type="password" class="form-control" placeholder="•••••••••••••••••"
                                                    name="new_pass" id="new_pass">
                                            </div>
                                        </div>
                                        <div class="row pt-4">
                                            <div class="col-12">
                                                <p class="faded-black-2 fs-7 fw-normal pb-2">Confirm Password</p>
                                                <input type="password" class="form-control" placeholder="•••••••••••••••••"
                                                    name="confirm_pass" id="confirm_pass">
                                                <div id="password-error" class="text-danger fs-7 pt-2"></div>
                                            </div>
                                        </div>
                                        <div class="row pt-5">
                                            <div class="col-12 d-flex justify-content-center">
                                                <button type="submit" class="save-changes" id="save-btn" disabled>Save
                                                    Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>


            <?php
            include ('includes/footer.php');
            ?>

            <!-- Saved Successfully Modal -->
            <div class="modal" id="saved-successfully" tabindex="-1" role="dialog" data-bs-backdrop="static"
                data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body d-flex flex-column justify-content-center align-items-center">
                            <div class="modal-middle-icon">
                                <img src="images/check.gif" style="width: 7rem; height: auto;" alt="Check Icon">
                            </div>
                            <div class="modal-title" style="color: black;">Saved Successfully</div>
                            <div class="modal-subtitle" style="justify-content: center;">Your changes have been successfully
                                saved!</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Modal -->
            <div class="modal" id="loginFailed" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body d-flex flex-column justify-content-center align-items-center">
                            <div class="modal-middle-icon">
                                <i class="bi bi-x-circle-fill" style="color:#E13F3D; font-size:5rem"></i>
                            </div>
                            <div class="modal-title">Oops!</div>
                            <div class="modal-subtitle"
                                style="display: flex; justify-content: center; align-items: center; text-align: center; width: 100%;">
                            </div>

                            <div class="pt-3"></div>
                            <button type="button" class="btn btn-secondary" id="login-close-modal" data-dismiss="modal"
                                style="background-color: #E13F3D; 
                                font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem;">Close</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        </div>
        <script src="scripts/password-validations.js"></script>
        <script src="../vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="scripts/script.js"></script>
        <script src="scripts/loader.js"></script>

        <script>

            // Submit Form
            $(document).ready(function () {
                $('#change-password-form').on('submit', function (event) {
                    event.preventDefault(); // Prevent the default form submission

                    $.ajax({
                        url: 'includes/queries/change-pass-query.php',
                        type: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                $('#saved-successfully').modal('show'); // Show success modal
                                // Automatically close the modal after 2 seconds
                                setTimeout(function () {
                                    window.location.href = 'student-profile.php';
                                }, 2000);
                            } else {
                                $('#loginFailed .modal-subtitle').text(response.message); // Set the error message
                                $('#loginFailed').modal('show'); // Show error modal
                            }
                        },
                        error: function () {
                            $('#loginFailed .modal-subtitle').text('An error occurred. Please try again.');
                            $('#loginFailed').modal('show'); // Show error modal
                        }
                    });
                });
            });



            // Close Modal
            $(document).ready(function () {
                // Close the Modal with the close button
                $("#login-close-modal").click(function (event) {
                    $("#loginFailed").modal("hide");
                });
            });

        </script>

        <!-- <script src="scripts/loader.js"></script> -->
    </body>

    </html>

    <?php
} else {
    header("Location: login.php");
}
?>
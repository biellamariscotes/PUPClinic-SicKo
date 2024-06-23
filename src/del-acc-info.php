<?php
session_start();
require_once ('includes/connect.php');

if (isset($_SESSION['patient_id'])) {
    $patient_id = $_SESSION['patient_id'];
    include ('includes/queries/student-profile-query.php'); ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile</title>
        <link rel="icon" type="image/png" href="images/heart-logo.png">
        <link rel="stylesheet" href="styles/student-style.css">
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
                            <div class="col-12">
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
                                <div class="card-box-col-2 d-flex align-items-center justify-content-center">
                                    <div class="change-password-div">
                                        <a href="change-password.php" class="fw-semibold faded-black text-center fs-7"><i
                                                class="bi bi-person-lock me-2"></i>Change your
                                            password</a>
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
                                    <div class="col-8 ">
                                        <h2 class="fw-bold ls-1 red">Delete <span class="green"> Account</span></h2>
                                    </div>

                                </div>

                                <div class="p-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="fs-6 fw-normal">Deleting your account will:</p>

                                            <div class="enumeration">
                                                <p class="fw-normal pt-3">• Remove all personal information.</p>
                                                <p class="fw-normal">• Eliminate access to your medical records.</p>
                                                <p class="fw-normal">• Permanently disable your account or access.</p>
                                            </div>

                                            <p class="fs-6 fw-normal pt-3">
                                                However, your past treatment records will remain
                                                accessible to the authorized nurse.</p>

                                            <p class="fs-6 fw-normal pt-3">
                                                This action <span class="fw-bold">cannot be undone</span>. Are you sure you
                                                want to proceed?</p>

                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Account -->
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end pt-3">
                                        <a href="student-profile.php"><button class="cancel-btn ls-1">Go back to
                                                Settings</button></a>
                                        <a href="del-confirm-pass.php"><button class="red-warning-btn ls-1">Yes, I'd like to
                                                proceed</button></a>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>


            <?php
            include ('includes/footer.php');
            ?>
        </div>
        <script src="../vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            });
        </script>

        <script src="scripts/script.js"></script>
        <script src="scripts/loader.js"></script>
    </body>

    </html>

    <?php
} else {
    header("Location: login.php");
}
?>
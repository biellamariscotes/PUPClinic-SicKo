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
        <!-- <div class="loader">
            <img src="images/loader.gif">
        </div> -->

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
                                        <a href="change-password.php" class="fw-semibold faded-black text-center fs-7"><i class="bi bi-person-lock me-2"></i>Change your
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
                                        <h2 class="fw-bold ls-1 red">Profile <span class="green"> Settings</span></h2>
                                    </div>
                                    <div class="col-4 d-flex justify-content-end align-items-center">
                                        <a href="edit-student-profile.php"><button type="button" class="btn btn-outline-primary btn-sm">
                                                Edit <i class="fa-regular fa-pen-to-square ms-1"></i>
                                            </button></a>
                                    </div>

                                </div>

                                <div class="p-4">
                                    <!-- First Section: Name, Student ID, Sex -->
                                    <div class="row">
                                        <div class="col-5">
                                            <p class="faded-black-2 fs-7 fw-normal">Full Name</p>
                                            <p class="fw-semibold text-truncate"><?= $full_name; ?></p>
                                        </div>
                                        <div class="col-4">
                                            <p class="faded-black-2 fs-7 fw-normal">Student ID</p>
                                            <p class="fw-semibold"><?= $student_id; ?></p>
                                        </div>
                                        <div class="col-3">
                                            <p class="faded-black-2 fs-7 fw-normal">Sex</p>
                                            <p class="fw-semibold"><?= $sex; ?></p>
                                        </div>
                                    </div>

                                    <!-- Second Section: Email Address -->
                                    <div class="row pt-4">
                                        <div class="col-12">
                                            <p class="faded-black-2 fs-7 fw-normal">Email Address</p>
                                            <p class="fw-semibold text-truncate"><?= $email; ?></p>
                                        </div>
                                    </div>

                                    <!-- Third Section: Program, Section & Birthday -->
                                    <div class="row pt-4">
                                        <div class="col-5">
                                            <p class="faded-black-2 fs-7 fw-normal">Program</p>
                                            <p class="fw-semibold text-truncate"><?= $course; ?></p>
                                        </div>
                                        <div class="col-4 ">
                                            <p class="faded-black-2 fs-7 fw-normal">Block Section</p>
                                            <p class="fw-semibold"><?= $section; ?></p>
                                        </div>
                                        <div class="col-3">
                                            <p class="faded-black-2 fs-7 fw-normal">Birthday</p>
                                            <p class="fw-semibold"><?= $birthday; ?></p>
                                        </div>
                                    </div>

                                    <div class="row pt-4">
                                        <div class="col-12">
                                            <p class="faded-black-2 fs-7 fw-normal">Emergency Number <i
                                                    class="bi bi-info-circle blue-link ms-1" data-bs-toggle="tooltip"
                                                    data-bs-placement="right"
                                                    title="The emergency contact number will be called first in case of an emergency."></i>
                                            </p>
                                            <p class="fw-semibold">
                                                <?php if (!empty($emergency_no)): ?>
                                                    <?= htmlspecialchars($emergency_no); ?>
                                                <?php else: ?>
                                                    No contact number
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>


                                </div>

                                <!-- Delete Account -->
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <a href="del-acc-info.php"><button class="btn delete-link-btn ls-1">Delete Account</button></a>
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
        <!-- <script src="scripts/loader.js"></script> -->
    </body>

    </html>

    <?php
} else {
    header("Location: login.php");
}
?>
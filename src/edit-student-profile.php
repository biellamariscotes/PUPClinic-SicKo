<?php
session_start();
require_once ('includes/connect.php');

if (isset($_SESSION['patient_id'])) {
    $patient_id = $_SESSION['patient_id'];
    include ('includes/queries/edit-profile-query.php'); ?>
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
        <!-- <div class="loader">
            <img src="images/loader.gif">
        </div> -->

        <div class="main-content">
            <?php include ('includes/student-topbar.php'); ?>

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
                            <form method="POST" action="">
                                <section class="student-information px-3">
                                    <!-- Header -->
                                    <div class="row">
                                        <div class="col-12">
                                            <h2 class="fw-bold ls-1 red">Edit <span class="green"> Profile</span></h2>
                                        </div>
                                    </div>

                                    <div class="p-4">
                                        <!-- First Section: Name, Student ID, Sex -->
                                        <div class="row">
                                            <div class="col-4">
                                                <p class="faded-black-2 fs-7 fw-normal pb-2">First Name</p>
                                                <input type="text" class="form-control" placeholder="First Name"
                                                    name="first_name" id="first_name" value="<?= $first_name; ?>">
                                            </div>
                                            <div class="col-4">
                                                <p class="faded-black-2 fs-7 fw-normal pb-2">Middle Name</p>
                                                <input type="text" class="form-control" placeholder="Middle Name"
                                                    name="middle_name" id="middle_name" value="<?= $middle_name; ?>">
                                            </div>
                                            <div class="col-4">
                                                <p class="faded-black-2 fs-7 fw-normal pb-2">Last Name</p>
                                                <input type="text" class="form-control" placeholder="Last Name"
                                                    name="last_name" id="last_name" value="<?= $last_name; ?>">
                                            </div>
                                        </div>

                                        <!-- Second Section: Student ID, Email Address -->
                                        <div class="row pt-4">
                                            <div class="col-6">
                                                <p class="faded-black-2 fs-7 fw-normal pb-2">Student ID</p>
                                                <input type="text" class="form-control" placeholder="Student ID"
                                                    name="student_id" id="student_id" value="<?= $student_id; ?>">
                                            </div>
                                            <div class="col-6">
                                                <p class="faded-black-2 fs-7 fw-normal pb-2">Email Address</p>
                                                <input type="text" class="form-control" placeholder="Email Address"
                                                    name="email" id="email" value="<?= $email; ?>">
                                            </div>
                                        </div>

                                        <!-- Third Section: Program, Section & Sex -->
                                        <div class="row pt-4">
                                            <div class="col-4">
                                                <p class="faded-black-2 fs-7 fw-normal pb-2">Program</p>
                                                <select class="form-control" name="course" id="course">
                                                    <option selected hidden value=""><?= $course_name; ?></option>
                                                    <option value="BSA" <?= $course_name == 'BSA' ? 'selected' : ''; ?>>BSA
                                                    </option>
                                                    <option value="BSECE" <?= $course_name == 'BSECE' ? 'selected' : ''; ?>>
                                                        BSECE</option>
                                                    <option value="BSIE" <?= $course_name == 'BSIE' ? 'selected' : ''; ?>>BSIE
                                                    </option>
                                                    <option value="BSP" <?= $course_name == 'BSP' ? 'selected' : ''; ?>>BSP
                                                    </option>
                                                    <option value="BSIT" <?= $course_name == 'BSIT' ? 'selected' : ''; ?>>BSIT
                                                    </option>
                                                    <option value="BSED-ENG" <?= $course_name == 'BSED-ENG' ? 'selected' : ''; ?>>BSED - Eng</option>
                                                    <option value="BSED-MATH" <?= $course_name == 'BSED-MATH' ? 'selected' : ''; ?>>BSED - Math</option>
                                                    <option value="BSBA-MM" <?= $course_name == 'BSBA-MM' ? 'selected' : ''; ?>>BSBA - MM</option>
                                                    <option value="BSBA-HRM" <?= $course_name == 'BSBA-HRM' ? 'selected' : ''; ?>>BSBA - HRM</option>
                                                    <option value="BSEM" <?= $course_name == 'BSEM' ? 'selected' : ''; ?>>BSEM
                                                    </option>
                                                    <option value="BSMA" <?= $course_name == 'BSMA' ? 'selected' : ''; ?>>BSMA
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <p class="faded-black-2 fs-7 fw-normal pb-2">Block Section</p>
                                                <div class="input-group mb-3 d-flex flex-wrap justify-content-center">
                                                    <select class="form-control" name="section" id="section">
                                                        <option selected hidden value=""><?= $section; ?></option>
                                                        <option value="1-1" <?= $section == '1-1' ? 'selected' : ''; ?>>1-1
                                                        </option>
                                                        <option value="1-2" <?= $section == '1-2' ? 'selected' : ''; ?>>1-2
                                                        </option>
                                                        <option value="2-1" <?= $section == '2-1' ? 'selected' : ''; ?>>2-1
                                                        </option>
                                                        <option value="2-2" <?= $section == '2-2' ? 'selected' : ''; ?>>2-2
                                                        </option>
                                                        <option value="3-1" <?= $section == '3-1' ? 'selected' : ''; ?>>3-1
                                                        </option>
                                                        <option value="3-2" <?= $section == '3-2' ? 'selected' : ''; ?>>3-2
                                                        </option>
                                                        <option value="4-1" <?= $section == '4-1' ? 'selected' : ''; ?>>4-1
                                                        </option>
                                                        <option value="4-2" <?= $section == '4-2' ? 'selected' : ''; ?>>4-2
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <p class="faded-black-2 fs-7 fw-normal pb-2">Block Section</p>
                                                <div class="input-group mb-3 d-flex flex-wrap justify-content-center">
                                                    <select class="form-control" name="sex" id="sex">
                                                        <option selected hidden value=""><?= $sex; ?></option>
                                                        <option value="Male" <?= $sex == 'Male' ? 'selected' : ''; ?>>Male
                                                        </option>
                                                        <option value="Female" <?= $sex == 'Female' ? 'selected' : ''; ?>>
                                                            Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Fourth Section: Contact Number -->
                                        <div class="row pt-2">
                                            <div class="col-6">
                                                <p class="faded-black-2 fs-7 fw-normal pb-2"><span class="asterisk">*
                                                    </span>Emergency Number <i class="bi bi-info-circle blue-link ms-1"
                                                        data-bs-toggle="tooltip" data-bs-placement="right"
                                                        title="The emergency contact number will be called first in case of an emergency."></i>
                                                </p>
                                                <input type="text" class="form-control" placeholder="Emergency Number"
                                                    name="emergency_no" id="emergency_no" maxlength="11"
                                                    value="<?= $emergency_no; ?>">
                                            </div>

                                            <div class="col-6">
                                                <p class="faded-black-2 fs-7 fw-normal pb-2">Birthday</p>
                                                <input type="date" class="form-control" name="date" id="date"
                                                    min="yyyy-01-01" max="yyyy-12-31" value="<?= $birthday; ?>">
                                            </div>
                                        </div>

                                        <!-- Save Changes -->
                                        <div class="row pt-5">
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="save-changes" id="save-btn" disabled>Save
                                                    Changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal" id="saved-successfully" tabindex="-1" role="dialog" data-bs-backdrop="static"
                data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
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

            <?php include ('includes/footer.php'); ?>
        </div>

        <script src="scripts/edit-profile-validations.js"></script>
        <script src="../vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>


        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });
            });
        </script>

    </body>

    </html>

    <?php
} else {
    header("Location: login.php");
}
?>
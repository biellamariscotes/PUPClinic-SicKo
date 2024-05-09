<?php
session_start();
require_once ('src/includes/connect.php');

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

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <link rel="icon" type="image/png" href="src/images/heart-logo.png">
        <link rel="stylesheet" href="src/styles/dboardStyle.css">
        <link rel="stylesheet" href="src/styles/student-style.css">
        <link rel="stylesheet" href="vendors/bootstrap-5.0.2/dist/css/bootstrap.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    </head>

    <body>

        <div class="loader">
            <img src="src/images/loader.gif">
        </div>

        <div class="main-container">
            <div class="row nav-bar px-6">
                <div class="col-md-6 nav-bar-left d-flex align-items-center">
                    <img src="src/images/sicko-logo.png" class="me-4 my-2">
                    <div class="fw-bold fs-4 d-flex flex-column white" style="padding: 0;">
                        SicKo
                        <!-- <div class="fs-8 fw-light" style="padding: 0;">PUP-SRC CLINIC</div> -->
                    </div>
                </div>

                <div class="col-md-6 d-flex justify-content-end nav-bar-right">
                    <i class="fa-solid fa-bell white d-flex align-items-center" style="margin-right: 40px"></i>

                    <div class="ml-5 initials-cont d-flex align-items-center">
                        <p class="initials fs-5 fw-semibold" style="margin: 10px 10px 0px 0px"><?= $initials; ?></p></a>
                    </div>

                    <div class="dropdown user-profile d-flex align-items-center">
                        <button class="btn white" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= $row['first_name'] . ' ' . $row['last_name']; ?>

                            <i class="pl-1 fas fa-chevron-down main-color fs-6" style="margin-left: 10px"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <li class="px-xl-2"><a class="dropdown-item" href="#">Profile</a></li>
                            <li class="px-xl-2"><a class="dropdown-item" href="user-logout.php">Log Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Header -->
            <div class="container header col-md-9">
                <div class="row header">
                    <div class="col-md-8 pt-4">
                        <p class="bold fs-1">
                            <span class="green">Hello, </span><span class="red"><?php echo $row['first_name'] ?></span>
                        </p>

                        <p>
                            How have you been? See your records here.
                        </p>
                    </div>

                    <div class="col-md-4 home-illus">
                        <div class="image-container">
                            <img src="src/images/home-student.png">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="container col-9 mb-5">
                <div class="row d-flex flex-wrap align-items-stretch">
                    <div class="col-4">
                        <div class="card-box">
                            <div>
                                <p class="fs-3 green fw-semibold">Recent Visits</p>
                            </div>
                            <div>
                                <ul>
                                    <a href="#">
                                        <li>April 08, 2024</li>
                                    </a>
                                </ul>

                                <ul>
                                    <a href="#">
                                        <li>April 08, 2024</li>
                                    </a>
                                </ul>

                                <ul>
                                    <a href="#">
                                        <li>April 08, 2024</li>
                                    </a>
                                </ul>

                                <ul>
                                    <a href="#">
                                        <li>April 08, 2024</li>
                                    </a>
                                </ul>

                                <ul>
                                    <a href="#">
                                        <li>April 08, 2024</li>
                                    </a>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-8">
                        <div class="calendar-card-box calendar-box">
                            <div class="row">
                                <div class="col-6 calendar-cont" style="padding-right:3%">
                                    <div class="col-md-12">
                                        <div class="calendar calendar-first" id="calendar_first">
                                            <div class="calendar_header">
                                                <button class="switch-month switch-left"><i
                                                        class="fas fa-chevron-left"></i></button>
                                                <h2></h2>
                                                <button class="switch-month switch-right"> <i
                                                        class="fas fa-chevron-right"></i></button>
                                            </div>
                                            <div class="calendar_weekdays"></div>
                                            <div class="calendar_content"></div>
                                        </div>
                                    </div>

                                    <span class="vertical-line mr-5"></span>
                                </div>
                                <div class="col-6 d-flex flex-wrap align-items-center flex-grow-1">
                                    <div class="px-4 py-4">
                                        <div>
                                            <p class="fs-4 red fw-semibold">Treatment Records</p>
                                            <p class="fs-8 gray fw-semibold">March 27, 2024</p>
                                        </div>

                                        <div class="pt-3">
                                            <p class="fs-6 fw-semibold">Medicine Given: <span
                                                    class="fw-normal">Cetirizine</span></p>
                                            <p class="fs-6 fw-semibold pt-2">Symptoms: <span
                                                    class="fw-normal">Cetirizine</span></p>
                                            <p class="fs-6 fw-semibold pt-2">Diagnosis: <span class="fw-normal">Allergic
                                                    rhinitis</span></p>
                                        </div>

                                        <div class="pt-4 d-flex justify-content-end">
                                            <a href="" class="fs-7 blue-link fw-semibold">View full details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        include ('src/includes/footer.php');
        ?>
        <script src="vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="src/scripts/script.js"></script>
        <script src="src/scripts/calendar.js"></script>
        <script src="src/scripts/loader.js"></script>
    </body>

    </html>

    <?php
} else {
    header("Location: login.php");
}
?>
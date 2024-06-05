<?php
session_start();
require_once ('includes/connect.php');

if (isset($_SESSION['patient_id'])) {
    $patient_id = $_SESSION['patient_id'];
    $record_id = $_GET['record_id'];

    $stmt = $conn->prepare("SELECT * FROM patient WHERE patient_id = ?");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $first_initial = strtoupper(substr($row['first_name'], 0, 1));
    $last_initial = strtoupper(substr($row['last_name'], 0, 1));
    $initials = $first_initial . $last_initial;

    // Recent Visits
    $stmt = $conn->prepare("SELECT * FROM treatment_record WHERE patient_id = ?");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result_record = $stmt->get_result();
    $row_record = $result_record->fetch_assoc();
    $formatted_date = date("F j, Y", strtotime($row_record['date']));



    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Treatment Record</title>
        <link rel="icon" type="image/png" href="images/heart-logo.png">
        <link rel="stylesheet" href="styles/dboardStyle.css">
        <link rel="stylesheet" href="styles/student-style.css">
        <link rel="stylesheet" href="../vendors/bootstrap-5.0.2/dist/css/bootstrap.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />

        <style>
            .footer {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
            }
        </style>
    </head>

    <body>

         <div class="loader">
            <img src="images/loader.gif">
        </div>

        <div class="main-content">
            <div class="row nav-bar px-6">
                <div class="col-md-6 nav-bar-left d-flex align-items-center">
                    <a href="home.php"><img src="images/sicko-logo.png" class="me-4 my-2"></a>
                    <div class="fw-bold fs-4 d-flex flex-column white" style="padding: 0;">
                        SicKo
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

            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-10 container d-flex justify-content-start">
                        <span class="gray fs-7"><a href="home.php" style="text-decoration:none"><i class="fa-solid fa-arrow-left"></i>   Back to Home</a></span>
                    </div>
                </div>
            </div>

            <div class="mt-2 col-md-9 container">
                <div class="calendar-card-box calendar-box">
                    <div class="row  d-flex flex-wrap align-items-center">
                        <div class="col-6 align-items-center">
                            <p class="bold fs-1 d-flex justify-content-center">
                                <span class="green">Treatment </span><span class="red">Record</span>
                            </p>

                            <p class="d-flex justify-content-center fs-5">Date Prescribed: <span class="fw-semibold">
                                    <?php echo $formatted_date ?></span>
                            </p>

                            <span class="vertical-line mr-5"></span>
                        </div>


                        <div class="col-6 d-flex flex-wrap align-items-center flex-grow-1">
                            <div class="px-4 py-4">
                                <div class="pt-3">
                                    <p class="fs-6 fw-semibold">Medicine Given: <span
                                            class="fw-normal"><?php echo ucfirst($row_record['treatments']); ?></span>
                                    </p>
                                    <p class="fs-6 fw-semibold pt-2">Symptoms: <span
                                            class="fw-normal"><?php echo ucfirst($row_record['symptoms']); ?></span>
                                    </p>
                                    <p class="fs-6 fw-semibold pt-2">Diagnosis: <span
                                            class="fw-normal"><?php echo ucfirst($row_record['diagnosis']); ?></span>
                                    </p>
                                </div>

                                <!-- <div class="pt-4 d-flex justify-content-end">
                                    <a href="" class="fs-7 blue-link fw-semibold">View full details</a>
                                </div> -->
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <?php
            include ('includes/footer.php');
            ?>
        </div>
        <script src="../vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="scripts/script.js"></script>
        <script src="scripts/calendar.js"></script>
         <script src="scripts/loader.js"></script>
    </body>

    </html>

    <?php
} else {
    header("Location: login.php");
}
?>
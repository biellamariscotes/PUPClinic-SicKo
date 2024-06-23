<?php
session_start();
require_once ('includes/connect.php');

if (isset($_SESSION['patient_id'])) {
    $patient_id = $_SESSION['patient_id'];
    $record_id = isset($_GET['record_id']) ? $_GET['record_id'] : null;

    // Fetch patient details
    $stmt = $conn->prepare("SELECT * FROM patient WHERE patient_id = ?");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $first_initial = strtoupper(substr($row['first_name'], 0, 1));
        $last_initial = strtoupper(substr($row['last_name'], 0, 1));
        $initials = $first_initial . $last_initial;
    } else {
        $initials = '';
        $row = ['first_name' => '', 'last_name' => ''];
    }

    // Fetch recent treatment records
    $stmt = $conn->prepare("SELECT * FROM treatment_record WHERE patient_id = ? ORDER BY date DESC LIMIT 6");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result_record = $stmt->get_result();
    $records = [];

    if ($result_record->num_rows > 0) {
        while ($all_record = $result_record->fetch_assoc()) {
            $records[] = $all_record;
        }
    }

    // Fetch initial treatment record details based on record_id
    if ($record_id) {
        $stmt = $conn->prepare("SELECT * FROM treatment_record WHERE record_id = ?");
        $stmt->bind_param("i", $record_id);
        $stmt->execute();
        $result_initial_record = $stmt->get_result();
        $initial_record = $result_initial_record->fetch_assoc();
    } else {
        $initial_record = null;
    }

    // Fallback values if no initial record is found
    $formatted_date = $initial_record ? date("F j, Y", strtotime($initial_record['date'])) : '';
    $treatments = $initial_record ? ucfirst($initial_record['treatments']) : '';
    $symptoms = $initial_record ? ucfirst($initial_record['symptoms']) : '';
    $diagnosis = $initial_record ? ucfirst($initial_record['diagnosis']) : '';

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Treatment Record</title>
        <link rel="icon" type="image/png" href="images/heart-logo.png">
        <link rel="stylesheet" href="styles/student-style.css">
        <link rel="stylesheet" href="../vendors/bootstrap-5.0.2/dist/css/bootstrap.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
        <link rel="stylesheet" type="text/css"
            href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
        <link rel="stylesheet" type="text/css"
            href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />

        <style>
            .nav-bar a {
                text-decoration: none;
            }

            .card-box {
                background-color: #f8f9fa;
                border-radius: 8px;
                padding: 2rem 2.5rem !important;
                margin: 10px;
                cursor: pointer;
            }

            .card-box p {
                margin: 0 !important;
            }

            .card-box:hover {
                transform: translateY(-5px);
            }

            .selected-card {
                box-shadow: 0 0 4px 0px #058789;
            }

            .slick-prev,
            .slick-next {
                background-color: #fff;
                border-radius: 50%;
                border: 1px solid #ddd;
                color: #333;
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

            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-10 container d-flex justify-content-start">
                        <span class="gray fs-7"><a href="home.php" style="text-decoration:none"><i
                                    class="fa-solid fa-arrow-left"></i>   Back to Home</a></span>
                    </div>
                </div>
            </div>

            <div class="mt-2 col-md-9 container">
                <div class="">
                    <div class="row d-flex flex-wrap align-items-center treatment-slider"
                        style="margin: .5rem 1rem 0rem 1rem">
                        <?php
                        if (!empty($records)) {
                            foreach ($records as $all_record) {
                                $formatted_date = date("F j, Y", strtotime($all_record['date']));
                                echo "<div class='col card-box' data-record-id='" . $all_record['record_id'] . "'>";
                                echo "<p class='fs-7 text-center'>$formatted_date</p>";
                                echo "</div>";
                            }
                        } else {
                            echo "No treatment records found.";
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="mt-2 col-md-9 container">
                <div class="calendar-card-box calendar-box" id="treatment-details">
                    <div class="row d-flex flex-wrap align-items-center">
                        <div class="col-6 align-items-center">
                            <p class="fw-bold fs-1 d-flex justify-content-center">
                                <span class="green">Treatment </span><span class="red">Record</span>
                            </p>
                            <p class="d-flex justify-content-center fs-5">Date Prescribed: <span class="fw-semibold"
                                    id="date-prescribed"><?= $formatted_date ?></span></p>
                            <span class="vertical-line mr-5"></span>
                        </div>
                        <div class="col-6 d-flex flex-wrap align-items-center flex-grow-1">
                            <div class="px-4 py-4">
                                <div class="pt-3">
                                    <p class="fs-6 fw-semibold">Medicine Given: <span class="fw-normal"
                                            id="medicine-given"><?= $treatments ?></span></p>
                                    <p class="fs-6 fw-semibold pt-2">Symptoms: <span class="fw-normal"
                                            id="symptoms"><?= $symptoms ?></span></p>
                                    <p class="fs-6 fw-semibold pt-2">Diagnosis: <span class="fw-normal"
                                            id="diagnosis"><?= $diagnosis ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include ('includes/footer.php'); ?>
        </div>

        <script src="../vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
        <script src="scripts/script.js"></script>
        <script src="scripts/loader.js"></script>
        <script>
            $(document).ready(function () {
                // Initialize Slick Carousel
                $('.treatment-slider').slick({
                    slidesToShow: 5,
                    slidesToScroll: 1,
                    infinite: false,
                    arrows: true,
                    prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>'
                });

                // Function to scroll to the selected card
                function scrollToCard(recordId) {
                    var cardElement = $(`.card-box[data-record-id=${recordId}]`);
                    var cardIndex = cardElement.index();
                    $('.treatment-slider').slick('slickGoTo', cardIndex);
                }

                // Event handler for clicking on a card
                $('.card-box').on('click', function () {
                    var recordId = $(this).data('record-id');
                    $('.card-box').removeClass('selected-card');
                    $(this).addClass('selected-card');
                    fetchTreatmentDetails(recordId);
                    scrollToCard(recordId);
                });

                // Function to fetch treatment details via AJAX
                function fetchTreatmentDetails(recordId) {
                    $.ajax({
                        url: 'includes/queries/get-treatment-details.php',
                        type: 'GET',
                        data: { record_id: recordId },
                        success: function (response) {
                            var data = JSON.parse(response);
                            if (!data.error) {
                                $('#date-prescribed').text(data.date);
                                $('#medicine-given').text(data.treatments);
                                $('#symptoms').text(data.symptoms);
                                $('#diagnosis').text(data.diagnosis);
                                history.pushState(null, '', '?record_id=' + recordId);
                            } else {
                                alert(data.error);
                            }
                        }
                    });
                }

                // Check for record_id in URL and fetch details if present
                const urlParams = new URLSearchParams(window.location.search);
                const initialRecordId = urlParams.get('record_id');
                if (initialRecordId) {
                    fetchTreatmentDetails(initialRecordId);
                    $(`.card-box[data-record-id=${initialRecordId}]`).addClass('selected-card');
                    scrollToCard(initialRecordId);
                }

                $('#profile-link').on('click', function (event) {
                    window.location.href = 'student-profile.php';
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
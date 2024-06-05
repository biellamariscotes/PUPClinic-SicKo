<?php
require_once ('includes/session-nurse.php');
require_once ('includes/connect.php');

// Check if patient_id and record_id are provided in the URL
if (isset($_GET['patient_id']) && isset($_GET['record_id'])) {
    $patient_id = $_GET['patient_id'];
    $record_id = $_GET['record_id'];

    // Fetch patient details from the database based on patient_id
    $stmt = $conn->prepare("SELECT * FROM patient WHERE patient_id = ?");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if patient exists
    if ($result->num_rows === 1) {
        $patient = $result->fetch_assoc();

        // Fetch treatment record details from the database based on record_id and patient_id
        $stmt = $conn->prepare("SELECT * FROM treatment_record WHERE record_id = ? AND patient_id = ?");
        $stmt->bind_param("ss", $record_id, $patient_id);
        $stmt->execute();
        $record_result = $stmt->get_result();

        // Check if treatment record exists
        if ($record_result->num_rows === 1) {
            $record = $record_result->fetch_assoc();

            // Fetch previous record
            $stmt = $conn->prepare("SELECT * FROM treatment_record WHERE patient_id = ? AND record_id < ? ORDER BY record_id DESC LIMIT 1");
            $stmt->bind_param("ss", $patient_id, $record_id);
            $stmt->execute();
            $prev_result = $stmt->get_result();
            $prev_record = $prev_result->fetch_assoc();

            // Fetch next record
            $stmt = $conn->prepare("SELECT * FROM treatment_record WHERE patient_id = ? AND record_id > ? ORDER BY record_id ASC LIMIT 1");
            $stmt->bind_param("ss", $patient_id, $record_id);
            $stmt->execute();
            $next_result = $stmt->get_result();
            $next_record = $next_result->fetch_assoc();
        } else {
            // Redirect or handle error if treatment record does not exist
            header('Location: error.php');
            exit;
        }
    } else {
        // Redirect or handle error if patient does not exist
        header('Location: error.php');
        exit;
    }
} else {
    // Redirect or handle error if patient_id or record_id is not provided
    header('Location: error.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Treatment Record</title>
    <link rel="icon" type="image/png" href="images/heart-logo.png">
    <link rel="stylesheet" href="styles/dboardStyle.css">
    <link rel="stylesheet" href="../vendors\bootstrap-5.0.2\dist\css\bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
</head>

<style>
    .history-info-container {
        display: flex;
        flex-direction: column;
    }

    .treatment-history-info {
        margin-bottom: 1px;
        padding: 0;
    }

    .additional-info {
        font-family: 'Poppins', sans-serif;
        font-weight: bold;

        a:hover {
            color: #fff !important;
        }
    }
</style>

<body>
    <div class="overlay" id="overlay"></div>

    <?php include ('includes/sidebar/patients-treatment-record.php'); ?>

    <div class="content" id="content">

        <div class="container mt-5">
            <div class="row">
                <div class="col-md-10 container d-flex justify-content-start">
                        <span class="gray fs-7">
                        <a href="patients.php" style="text-decoration:none">   Patients   </a>
                        <i class="fa-solid fa-chevron-right"></i>    </span>

                        <span class="gray fs-7">
                        <a href="patients-treatment-record.php?patient_id=<?php echo $patient_id; ?>" style="text-decoration:none">   Treatment History   </a>
                        <i class="fa-solid fa-chevron-right"></i>    </span>

                        <span class="fw-bold fs-7">Record</span>
                </div>
            </div>
        </div>
        <div class="two-container">
            <div class="box-container left-box">
                <div class="profile-avatar">
                    <div class="avatar-circle">
                        <div class="initials">
                            <?php echo strtoupper(substr($patient['first_name'], 0, 1) . substr($patient['last_name'], 0, 1)); ?>
                        </div>
                    </div>
                    <div class="patient-info">
                        <div class="patient-name"><?php echo $patient['first_name'] . ' ' . $patient['last_name']; ?>
                        </div>
                        <div class="patient-id"><span>PATIENT ID:</span>
                            <span><?php echo $patient['patient_id']; ?></span>
                        </div>
                        <hr class="horizontal-line-separator">

                        <!-- New container for patient's other information -->
                        <div class="additional-info-container">
                            <div class="additional-info">
                                <div class="info-label">Email:</div>
                                <div class="info-value" class="text-truncate">
                                    <?php echo isset($patient['email']) ? $patient['email'] : 'No data'; ?>
                                </div>
                                <div class="info-label">Course:</div>
                                <div class="info-value">
                                    <?php echo isset($patient['course']) ? $patient['course'] : 'No data'; ?>
                                </div>
                                <div class="info-label">Section:</div>
                                <div class="info-value">
                                    <?php echo isset($patient['section']) ? $patient['section'] : 'No data'; ?>
                                </div>
                                <div class="info-label">Birthday:</div>
                                <div class="info-value">
                                    <?php echo isset($patient['birthday']) ? date("F d, Y", strtotime($patient['birthday'])) : 'No data'; ?>
                                </div>
                                <div class="info-label">Sex:</div>
                                <div class="info-value">
                                    <?php echo isset($patient['sex']) ? $patient['sex'] : 'No data'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-container right-box mb-5">
                <div class="right-box-container">
                    <div class="treatment-history-header" style="margin-bottom:-10px;">
                        <span style="color: #E13F3D;">Treatment</span>
                        <span style="color: #058789;">&nbsp;History</span>
                    </div>
                    <div class="history-info-container" style="padding-left: 40px;">
                        <?php
                        // Format the date
                        $formatted_date = date("F j, Y", strtotime($record['date']));
                        // Display treatment record details
                        echo '<span style="color: black; font-size: 18px; margin-bottom: 40px;">' . $formatted_date . '</span>';
                        echo "<p><span style=\"color: #058789; font-size: 25px; font-weight: bold;\">Symptoms</span> </p>";
                        echo '<p><span style="color: #494949; font-size: 20px;">' . ucfirst(strtolower($record['symptoms'])) . '</span></p>';
                        echo "<p><span style=\"color: #058789; font-size: 25px; font-weight: bold;\">Diagnosis</span></p>";
                        echo '<p><span style="color: #494949; font-size: 20px;">' .ucfirst(strtolower($record['diagnosis'])) . '</span></p>';
                        echo "<p><span style=\"color: #058789; font-size: 25px; font-weight: bold;\">Medicine Given:</span></p>";
                        echo '<p><span style="color: #494949; font-size: 20px;" >' . ucfirst(strtolower($record['treatments'])) . '</span></p>';
                        ?>
                    </div>
                    <div class="treatment-history-buttons">
                        <?php if ($prev_record): ?>
                            <a class="history-prev-button"
                                href="patient-record-date.php?patient_id=<?php echo $patient_id; ?>&record_id=<?php echo $prev_record['record_id']; ?>">Previous</a>
                        <?php endif; ?>
                        <?php if ($next_record): ?>
                            <a class="history-next-button"
                                href="patient-record-date.php?patient_id=<?php echo $patient_id; ?>&record_id=<?php echo $next_record['record_id']; ?>">Next</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include ('includes/footer.php'); ?>
    <script src="scripts/script.js"></script>
</body>

</html>
<?php
require_once('src/includes/session-nurse.php');
require_once('src/includes/connect.php');

// Check if the patient_id is provided in the URL
if(isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
    
    // Fetch patient details from the database based on patient_id
    $stmt = $conn->prepare("SELECT * FROM patient WHERE patient_id = ?");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if patient exists
    if($result->num_rows === 1) {
        $patient = $result->fetch_assoc();

        // Fetch treatment history of the patient from the database
        $stmt = $conn->prepare("SELECT * FROM treatment_record WHERE patient_id = ?");
        $stmt->bind_param("s", $patient_id);
        $stmt->execute();
        $treatment_result = $stmt->get_result();

        // Store treatment history in an array
        $treatment_history = array();
        while($row = $treatment_result->fetch_assoc()) {
            $treatment_history[] = $row;
        }
    } else {
        // Redirect or handle error if patient does not exist
        header('Location: error.php');
        exit;
    }
} else {
    // Redirect or handle error if patient_id is not provided
    header('Location: error.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Patient Treatment Record</title>
    <link rel="icon" type="image/png" href="src/images/sicko-logo.png"> 
    <link rel="stylesheet" href="src/styles/dboardStyle.css">
    <link rel="stylesheet" href="vendors\bootstrap-5.0.2\dist\css\bootstrap.min.css">
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
</style>

<body>
    <div class="overlay" id="overlay"></div>

    <?php include ('src/includes/sidebar/patients-treatment-record.php'); ?>

    <div class="content" id="content">
        <div class="two-container">
            <div class="box-container left-box">
                <div class="profile-avatar">
                    <img src="src/images/avatar.png" alt="Profile Avatar" class="avatar-image">
                    <div class="patient-info">
                        <div class="patient-name"><?php echo $patient['first_name'] . ' ' . $patient['last_name']; ?></div>
                        <div class="patient-id"><span>PATIENT ID:</span> <span><?php echo $patient['patient_id']; ?></span></div>
                        <hr class="horizontal-line-separator">
                        
                        <!-- New container for patient's other information -->
                        <div class="additional-info-container">
                            <div class="additional-info">
                                <div class="info-label">Email:</div>
                                <div class="info-value"><?php echo $patient['email']; ?></div>
                                
                                <!-- Add more patient information fields here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-container right-box">
                <div class="right-box-container">
                    <div class="treatment-history-header">
                        <span style="color: #E13F3D;">Treatment</span>
                        <span style="color: #058789;">&nbsp;History</span>
                    </div>
                    <div class="history-info-container">
                        <?php 
                            $recordsPerPage = 4;
                            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
                            $start = ($currentPage - 1) * $recordsPerPage;
                            $end = $start + $recordsPerPage;

                            for ($i = $start; $i < min($end, count($treatment_history)); $i++): // Display records based on pagination
                        ?>
                            <div class="treatment-history-info">
                                <div class="history-row">
                                    <div class="vertical-line-separator"></div>
                                    <div class="history-info">
                                        <div class="history-date" id=""><?php echo $treatment_history[$i]['date']; ?></div>
                                        <div class="diagnosis-tag">Diagnosis: 
                                            <div class="diagnosis-tag-box"><?php echo $treatment_history[$i]['diagnosis']; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <div class="treatment-history-buttons">
                        <?php if ($currentPage > 1): ?>
                            <a href="?patient_id=<?php echo $patient_id; ?>&page=<?php echo $currentPage - 1; ?>" class="history-prev-button">Previous</a>
                        <?php endif; ?>
                        <?php if ($end < count($treatment_history)): ?>
                            <a href="?patient_id=<?php echo $patient_id; ?>&page=<?php echo $currentPage + 1; ?>" class="history-next-button">Next</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include ('src/includes/footer.php'); ?>
    <script src="src/scripts/script.js"></script>
</body>
</html>

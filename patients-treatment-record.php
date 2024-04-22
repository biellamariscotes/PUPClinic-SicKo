<?php
require_once('src/includes/session-nurse.php');
require_once('src/includes/connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Patients Treatment Record</title>
    <link rel="icon" type="image/png" href="src/images/sicko-logo.png"> 
    <link rel="stylesheet" href="src/styles/dboardStyle.css">
</head>
<body>
    <div class="overlay" id="overlay"></div>
    <?php
    include ('src/includes/sidebar.php');
    ?>

        <div class="content" id="content">
            <div class="two-container">
            <div class="box-container left-box">
                <div class="profile-avatar">
                    <img src="src/images/avatar.png" alt="Profile Avatar" class="avatar-image">
                    <div class="patient-info">
                        <div class="patient-name">Apolo L. Trasmonte</div>
                        <div class="patient-id"><span>STUDENT ID:</span> <span>YYYY-00000-SR-0</span></div>
                            <hr class="horizontal-line-separator">
                            
                                <!-- New container for patient's other information -->
                                <div class="additional-info-container">
                                    <div class="additional-info">
                                        <div class="info-label">Email:</div>
                                        <div class="info-value">apololtrasmonte@gmail.com</div>
                                        
                                        <div class="info-label">Course:</div>
                                        <div class="info-value">Information Technology</div>
                                        
                                        <div class="info-label">Section:</div>
                                        <div class="info-value">3-1</div>
                                        
                                        <div class="info-label">Birthday:</div>
                                        <div class="info-value">December 25, 2003</div>
                                        
                                        <div class="info-label">Sex at Birth:</div>
                                        <div class="info-value">Male</div>
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
                                    <div class="treatment-history-info">
                                        <div class="history-row">
                                            <div class="vertical-line-separator"></div>
                                            <div class="history-info">
                                                <div class="history-date" id="">March 28, 2024</div>
                                                    <div class="diagnosis-tag">Diagnosis: 
                                                        <div class="diagnosis-tag-box">Allergic Rhinitis</div>
                                                    </div>
                                                </div>
                                            </div>
                                        

                                        <div class="history-row">
                                            <div class="vertical-line-separator"></div>
                                            <div class="history-info">
                                                <div class="history-date" id="">March 28, 2024</div>
                                                    <div class="diagnosis-tag">Diagnosis: 
                                                        <div class="diagnosis-tag-box">Allergic Rhinitis</div>
                                                    </div>
                                                </div>
                                            </div>

                                        <div class="history-row">
                                            <div class="vertical-line-separator"></div>
                                            <div class="history-info">
                                                <div class="history-date" id="">March 28, 2024</div>
                                                    <div class="diagnosis-tag">Diagnosis: 
                                                        <div class="diagnosis-tag-box">Allergic Rhinitis</div>
                                                    </div>
                                                </div>
                                            </div>
                                    

                                        <div class="history-row">
                                            <div class="vertical-line-separator"></div>
                                            <div class="history-info">
                                                <div class="history-date" id="">March 28, 2024</div>
                                                    <div class="diagnosis-tag">Diagnosis: 
                                                        <div class="diagnosis-tag-box">Allergic Rhinitis</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="treatment-history-buttons">
                                            <div class="history-prev-button">Previous</div>
                                            <div class="history-next-button">Next</div>
                                        </div>
                                </div>
                                
                        </div>
                    </div>
                </div>
    </div>

    <?php
    include ('src/includes/footer.php');
    ?>
    <script src="src/scripts/script.js"></script>
</body>
</html>

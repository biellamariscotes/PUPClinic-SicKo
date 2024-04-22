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

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="src/images/sidebar-logo.svg" alt="SicKo Logo" class="sidebar-logo">
        </div>

        <div class="sidebar-content">
            <img src="src/images/sidebar_design.png" alt="Sidebar Design" class="sidebar-design">
            <div class="new-treatment-box">
                <div class="new-treatment-text">
                    Create New <br> Treatment Record
                </div>
                <img src="src/images/add-button.svg" alt="Add Button Icon" class="add-button-icon" onclick="window.location.href='treatment-record.php'">
            </div>
            <!-- Dashboard -->
            <div class="menu-item">
                <img src="src/images/dashboard-icon.svg" alt="dashboard Icon" class="dashboard-icon">
                <span class="menu-text" onclick="window.location.href='dashboard.php'">Dashboard</span>
            </div>

            <!-- Ai-based SDT -->
            <div class="menu-item">
                <img src="src/images/pill.svg" alt="ai Icon" class="ai-icon">
                <span class="menu-text" onclick="window.location.href='ai-basedSDT.php'">AI-based SDT</span>
            </div>

            <!-- Patients -->
            <div class="menu-item">
                <img src="src/images/patients.svg" alt="patients Icon" class="patients-icon">
                <span class="menu-text" onclick="window.location.href='patients.php'">Patients</span>
            </div>

            <!-- Records -->
            <div class="menu-item">
                <img src="src/images/records.svg" alt="records Icon" class="records-icon">
                <span class="menu-text" onclick="window.location.href='treatment-record.php'">Records</span>
            </div>

            <!-- Med Reports -->
            <div class="menu-item">
                <img src="src/images/med-reports.svg" alt="medreports Icon" class="medreports-icon">
                <span class="menu-text" onclick="window.location.href='medreports.php'">Med Reports</span>
            </div>

            <!-- New header title for "Accounts" -->
            <div class="menu-header">
                <span class="menu-header-text">ACCOUNT</span>
            </div>

            <!-- New menu items -->
            <div class="menu-item">
                <img src="src/images/notifications.svg" alt="notifications Icon" class="notifications-icon">
                <span class="menu-text" onclick="window.location.href='notifications.php'">Notifications</span>
            </div>

            <div class="menu-item">
                <img src="src/images/settings.svg" alt="settings Icon" class="settings-icon">
                <span class="menu-text" onclick="window.location.href='settings.php'">Settings</span>
            </div>

            <div class="menu-item">
                <img src="src/images/log-out.svg" alt="log-out Icon" class="log-out-icon">
                <span class="menu-text" onclick="window.location.href='logout.php'">Log Out</span>
            </div>
        </div>
    </div>

    <div class="topnav" id="topnav">
        <div class="menu-toggle" id="menu-toggle" onclick="toggleSidebar()">
            <div id="menu-icon">&#9776;</div>
            <div id="close-icon">&times;</div>
        </div>
        <img src="src/images/topnav-logo.svg" alt="SicKo Logo" class="logo">
        </div>

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
                                </div>
                                
                        </div>
                    </div>
                </div>
    </div>

    <div class="footer">
    <div class="footer-text">
        <div class="footer-left">
            <div class="sicko-text">SicKo</div>
            <div class="separator"></div>
            <div class="pup-text">
                <p>Polytechnic University of the Philippines Santa Rosa Campus.</p>
                <p>&copy; 2024 PUP Sta. Rosa Campus Clinic. All Rights Reserved.</p>
            </div>
        </div>
        <div class="footer-right">
        <div class="footer-icons">
            <a href="link1.html"><img src="src/images/facebook.svg" class="footer-icon" alt="facebook"></a>
            <a href="link2.html"><img src="src/images/instagram.svg" class="footer-icon" alt="instagram"></a>
            <a href="link3.html"><img src="src/images/twitter.svg" class="footer-icon" alt="twitter"></a>
            <a href="link4.html"><img src="src/images/discord.svg" class="footer-icon" alt="discord"></a>
        </div>
            <p class="support-text">Support: bsit.teamsicko@gmail.com</p>
        </div>
    </div>
    </div>
    <script src="src/scripts/script.js"></script>
</body>
</html>

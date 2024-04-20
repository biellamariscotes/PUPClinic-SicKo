<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Dashboard</title>
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
                <img src="src/images/add-button.svg" alt="Add Button Icon" class="add-button-icon" onclick="window.location.href='ai-sdt.html'">
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
    <div class="dashboard-header-container">
        <img src="src/images/dashboard-header.png" alt="Dashboard Header" class="dashboard-header">
        <div class="dashboard-text">
            <p>Good day, <span class="bold">Nurse Sharwin!</span></p>
            <p class="bold" style="color: #E13F3D; font-size: 50px; font-family: 'Poppins', sans-serif;">Anong SicKo?</p>
            <p style="color: black; font-size: 17px; font-family: 'Poppins', sans-serif; text-align: justify;">See today’s health reports. Lorem ipsum,<br> lore ipsum. Lorem iupsum.</p>
        </div>
    </div>
    <div class="header-middle">Daily Treatment Record</div>
    <div class="table-container">
        <table class="dashboard-table">
            <tr>
                <th>Patient Name</th>
                <th>Course &amp; Year</th>
                <th>Diagnosis</th>
                <th>Time</th>
            </tr>
            <tr>
                <td>John Doe</td>
                <td>BSIT 3-1</td>
                <td>Fever</td>
                <td>10:00 AM</td>
            </tr>
            <tr>
                <td>Jane Smith</td>
                <td>BSP 1-2</td>
                <td>Common Cold</td>
                <td>11:30 AM</td>
            </tr>
            <tr>
                <td>Michael Johnson</td>
                <td>BSED 4-1</td>
                <td>Headache</td>
                <td>01:45 PM</td>
            </tr>
            <tr>
                <td>Sarah Lee</td>
                <td>BSECE 2-2</td>
                <td>Toothache</td>
                <td>03:20 PM</td>
            </tr>
            <tr>
                <td>David Brown</td>
                <td>BSA 3-2</td>
                <td>Eye Strain</td>
                <td>04:50 PM</td>
            </tr>
        </table>
    </div>

    <div class="header-middle">Clinic Health Tools</div>
    <div class="health-tools-container">
        <div class="health-tool health-tool-1" onclick="window.location.href='treatment-record.php'">
            <img src="src/images/health-checkup.svg" alt="Health Checkup Icon" class="health-icon">
            <div class="tool-text">
                <p class="tool-title">Patient Form</p>
                <p class="tool-subtitle">Treatment Record Form</p>
            </div>
        </div>
        <div class="health-tool health-tool-2" onclick="window.location.href='ai-basedSDT.php'">
            <img src="src/images/drawing.svg" alt="Health Checkup Icon" class="health-icon">
            <div class="tool-text">
                <p class="tool-title">AI-based SDT</p>
                <p class="tool-subtitle">Symptoms Diagnostic Tool</p>
            </div>
        </div>
        <div class="health-tool health-tool-3" onclick="window.location.href='med-reports.html'">
            <img src="src/images/health-calendar.svg" alt="Health Checkup Icon" class="health-icon">
            <div class="tool-text">
                <p class="tool-title">Med Reports</p>
                <p class="tool-subtitle">Medical Records Archive</p>
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

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
    <div class="topnav">
        <div class="menu-toggle">&#9776;</div> <!-- Hamburger Menu Icon -->
        <img src="src/images/topnav-logo.svg" alt="SicKo Logo" class="logo">
    </div>
    <div class="dashboard-header-container">
        <img src="src/images/dashboard-header.png" alt="Dashboard Header" class="dashboard-header">
        <div class="dashboard-text">
            <p>Good day, <span class="bold">Nurse Sharwin!</span></p>
            <p class="bold" style="color: #E13F3D; font-size: 50px; font-family: 'Poppins', sans-serif;">Anong SicKo?</p>
            <p style="color: black; font-size: 17px; font-family: 'Poppins', sans-serif; text-align: justify;">See today’s health reports. Lorem ipsum,<br> lore ipsum. Lorem iupsum.</p>
        </div>
    </div>
    <div class="dashboard-daily-treatment">
        <p class="bold" style="color: #E13F3D; font-size: 40px; font-family: 'Poppins', sans-serif; text-align: center;">Daily Treatment Record</p>
    </div>
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
    <div class="clinic-tools">
    <p class="bold" style="color: #E13F3D; font-size: 40px; font-family: 'Poppins', sans-serif; text-align: center;">Clinic Health Tools</p>
    <div class="health-tools-container">
        <div class="health-tool health-tool-1" onclick="window.location.href='patient-form.html'">
            <img src="src/images/health-checkup.svg" alt="Health Checkup Icon" class="health-icon">
            <div class="tool-text">
                <p class="tool-title">Patient Form</p>
                <p class="tool-subtitle">Treatment Record Form</p>
            </div>
        </div>
        <div class="health-tool health-tool-2" onclick="window.location.href='ai-sdt.html'">
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
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Patients</title>
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
    <div class="left-header" style="margin-top: 40px;">
        <p>
            <span style="color: #E13F3D;">List of</span>
            <span style="color: #058789;">Patients</span>
        </p>
    </div>

    <!-- Table Container -->
        <div class="table-container" id="">
            <table class="dashboard-table" style="margin-bottom: 80px;">
                <tr>
                    <th>Patient Name</th>
                    <th>Course</th>
                    <th>Section</th>
                    <th>Gender</th>
                </tr>
                <tr>
                    <td class="nameColumn" onclick="window.location.href='patients-treatment-record.php'">Apolo L. Trasmonte</td>
                    <td>Information Technology</td>
                    <td>BSIT 3-1</td>
                    <td>Male</td>
                </tr>
                <tr>
                    <td class="nameColumn" onclick="redirectToInfoPage()">Mikaela Tahum</td>
                    <td>Information Technology</td>
                    <td>BSIT 3-1</td>
                    <td>Female</td>
                </tr>
                <tr>
                    <td class="nameColumn" onclick="redirectToInfoPage()">Biella Requina</td>
                    <td>Information Technology</td>
                    <td>BSIT 3-1</td>
                    <td>Female</td>
                </tr>
                <tr>
                    <td class="nameColumn" onclick="redirectToInfoPage()">Andrei Matibag</td>
                    <td>Information Technology</td>
                    <td>BSIT 3-1</td>
                    <td>Male</td>
                </tr>
                <tr>
                    <td class="nameColumn" onclick="redirectToInfoPage()">Bobby Morante</td>
                    <td>Information Technology</td>
                    <td>BSIT 3-1</td>
                    <td>Male</td>
                </tr>
                <tr>
                    <td colspan="4"> <!-- Use colspan to span across all columns -->

                        <!-- Inside the table button container -->
                        <div class="table-button-container">
                            <button class="delete-button">
                                <img src="src/images/trash-icon.svg" alt="Delete Icon" class="delete-icon">
                                Delete
                            </button>

                            <!-- Sorting and Pagination Container -->
                            <div class="sorting-pagination-container">
                                <!-- Sorting button box -->
                                <div class="sorting-button-box" id="sortingButtonBox">
                                    <!-- Sort text -->
                                    Sort by:
                                    <select id="sortCriteria" style="font-family: 'Poppins', sans-serif; font-weight: bold;">
                                        <option value="name">Name</option>
                                        <option value="course">Course</option>
                                        <option value="section">Section</option>
                                        <option value="gender">Gender</option>
                                    </select>
                                </div>
                                <!-- Pagination buttons -->
                                <div class="pagination-buttons">
                                    <!-- Previous button -->
                                    <button class="pagination-button" id="previousButton">
                                        &lt;
                                    </button>
                                    <!-- Next button -->
                                    <button class="pagination-button" id="nextButton">
                                        &gt;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
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

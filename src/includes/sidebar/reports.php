<?php

/**
 * Includes header and side navigation bar.
 *
 */
?>


<div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="images/sidebar-logo.svg" alt="SicKo Logo" class="sidebar-logo">
        </div>

        <div class="sidebar-content">
            <img src="images/sidebar_design.png" alt="Sidebar Design" class="sidebar-design">
            <div class="new-treatment-box" onclick="window.location.href='treatment-record.php'">
                <div class="new-treatment-text">
                    Create New <br> Treatment Record
                </div>
                <img src="images/add-button.svg" alt="Add Button Icon" class="add-button-icon"
                    >
            </div>
            <!-- Dashboard -->
            <div class="menu-item">
                <img src="images/dashboard-icon.svg" alt="dashboard Icon" class="dashboard-icon">
                <span class="menu-text" onclick="window.location.href='dashboard.php'">Dashboard</span>
            </div>

            <!-- Ai-based SDT -->
            <div class="menu-item">
                <img src="images/pill.svg" alt="ai Icon" class="ai-icon">
                <span class="menu-text" onclick="window.location.href='ai-basedSDT.php'" >AI-based SDT</span>
            </div>

            <!-- Patients -->
            <div class="menu-item">
                <img src="images/patients.svg" alt="patients Icon" class="patients-icon">
                <span class="menu-text" onclick="window.location.href='patients.php'">Patients</span>
            </div>

            <!-- Records -->
            <div class="menu-item">
                <img src="images/records.svg" alt="records Icon" class="records-icon">
                <span class="menu-text" onclick="window.location.href='records.php'">Records</span>
            </div>

            <!-- Med Reports -->
            <div class="menu-item">
                <img src="images/med-reports.svg" alt="medreports Icon" class="medreports-icon">
                <span class="menu-text" onclick="window.location.href='reports.php'" style="color: #058789;">Med Reports</span>
            </div>

            <!-- New header title for "Accounts" -->
            <div class="menu-header">
                <span class="menu-header-text">ACCOUNT</span>
            </div>

           <!-- New menu items -->
           <div class="menu-item">
                <img src="images/activity-icon.svg" alt="Activity Icon" class="activity-icon">
                <span class="menu-text" onclick="window.location.href='activity.php'">Activity</span>
            </div>

            <div class="menu-item">
                <img src=images/settings.svg" alt="settings Icon" class="settings-icon">
                <span class="menu-text" onclick="window.location.href='edit-profile.php'">User Settings</span>
            </div>

            <div class="menu-item">
                <img src="images/log-out.svg" alt="log-out Icon" class="log-out-icon">
                <span class="menu-text" onclick="window.location.href='logout.php'">Log Out</span>
            </div>
        </div>
    </div>
    <div class="topnav" id="topnav">
        <div class="menu-toggle" id="menu-toggle" onclick="toggleSidebar()">
            <div id="menu-icon">&#9776;</div>
            <div id="close-icon">&times;</div>
        </div>
        <img src="images/topnav-logo.svg" alt="SicKo Logo" class="logo">
    </div>
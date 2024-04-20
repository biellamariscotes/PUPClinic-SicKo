<?php
session_start();

require_once('connect.php');

// Initialize session variables
$last_name_value = '';
$first_name_value = '';
$middle_name_value = '';
$email_value = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $last_name = ucwords(strtolower(mysqli_real_escape_string($conn, $_POST['lastName'])));
    $first_name = ucwords(strtolower(mysqli_real_escape_string($conn, $_POST['firstName'])));
    $middle_name = ucwords(strtolower(mysqli_real_escape_string($conn, $_POST['middleName'])));
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Update user details in the database
    $sql = "UPDATE users SET last_name='$last_name', first_name='$first_name', middle_name='$middle_name' WHERE email='{$_SESSION['email']}'";

    if (mysqli_query($conn, $sql)) {
        // Redirect or display success message
        // header('Location: success.php'); // Uncomment this line to redirect to a success page after form submission
        // echo "Details updated successfully."; // Uncomment this line to display a success message
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

// Retrieve user details for pre-filling the form
if (!empty($email)) {
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_name_value = $row['last_name'];
        $first_name_value = $row['first_name'];
        $middle_name_value = $row['middle_name'];
        $email_value = $row['email'];
    }
} else {
    // Handle the case when $_SESSION['email'] is not set
    // For example, you might want to redirect the user to a login page.
    // header("Location: login.php");
    // exit();
}


// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Edit Profile</title>
    <link rel="icon" type="image/png" href="src/images/sicko-logo.png"> 
    <link rel="stylesheet" href="src/styles/dboardStyle.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/styles/dboardStyle.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<style>
  .main-content {
    padding-left: 200px;
    padding-right: 130px;
  }

  .title {
    margin-bottom: 30px
  }

  .form-control {
    border-radius: 12px;
    height: 60px;
  }


</style>
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

  <main id="content" class="mt-5 content">
    <div class="container-fluid main-content">
      <div class="title">
        <span style="color: red; font-size: 30px"><b>Edit</span>
        <span class="fw-bolder" style="color: #058789; font-size: 30px;">Profile</span></b><br/>
        <span style="font-size: small;">Update your information</span>
      </div>

      <form class="needs-validation" novalidate>
      <div class="form-row">
          <div class="form-group col-md-4">
              <input type="text" name="last_name" class="form-control" id="lastName" placeholder="Last Name" required>
              <div class="invalid-feedback">Please enter your last name.</div>
          </div>
          <div class="form-group col-md-4">
              <input type="text" name="first_name" class="form-control" id="firstName" placeholder="First Name" required>
              <div class="invalid-feedback">Please enter your first name.</div>
          </div>
          <div class="form-group col-md-4">
              <input type="text" name="middle_name" class="form-control" id="middleName" placeholder="Middle Name" required>
              <div class="invalid-feedback">Please enter your middle name.</div>
          </div>
      </div>
      <div class="form-group">
          <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
          <div class="invalid-feedback">Please confirm your email</div>
      </div>
      <div class="form-group">
          <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
          <div class="invalid-feedback">Please confirm your password</div>
          <i class="bi bi-eye-slash-fill eye-icon" id="togglePassword"></i>
      </div>
      <div class="form-group">  
          <input type="password" name="confirm_password" class="form-control" id="confirmPassword" placeholder="Confirm Password" required>
          <div class="invalid-feedback">Please confirm your Confirm Password</div>
          <i class="bi bi-eye-slash-fill eye-icon" id="toggleConfirmPassword"></i>
      </div>
      <div>
        <center>
          <button class="btn btn-secondary" type="button" style="background-color: #6c757d;">Cancel</button>
          <button class="btn btn-primary" type="submit" style="background-color: #058789;">Save Changes</button>  
        </center>
      </div>
  </form>


    </div>
  </main> 

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
      function toggleSidebar() {
          document.getElementById("sidebar").classList.toggle("active");
          document.getElementById("content").classList.toggle("active");
      }

      // Toggle Password Visibility
      const togglePassword = document.querySelector('#togglePassword');
      const password = document.querySelector('#password');

      togglePassword.addEventListener('click', function (e) {
          // toggle the type attribute
          const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
          password.setAttribute('type', type);
          // toggle the eye slash icon
          this.classList.toggle('bi-eye-fill');
          this.classList.toggle('bi-eye-slash-fill');
      });

      const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
      const confirmPassword = document.querySelector('#confirmPassword');

      toggleConfirmPassword.addEventListener('click', function (e) {
          // toggle the type attribute
          const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
          confirmPassword.setAttribute('type', type);
          // toggle the eye slash icon
          this.classList.toggle('bi-eye-fill');
          this.classList.toggle('bi-eye-slash-fill');
      });

  </script>

</body>
</html>

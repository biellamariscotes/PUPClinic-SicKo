<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
  />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <title>Edit Profile</title>
</head>

<style>
    body {
        font-family: poppins;
        padding-left: 200px;
        padding-right: 200px;
        padding-top: 50px;
        background-color: #F4F4F4;
    }

    .card {
        margin: 40px;
        padding: 10px;
    }


    .fw-bolder {
        margin-left: 10px;
        font-size: 30px;
    }

    .fw-light {
      margin-left: 10px;
    }

    footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #058789;
        padding: 10px 20px;
        text-align: center;
    }

    .copyright {
        font-size: 25px;
    }

    .main-content {
        height: 600px;
        /* Adjust height as needed */
        overflow: auto;
    }

    .sicko-header {
        margin-left: 20px;
    }

    .sicko {
        margin-left: 2px;
    }

    .bi-three-dots-vertical {
        margin-left: 600px;
    }

    .title {
      margin-top: 20px;
    }

    .form {
      margin-top: 10px;
    }

    .header{
      padding: 5px;
    }

    .form-control {
      border-radius: 10px; /* Adjust the radius as needed */
      box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1); /* Adjust shadow as needed */
    }

    .cancel {
      background-color: #818589;
      color: white;
      border-radius: 12px;
      width: 60%;
      margin-left: 190px;
      margin-top: 20px;
    }

    .submit {
      background-color: #058789;
      color: white;
      border-radius: 12px;
      width: 60%;
      margin-top: 20px;
    }

    /* Eye Icon Styling */
    .eye-icon {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      cursor: pointer;
    }

    /* Sidebar Styling */
    .sidebar {
      position: fixed;
      left: -200px;
      top: 0;
      height: 100%;
      width: 200px;
      background-color: white;
      padding-top: 50px;
      transition: left 0.3s ease-in-out;
    }

    .sidebar ul {
      list-style-type: none;
      padding: 0;
    }

    .sidebar li {
      padding: 10px;
      color: white;
    }

    .sidebar a {
      color: black;
      text-decoration: none;
    }

    .sidebar a:hover {
      text-decoration: underline;
    }

    .content {
      margin-left: 0;
      padding: 20px;
      transition: margin-left 0.3s ease-in-out;
    }

    .menu-toggle {
      position: fixed;
      left: 10px;
      top: 10px;
      font-size: 24px;
      cursor: pointer;
      z-index: 1000;
      color: white;
    }

</style>


<body>
  
  <header id="header" class="header fixed-top d-flex align-items-center justify-content-center" style="background-color: #058789;">
    <!-- Your header content -->
    <div>
      <div class="menu-toggle" id="menu-toggle">
        <i class="bi bi-list"></i>
      </div>
      <!-- Centered Image and Text -->
      <div class="d-flex align-items-center">
        <img src="images/Sicko Logo.png" alt="" width="50" height="50" class="sicko-header">
        <div>
          <div style="margin-bottom: -14px;">
            <span class="sicko" style="font-size: 1.2rem; font-weight: bold; color: white;">SicKo</span>
          </div>
          <div style="margin-bottom: 0px;">
            <span class="pup" style="font-size: 0.5rem; color: white;">PUP-SRC Clinic</span>
          </div>
        </div>
      </div>
    </div>
  </header><!-- End Header -->

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <ul>
      <li><a href="#">Home</a></li>
      <li><a href="#">Profile</a></li>
      <li><a href="#">Settings</a></li>
    </ul>
  </div>

  <!-- Menu Toggle Button -->
  

  <main class="mt-5 content">
    <div class="container-fluid main-content">
      <div class="title">
        <span class="fw-bolder" style="color: red;">Edit</span>
        <span class="fw-bolder" style="color: #058789;">Profile</span><br/>
        <span class="fw-light">Update your information</span>
      </div>

      <form class="row g-3 needs-validation form" novalidate>
        <div class="col-12">
          <div class="row">
            <div class="col">
              <div class="form-floating has-validation">
                <input type="text" name="lastName" class="form-control" id="lastName" placeholder="Last Name" required>
                <div class="invalid-feedback">Please enter your last name.</div>
                <label for="lastName">Last Name</label>
              </div>
            </div>

            <div class="col">
              <div class="form-floating has-validation">
                <input type="text" name="firstName" class="form-control" id="firstName" placeholder="First Name" required>
                <div class="invalid-feedback">Please enter your first name.</div>
                <label for="firstName">First Name</label>
              </div>
            </div>

            <div class="col">
              <div class="form-floating has-validation">
                <input type="text" name="middleName" class="form-control" id="middleName" placeholder="Middle Name" required>
                <div class="invalid-feedback">Please confirm your middle name</div>
                <label for="middleName">Middle Name</label>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="form-floating has-validation position-relative">
            <input type="text" name="email" class="form-control" id="email" placeholder="Email" required>
            <div class="invalid-feedback">Please confirm your email</div>
            <label for="email">Email</label>
          </div>
        </div>

        <div class="col-12">
          <div class="form-floating has-validation position-relative">
            <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
            <div class="invalid-feedback">Please confirm your password</div>
            <label for="password">Password</label>
            <i class="bi bi-eye-slash-fill eye-icon" id="togglePassword"></i>
          </div>
        </div>

        <div class="col-12">
          <div class="form-floating has-validation">
            <input type="password" name="confirmPassword" class="form-control" id="confirmPassword" placeholder="Confirm Password" required>
            <div class="invalid-feedback">Please confirm your Confirm Password</div>
            <label for="confirmPassword">Confirm Password</label>
            <i class="bi bi-eye-slash-fill eye-icon" id="toggleConfirmPassword"></i>
          </div>
        </div>

        <div class="col-12">
          <div class="row">
            <div class="col"> <!-- Adjust column width as needed -->
              <button class="btn cancel" type="submit">Cancel</button>
            </div>

            <div class="col"> <!-- Adjust column width as needed -->
              <button class="btn submit" type="submit">Submit</button>
            </div>
          </div>
        </div>
      </form>

    </div>
  </main> 

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer" style="background-color: #058789;">
    <div class="copyright text-white">
      <b>Sicko |</b>
      &copy;
    </div>
  </footer><!-- End Footer -->

  <script src="./js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
  <script src="./js/jquery-3.5.1.js"></script>
  <script src="./js/jquery.dataTables.min.js"></script>
  <script src="./js/dataTables.bootstrap5.min.js"></script>
  <script>
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
      const type = confirmPassword.getAttribute('type') === 'confirmPassword' ? 'text' : 'confirmPassword';
      confirmPassword.setAttribute('type', type);
      // toggle the eye slash icon
      this.classList.toggle('bi-eye-fill');
      this.classList.toggle('bi-eye-slash-fill');
    });

    // Sidebar Toggle
    const menuToggle = document.querySelector('#menu-toggle');
    const sidebar = document.querySelector('#sidebar');
    const content = document.querySelector('.content');

    menuToggle.addEventListener('click', function () {
      if (sidebar.style.left === "-200px") {
        sidebar.style.left = "0";
        content.style.marginLeft = "200px";
      } else {
        sidebar.style.left = "-200px";
        content.style.marginLeft = "0";
      }
    });
  </script>
</body>
</html>

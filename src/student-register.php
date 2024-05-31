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
  
  <title>Student Register</title>
</head>

<style>
    body {
        font-family: poppins;
        padding-left: 100px;
        padding-right: 100px;
        padding-top: 50px;
        background-color: #F4F4F4;
    }

    .fw-bolder {
        margin-left: 70px;
        margin-bottom: 50px;
        font-size: 30px;
    }

    .fw-light {
      margin-left: 10px;
    }

    .main-content {
        height: 600px;
        /* Adjust height as needed */
        overflow: auto;
    }

    .sicko-header {
        margin-left: 20px;
    }

    .btn-signin {
        margin-left: 2px;
    }

    .btn-signup {
        margin-left: 2px;
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

    .btn-signin {
      position: absolute;
      left: 75%;
      width: 8%;
      background-color: white;
      color: #058789;
      border-color: #058789;
      border-radius: 8px;
    }

    .btn-signup {
      position: absolute;
      left: 85%;
      top: 50%;
      width: 8%;
      background-color: #058789;
      color: white;
      border-color: #058789;
      border-radius: 8px;
    }

    .sicko-header1 {
      margin-left: 50px;
    }

    .footer {
      position: fixed;
      left: 0;
      bottom: 0;
      width: 100%;
      background-color: #058789;
      color: white;
      text-align: center;
      padding: 10px 0;
    }

    .form-column {
      width: 40%;
      margin: 0 auto;
    }

    .blue1 {
      position: fixed;
      top: 70%;
      right: 87%;
    }

    .reg-image {
      margin-left: 120px;
    }

    .signup {
      background-color: #058789;
      color: white;
      border-radius: 8px;
      width: 70%;
      margin-left: 70px;
    }

</style>


<body>
  
  <header id="header" class="header fixed-top d-flex">
    <!-- Your header content -->
    <div>
      <div class="d-flex align-items-center">
        <img src="images/Sicko Logo.png" alt="" width="50" height="50" class="sicko-header1">
        <img src="images/sicko.png" alt="" width="70" height="25" class="sicko-header2">
        <div>
          <div style="margin-bottom: -14px;">
            <button class="btn btn-signin">Sign In</button>
          </div>
          <div style="margin-bottom: 0px;">
            <button class="btn btn-signup">Sign Up</button>
          </div>
        </div>
      </div>
    </div>
  </header><!-- End Header -->

  <main class="mt-5 content">
    <div class="d-flex align-items-center">
        <div class="image-column">
          <img src="images/reg-image.png" alt="" width="400" height="300" class="reg-image">
        </div>
        <div class="form-column">
          <span class="fw-bolder"> Create an Account </span>
          <form class="row g-3 needs-validation form" novalidate>
            <div class="form-floating has-validation">
              <input type="text" name="studentID" class="form-control" id="studentID" placeholder="Student ID" required>
              <div class="invalid-feedback">Please enter your Student ID</div>
              <label for="lastName">Student ID</label>
            </div>

            <div class="form-floating has-validation position-relative">
              <input type="text" name="email" class="form-control" id="email" placeholder="Email" required>
              <div class="invalid-feedback">Please confirm your email</div>
              <label for="email">Email</label>
            </div>

            <div class="col-12">
              <div class="form-floating has-validation position-relative">
                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                <div class="invalid-feedback">Please confirm your password</div>
                <label for="password">Password</label>
              </div>
            </div>
            <div class="col-12">
              <div class="col"> <!-- Adjust column width as needed -->
                <button class="btn signup" type="submit">Sign Up</button>
              </div>
              <div class="col">
                <span style="color: #757575; font-size: 10px; margin-left: 120px"> Already have an account? <a href="student-signin.php">Sign In</a> instead </span>
              </div>
            </div>
          </form>
        </div>
      </div>
  </main> 

  <div>
    <img src="images/blue1.png" alt="" width="200" height="200" class="blue1">
  </div>

  <script src="./js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
  <script src="./js/jquery-3.5.1.js"></script>
  <script src="./js/jquery.dataTables.min.js"></script>
  <script src="./js/dataTables.bootstrap5.min.js"></script>
</body>
</html>

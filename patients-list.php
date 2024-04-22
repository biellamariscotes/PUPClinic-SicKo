<?php
require_once('src/includes/session-nurse.php');
require_once('src/includes/connect.php');
?>

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
  
  <title>Patients List</title>
</head>

<style>
    body {
        font-family: poppins;
        background-color: #F4F4F4;
    }

    .dropdown-toggle {
        margin-left: 600px;
        margin-top: -25px;
        border-color: black;
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

    .list {
      margin-top: 100px;
      padding-left: 150px;
    }

    .header{
      padding: 5px;
    }

    .form-control {
      border-radius: 10px; /* Adjust the radius as needed */
      box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1); /* Adjust shadow as needed */
    }

    .btn-danger {
      border-radius: 12px;
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

    .table-wrapper {
        border-radius: 12px;
        overflow: hidden;
        margin-top: 40px;
        margin-left: 150px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 80%;
    }

    .table-head {
        background-color: #058789;
        color: white;
    }

    .table>:not(caption)>*>* {
      padding: .5rem 5rem;
      background-color: var(--bs-table-bg);
      border-bottom-width: 1px;
      box-shadow: inset 0 0 0 9999px
      var(--bs-table-accent-bg);
    }

    .table-head th {
      padding: 15px; /* Adjust padding as needed */
    }

    .table tbody td {
      padding: 15px; /* Adjust padding as needed */
    }

    .delete {
      margin-left: 90px;
      padding-bottom: 20px;
    }

    .sb {
      margin-left: 430px;
      padding-bottom: 20px;
    }

    .period {
      position: absolute;
      top: 79%;
      left: 420px; /* Adjust left position */
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
  <?php
    include ('src/includes/sidebar.php');
    ?>

  <main class="mt-5">
    <div class="container-fluid main-content">
      <div class="list">
        <span class="fw-bolder" style="color: red;">List of</span>
        <span class="fw-bolder" style="color: #058789;">Patients</span><br/>
      </div>
      <div class="table-wrapper">
        <table class="table table-striped">
          <thead class="table-head">
            <tr>
              <th scope="col">Patient Name</th>
              <th scope="col">Course & Year</th>
              <th scope="col">Diagnosis</th>
              <th scope="col">Time</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Juan Dela Cruz</td>
              <td>BSIT 3-1</td>
              <td>Nausea</td>
              <td>11:50 am</td>
            </tr>
            <tr>
              <td>Joan Reyes</td>
              <td>BSBA 1-1</td>
              <td>Vomitting</td>
              <td>08:40 am</td>
            </tr>
            <tr>
              <td>Justine Gomez</td>
              <td>BSMM 3-4</td>
              <td>Dizzy</td>
              <td>02:15 pm</td>
            </tr>
          </tbody>
        </table>
        <tfoot>
          <div class="row align-items-end">
            <div class="col delete">
              <button class="btn-danger bi bi-trash"> Delete</button>
            </div>
            <div class="col sb">
              <span>Sort by: </span>
            </div>
            <div class="col period">
              <button class="btn btn-table btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><b>Annually</b>
              </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Annually</a></li>
                  <li><a class="dropdown-item" href="#">Monthly</a></li>
                  <li><a class="dropdown-item" href="#">Daily</a></li>
                </ul>
            </div>
          </div>
        </tfoot>
      </div>    
    </div>
  </main> 

  <!-- ======= Footer ======= -->
  <?php
    include ('src/includes/footer.php');
    ?><!-- End Footer -->

  <script src="./js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
  <script src="./js/jquery-3.5.1.js"></script>
  <script src="./js/jquery.dataTables.min.js"></script>
  <script src="./js/dataTables.bootstrap5.min.js"></script>
  <script>

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

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
  <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <title>Frontendfunn - Bootstrap 5 Admin Dashboard Template</title>
</head>

<style>
    body{
      font-family: poppins;
    }
    /* Custom CSS for the full-width image */
    img, svg {
      vertical-align: top; /* Align to the top */
    }

    .container {
      position: relative;
      text-align: center;
      color: white;
    }

    .first-text {
      position: absolute;
      top: 30%;
      left: 35%;
      transform: translate(-50%, -50%);
      color: #088F8F;
      font-size: 15px;
    }

    .second-text {
      position: absolute;
      top: 45%;
      left: 40%;
      transform: translate(-50%, -50%);
      color: #DC143C;
      font-size: 25px;
    }

    .third-text {
      position: absolute;
      top: 60%;
      left: 34%;
      transform: translate(-50%, -50%);
      font-size: 10px;
    }

    .fourth-text {
      position: absolute;
      top: 68%;
      left: 39%;
      transform: translate(-50%, -50%);
      color: black;
      font-size: 10px;
    }

    .type {
      position: absolute;
      bottom: 250px;
      left: 20%;
      transform: translateX(-50%);
      color: #DC143C;
      font-size: 32px;
      padding: 10px 20px;
      z-index: 1000;
    }

    .symptoms-btn{
      background-color:#088F8F;
    }

    .card{
      position: absolute;
      bottom: 180px;
      left: 9%;
      transform: translateX(-50%);
      color: #DC143C;
      font-size: 32px;
      padding: 10px 20px;
      z-index: 1000;
      border-radius: 10px;
      padding: 0;
    }

    .btn-primary {
      background-color: #123F3A; /* Blue color */
      border-radius: 10px;
      width: 200px;
      position: absolute;
      bottom: 130px;
      left: 60%;
    }

    .sicko-header{
      margin-left: 600px;
    }

    .sicko{
      margin-left: 2px;
    }

    .bi-three-dots-vertical{
      margin-left: 600px;
    }

    footer{
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background-color: #123F3A;
      padding: 10px 20px;
      text-align: center;
    }

    /* Make the main content scrollable */
    .main-content {
      height: 700px; /* Adjust height as needed */
      overflow-y: auto;
    }

    .copyright{
      font-size: 25px;
    }

    .symptoms-field{
      position: absolute;
      top: 610px;
      left: 80%;
      transform: translateX(-50%);
      color: #DC143C;
      font-size: 32px;
      padding: 10px 20px;
      z-index: 1000;
    }

    .form-control {
      color: #DC143C; /* Text color */
      background-color: #123F3A; /* Background color */
      border: 1px solid #088F8F; /* Border color */
      border-radius: 5px; /* Border radius */
    }

    .form-control::placeholder {
      color: #fff; /* Placeholder color */
    }
  </style>

<body>
  
  <header id="header" class="header fixed-top d-flex align-items-center justify-content-center" style="background-color: #088F8F;">
    <!-- Your header content -->
    <div>
      <!-- Centered Image and Text -->
      <div class="d-flex align-items-center">
        <img src="src/images/sicko-logo.png" alt="" width="50" height="50" class="sicko-header">
        <div>
          <div style="margin-bottom: -14px;">
            <span class="sicko" style="font-size: 1.2rem; font-weight: bold; color: white;">SicKo</span>
          </div>
          <div style="margin-bottom: 0px;">
            <span class="pup" style="font-size: 0.5rem; color: white;">PUP-SRC Clinic</span>
          </div>
        </div>
        <div>
          <i class="bi bi-three-dots-vertical text-white" style="font-size: 1.5rem; cursor: pointer;"></i>
        </div>
      </div>
    </div>
  </header><!-- End Header -->

  <main class="mt-5">
    <div class="container-fluid main-content">
      <div class="row g-0 no-gutters">
        <div class="col-md-9 p-0">
          <img src="src/images/ai-header.png" alt="" class="w-100 img-fluid">
        </div>
        <div class="col-md-3 p-0 container">
          <img src="src/images/white-bg.jfif" alt="" class="w-100 img-fluid" style="height: 213px;">
          <div class="first-text">AI-Based, <b>Symptoms</b></div>
          <div class="second-text"><b>Diagnostic Tool</b></div>
          <div class="third-text">Detects and generates possible</div>
          <div class="fourth-text">diagnosis based on patient symptoms.</div>
        </div>
        <div class="col-12 p-0">
          <div class="type"><b>Type Symptoms...</b></div>
        </div>
        <div class="col-12 pt-0 symptoms-field">
          <div class="card mt-3">
            <div class="card-body">
              <div>
                <form>
                  <div class="row">
                    <div class="col-4">
                      <input type="text" class="form-control" placeholder="Symptoms 1">
                    </div>
                    <div class="col-4">
                      <input type="text" class="form-control" placeholder="Symptoms 2">
                    </div>
                    <div class="col-4">
                      <input type="text" class="form-control" placeholder="Symptoms 3">
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <button class="btn btn-primary" type="submit">Generate Diagnosis</button>
        </div>
      </div>        
    </div>
  </main> 

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
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
  <script src="./js/script.js"></script>
</body>
</html>


<!---<nav>
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
        </ul>
      </nav>-->
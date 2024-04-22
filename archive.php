<?php
require_once('src/includes/session-nurse.php');
require_once('src/includes/connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Archive</title>
    <link rel="icon" type="image/png" href="src/images/sicko-logo.png"> 
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/styles/dboardStyle.css">
</head>

<style>

      .card {
          margin: 40px;
          padding: 10px;
      }

      .dropdown-toggle {
          margin-left: 600px;
          margin-bottom: 10px;
          background-color: white;
      }

      .btn-acad-year {
          background-color: #058789;
          color: #fff;
      }

      .fw-bolder {
          margin-left: 10px;
          font-size: 30px;
          color: #058789;
      }

      .dtr-text {
          position: absolute;
          top: 38%;
          left: 40%;
          color: red;
          font-size: 25px;
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
          height: 2000px;
          /* Adjust height as needed */
          overflow: auto;
      }

      .sicko-header {
          margin-left: 600px;
      }

      .sicko {
          margin-left: 2px;
      }

      .bi-three-dots-vertical {
          margin-left: 600px;
      }

      .table-wrapper {
          border-radius: 12px;
          overflow: hidden;
          margin-top: 80px;
          margin-left: 150px;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
          /* Adding shadow for better visual */
          width: 80%;
      }

      .table-head {
          background-color: #058789;
          color: white;
      }

      .qr-text {
          position: absolute;
          top: 100%;
          left: 40%;
          color: red;
          font-size: 25px;
      }

      .btn-table {
          border-color: #a0a0a0;
      }

      .accordion {
        margin-left: 200px;
        margin-top: 20px;
        width: 100%;
      }

      .accordion-item {
          margin-bottom: 15px;
      }

      /* Style for card count */
      .card.count {
          position: absolute;
          top: -25px;
          right: 50px;
          padding: 5px 10px;
          background-color: #f8f9fa;
          border: 1px solid #ced4da;
          border-radius: 4px;
          background-color: #D3D3D3;
      }

      .month{
        margin-left: 10px;
      }

      .fw-bold {
        font-size: 25px;
        margin-left: 10px;
      }

      .diagnosis {
        font-size: 10px;
        font-weight: bold;
      }

      .edr {
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

      .table>:not(caption)>*>* {
        padding: .5rem 5rem;
        background-color: var(--bs-table-bg);
        border-bottom-width: 1px;
        box-shadow: inset 0 0 0 9999px
        var(--bs-table-accent-bg);
      }

      .card.leading {
          display: flex;
          align-items: center;
          justify-content: space-between;
          padding: 10px;
          margin-bottom: 10px;
          background-color: #058789;
          border: 1px solid #ced4da;
          border-radius: 4px;
          color: white;
      }

      .card.leading-count {
          display: flex;
          align-items: center;
          justify-content: space-between;
          padding: 10px;
          margin-bottom: 10px;
          background-color: #E13F3D;
          border: 1px solid #ced4da;
          border-radius: 4px;
          color: white;
          width: 120px; /* Adjusted width */
          height: 73px;
      }

      .card.leading span,
      .card.leading-count span {
          margin-right: 10px;
      }

      .leading {
        font-size: 25px;
      }

      .text{
        font-size: 10px;
      }

      .col-6.leading{
        margin-left: 130px;
        margin-top: -25px;
      }

      .col-6.count{
        margin-left: 530px;
        margin-top: -124px;
      }

      .card-body {
        margin-left: 50px;
      }

      .row.label {
        font-size: 13px;
      }

      .accordion-button {
        margin-left: 300px; 
        background-color:#fff; 
        border-color: #fff;
        border-radius: 12px;
        width: 500px;
      }
  </style>

<body>
    <div class="overlay" id="overlay"></div>

<?php
    include ('src/includes/sidebar.php');
    ?>

    <div class="container-fluid content" id="content">
      <div class="row g-0 no-gutters">
        <div class="col-12 pt-4 symptoms-field">
          <div class="card mt-3">
            <div class="card-body">
              <span class="fw-bolder">Medical Records Archive</span>
              <div class="btn-group">
                <button class="btn btn-acad-year btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Academic Year
                </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">2021</a></li>
                    <li><a class="dropdown-item" href="#">2022</a></li>
                    <li><a class="dropdown-item" href="#">2023</a></li>
                  </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div>
        <span class="dtr-text fw-bolder"> Daily Treatment Record</span>
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
              <div class="col edr">
                <a href="#">Edit/Delete Records</a>
              </div>
              <div class="col sb">
                <span>Sort by: </span>
              </div>
              <div class="col period">
                <button class="btn btn-table btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><b>Annually</b>
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
      <div>
        <span class="qr-text fw-bolder">Quarterly Report</span>
      </div>    
    </div>

    <div class="accordion" id="accordionExample">
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
          <button class="accordion-button" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            <div>
              <span style="font-weight: bold; color: #058789;">First Quarter</span><br>
              <span style="font-size: 15px;">JANUARY - MARCH</span>
            </div> 
            <div class="card count">
              <span class="fw-bold num">35</span>
              <span class="diagnosis">Diagnosis</span>
            </div>
          </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
          <div class="accordion-body">
            <div class="row">
              <div class="col-6 leading">
                <div class="card leading">
                  <span class="leading"><b>LEADING DIAGNOSIS</span></b>
                  <span class="text">Most common medical condition for the quarter</span>
                </div>
              </div>
              <div class="col-6 count">
                <div class="card leading-count">
                  <span class="fw-bold">35</span>
                  <span class="diagnosis">Diagnosis</span>
                </div>
              </div>
            </div>
          </div>
          <div class="row label">
            <div class="label" style="width: 25%; padding-right: 20px; margin-left: 10px; margin-top: 30px; padding-bottom:20px ;">
              <div class="card-body">
                <span>Patient Diagnosed</span><br/>
                <span>Leading Diagnosis</span>
              </div>
            </div>
            <div class="label" style="width: 20%; padding-right: 20px; margin-left: 20px;">
              <div class="card-body">
                <span style="font-size:20px"><b>January</span></b><br/>
                <span>18</span><br/>
                <span>Diagnosis 1</span>
              </div>
            </div>
            <div class="label" style="width: 20%;">
              <div class="card-body">
                <span style="font-size:20px"><b>February</span></b><br/>
                <span>18</span><br/>
                <span>Diagnosis 1</span>
              </div>
            </div>
            <div class="label" style="width: 20%;">
              <div class="card-body">
                <span style="font-size:20px"><b>March</span></b><br/>
                <span>18</span><br/>
                <span>Diagnosis 1</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
          <button class="accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="margin-left: 300px;">
            <div>
              <span style="font-weight: bold; color: #058789;">Second Quarter</span><br>
              <span style="font-size: 15px;">APRIL - JUNE</span>
            </div> 
            <div class="card count">
              <span class="fw-bold num">35</span>
              <span class="diagnosis">Diagnosis</span>
            </div>
          </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
          <div class="accordion-body">
            <div class="row">
              <div class="col-6 leading">
                <div class="card leading">
                  <span class="leading"><b>LEADING DIAGNOSIS</span></b>
                  <span class="text">Most common medical condition for the quarter</span>
                </div>
              </div>
              <div class="col-6 count">
                <div class="card leading-count">
                  <span class="fw-bold">35</span>
                  <span class="diagnosis">Diagnosis</span>
                </div>
              </div>
            </div>
          </div>
          <div class="row label">
            <div class="label" style="width: 25%; padding-right: 20px; margin-left: 10px; margin-top: 30px; padding-bottom:20px ;">
              <div class="card-body">
                <span>Patient Diagnosed</span><br/>
                <span>Leading Diagnosis</span>
              </div>
            </div>
            <div class="label" style="width: 20%; padding-right: 20px; margin-left: 20px;">
              <div class="card-body">
                <span style="font-size:20px"><b>April</span></b><br/>
                <span>18</span><br/>
                <span>Diagnosis 1</span>
              </div>
            </div>
            <div class="label" style="width: 20%;">
              <div class="card-body">
                <span style="font-size:20px"><b>May</span></b><br/>
                <span>18</span><br/>
                <span>Diagnosis 1</span>
              </div>
            </div>
            <div class="label" style="width: 20%;">
              <div class="card-body">
                <span style="font-size:20px"><b>June</span></b><br/>
                <span>18</span><br/>
                <span>Diagnosis 1</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingThree">
          <button class="accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="margin-left: 300px;">
            <div>
              <span style="font-weight: bold; color: #058789;">Third Quarter</span><br>
              <span style="font-size: 15px;">JULY - SEPTEMBER</span>
            </div>   
            <div class="card count">
              <span>35</span>
              <span style="font-size: 15px;">Diagnosis</span>
            </div>
          </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
          <div class="accordion-body">
            <div class="row">
              <div class="col-6 leading">
                <div class="card leading">
                  <span class="leading"><b>LEADING DIAGNOSIS</span></b>
                  <span class="text">Most common medical condition for the quarter</span>
                </div>
              </div>
              <div class="col-6 count">
                <div class="card leading-count">
                  <span class="fw-bold">35</span>
                  <span class="diagnosis">Diagnosis</span>
                </div>
              </div>
            </div>
          </div>
          <div class="row label">
            <div class="label" style="width: 25%; padding-right: 20px; margin-left: 10px; margin-top: 30px; padding-bottom:20px ;">
              <div class="card-body">
                <span>Patient Diagnosed</span><br/>
                <span>Leading Diagnosis</span>
              </div>
            </div>
            <div class="label" style="width: 20%; padding-right: 20px; margin-left: 20px;">
              <div class="card-body">
                <span style="font-size:20px"><b>July</span></b><br/>
                <span>18</span><br/>
                <span>Diagnosis 1</span>
              </div>
            </div>
            <div class="label" style="width: 20%;">
              <div class="card-body">
                <span style="font-size:20px"><b>August</span></b><br/>
                <span>18</span><br/>
                <span>Diagnosis 1</span>
              </div>
            </div>
            <div class="label" style="width: 20%;">
              <div class="card-body">
                <span style="font-size:20px"><b>September</span></b><br/>
                <span>18</span><br/>
                <span>Diagnosis 1</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingFour">
          <button class="accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour" style="margin-left: 300px;">
            <div>
              <span style="font-weight: bold; color: #058789;">Fourth Quarter</span><br>
              <span style="font-size: 15px;">OCTOBER - DECEMBER</span>
            </div>
            <div class="card count">
              <span class="fw-bold num">35</span>
              <span class="diagnosis">Diagnosis</span>
            </div>
          </button>
        </h2>
        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
          <div class="accordion-body">
            <div class="row">
              <div class="col-6 leading">
                <div class="card leading">
                  <span class="leading"><b>LEADING DIAGNOSIS</span></b>
                  <span class="text">Most common medical condition for the quarter</span>
                </div>
              </div>
              <div class="col-6 count">
                <div class="card leading-count">
                  <span class="fw-bold">35</span>
                  <span class="diagnosis">Diagnosis</span>
                </div>
              </div>
            </div>
          </div>
          <div class="row label">
            <div class="label" style="width: 25%; padding-right: 20px; margin-left: 10px; margin-top: 30px; padding-bottom:20px ;">
              <div class="card-body">
                <span>Patient Diagnosed</span><br/>
                <span>Leading Diagnosis</span>
              </div>
            </div>
            <div class="label" style="width: 20%; padding-right: 20px; margin-left: 20px;">
              <div class="card-body">
                <span style="font-size:20px"><b>October</span></b><br/>
                <span>18</span><br/>
                <span>Diagnosis 1</span>
              </div>
            </div>
            <div class="label" style="width: 20%;">
              <div class="card-body">
                <span style="font-size:20px"><b>November</span></b><br/>
                <span>18</span><br/>
                <span>Diagnosis 1</span>
              </div>
            </div>
            <div class="label" style="width: 20%;">
              <div class="card-body">
                <span style="font-size:20px"><b>December</span></b><br/>
                <span>18</span><br/>
                <span>Diagnosis 1</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php
    include ('src/includes/footer.php');
    ?>
    <script src="src/scripts/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
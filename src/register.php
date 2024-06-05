<?php
session_start();
if (isset($_SESSION['patient_id'])) {
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" type="image/png" href="images/heart-logo.png">
    <link rel="stylesheet" href="../vendors/bootstrap-5.0.2/dist/css/bootstrap.css">
    <link rel="stylesheet" href="styles/register.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>

    <div class="loader">
        <img src="images/loader.gif">
    </div> 

    <div class="main-content">
        <!-- Navigation Bar -->
        <div class="container pt-4">
            <div class="row nav-bar">
                <div class="col-md-6  d-flex align-items-center">
                    <img src="images/sicko-logo.png" class="me-3">
                    <div class="fw-bold fs-4 d-flex align-items-center text-center" style="align-self: center"><span
                            class="green">Sic</span><span class="red">Ko</span></div>
                </div>
                <div class="col-md-6  d-flex justify-content-end">
                    <a href="login.php"><button class="sign-in">Sign In</button></a>
                </div>
            </div>
        </div>

        <!-- Information -->
        <div class="container" style="padding-right: 10rem">
            <div class="row register">
                <div class="col-md-12 info">
                    <div class="row">
                        <div class="col-md-8 d-flex justify-content-center pb-5">
                            <img src="images/register.png">
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12  d-flex justify-content-center mb-3">
                                    <span class="fs-3 fw-bold">Create an account</span>
                                </div>
                            </div>
                            <form method="post" action="includes/queries/register.php">
                                <!-- Student ID -->
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <small>Student ID <span class="asterisk">*</span></small>
                                        <div class="input-group mb-3 d-flex flex-wrap justify-content-center">
                                            <input type="text" class="form-control" placeholder="2000-00000-SR-0"
                                                name="student_id" id="student_id" maxlength="15">
                                        </div>
                                    </div>
                                </div>

                                <!-- First Name & Last Name -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <small>First Name <span class="asterisk">*</span></small>
                                        <div class="input-group mb-3 d-flex flex-wrap justify-content-center">
                                            <input type="text" class="form-control" placeholder="First Name"
                                                name="first_name" id="first_name">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <small>Last Name <span class="asterisk">*</span></small>
                                        <div class="input-group mb-3 d-flex flex-wrap justify-content-center">
                                            <input type="text" class="form-control" placeholder="Last Name"
                                                name="last_name" id="last_name">
                                        </div>
                                    </div>
                                </div>

                                <!-- Sex & Date -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <small>Sex <span class="asterisk">*</span></small>
                                        <div class="input-group mb-3 d-flex flex-wrap justify-content-center">
                                            <select class="form-control" name="sex" id="sex">
                                                <option selected hidden value=""></option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <small>Birthday <span class="asterisk">*</span></small>
                                        <div class="input-group mb-3 d-flex flex-wrap justify-content-center">
                                            <input type="date" class="form-control" name="date" id="date"
                                                min="yyyy-01-01" max="yyyy-12-31">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <small>Course <span class="asterisk">*</span></small>
                                        <div class="input-group mb-3 d-flex flex-wrap justify-content-center">
                                            <select class="form-control" name="course" id="course">
                                                <option selected hidden value=""></option>
                                                <option value="BSA">BSA</option>
                                                <option value="BSECE">BSECE</option>
                                                <option value="BSIE">BSIE</option>
                                                <option value="BSP">BSP</option>
                                                <option value="BSIT">BSIT</option>
                                                <option value="BSED-ENG">BSED - Eng </option>
                                                <option value="BSED-MATH">BSED - Math</option>
                                                <option value="BSBA-MM">BSBA - MM</option>
                                                <option value="BSBA-HRM">BSBA - HRM</option>
                                                <option value="BSEM">BSEM</option>
                                                <option value="BSMA">BSMA</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <small>Section <span class="asterisk">*</span></small>
                                        <div class="input-group mb-3 d-flex flex-wrap justify-content-center">
                                            <select class="form-control" name="section" id="section">
                                                <option selected hidden value=""></option>
                                                <option value="1-1">1-1</option>
                                                <option value="1-2">1-2</option>
                                                <option value="2-1">2-1</option>
                                                <option value="2-2">2-2</option>
                                                <option value="3-1">3-1</option>
                                                <option value="3-2">3-2</option>
                                                <option value="4-1">4-1</option>
                                                <option value="4-2">4-2</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>



                                <!-- Email Address -->
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <small>Email Address <span class="asterisk">*</span></small>
                                        <div class="input-group mb-3 d-flex flex-wrap justify-content-center">
                                            <input type="text" class="form-control" placeholder="email@gmail.com"
                                                name="email" id="email" maxlength="100">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 ">
                                        <small>Password <span class="asterisk">*</span></small>
                                        <div class="input-group mb-3 d-flex flex-wrap justify-content-center">
                                            <input type="password" class="form-control" placeholder="••••••••••"
                                                name="password" id="password"  maxlength="100">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-center">
                                            <button class="register-btn" name="register-btn" id="register-btn"
                                                disabled>Register</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <img class="vector-green fixed-bottom" src="images/vector-green.png" alt="Green Vector">
        </footer>
    </div>
    <script src="../vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/loader.js"></script>
    <script src="scripts/register-validation.js">

    <script>

    </script>

</body>

</html>
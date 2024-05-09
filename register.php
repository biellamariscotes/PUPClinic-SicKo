<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Sign In</title>
    <link rel="icon" type="image/png" href="src/images/sicko-logo.png">
    <link rel="stylesheet" href="vendors/bootstrap-5.0.2/dist/css/bootstrap.css">
    <link rel="stylesheet" href="src/styles/register.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>

    <!-- Navigation Bar -->
    <div class="container pt-4">
        <div class="row nav-bar">
            <div class="col-md-6  d-flex align-items-center">
                <img src="src/images/sicko-logo.png" class="me-3">
                <div class="fw-bold fs-4 d-flex align-items-center text-center" style="align-self: center"><span
                        class="green">Sic</span><span class="red">Ko</span></div>
            </div>
            <div class="col-md-6  d-flex justify-content-end">
                <a href="login.php"><button class="sign-in">Sign In</button></a>
                <a href="#"><button class="sign-up">Register</button></a>
            </div>
        </div>
    </div>

    <!-- Information -->
    <div class="container" style="padding-right: 10rem">
        <div class="row register">
            <div class="col-md-12 info">
                <div class="row">
                    <div class="col-md-8 d-flex justify-content-center pb-5">
                        <img src="src/images/register.png">
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12  d-flex justify-content-center mb-3">
                                <span class="fs-3 fw-bold">Create an account</span>
                            </div>
                        </div>
                        <form method="post" action="src/includes/queries/register.php">
                            <!-- Student ID -->
                            <div class="row">
                                <div class="col-md-12 ">
                                    <small>Student ID <span class="asterisk">*</span></small>
                                    <div class="input-group mb-3 d-flex flex-wrap justify-content-center">
                                        <input type="text" class="form-control" placeholder="2000-00000-SR-0"
                                            name="student_id" id="student_id">
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
                                        <input type="text" class="form-control" placeholder="Last Name" name="last_name"
                                            id="last_name">
                                    </div>
                                </div>
                            </div>

                            <!-- Sex, Date & Course -->
                            <div class="row">
                                <div class="col-md-3">
                                    <small>Sex<span class="asterisk">*</span></small>
                                    <div class="input-group mb-3 d-flex flex-wrap justify-content-center">
                                        <select class="form-control" name="sex" id="sex">
                                            <option selected hidden value=""></option>
                                            <option value="John">Male</option>
                                            <option value="Jane">Female</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <small>Birthday <span class="asterisk">*</span></small>
                                    <div class="input-group mb-3 d-flex flex-wrap justify-content-center">
                                        <input type="date" class="form-control" name="date"
                                            id="date" min="yyyy-01-01" max="yyyy-12-31">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <small>Course <span class="asterisk">*</span></small>
                                    <div class="input-group mb-3 d-flex flex-wrap justify-content-center">
                                        <select class="form-control" name="course" id="course">
                                            <option selected hidden value=""></option>
                                            <option value="Information Technology">BSIT</option>
                                            <option value="Jane">Jane</option>
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
                                            name="email" id="email">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 ">
                                    <small>Password <span class="asterisk">*</span></small>
                                    <div class="input-group mb-3 d-flex flex-wrap justify-content-center">
                                        <input type="password" class="form-control" placeholder="••••••••••"
                                            name="password" id="password">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-center">
                                        <button class="register-btn" name="register-btn"
                                            id="register-btn">Register</button>
                                    </div>
                                    <div class="small-p d-flex justify-content-center">Already have an account? Sign in
                                        instead.</div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <footer>
        <img class="vector-green fixed-bottom" src="src/images/vector-green.png" alt="Green Vector">
    </footer>
    <script src="vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
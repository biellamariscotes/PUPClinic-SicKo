<div class="row nav-bar px-6">
    <div class="col-md-6 nav-bar-left d-flex align-items-center">
        <a href="home.php" class="d-flex align-items-center">
            <img src="images/sicko-logo.png" class="me-4 my-2">
            <div class="fw-bold fs-4 d-flex flex-column white" style="padding: 0;">
                SicKo
                <!-- <div class="fs-8 fw-light" style="padding: 0;">PUP-SRC CLINIC</div> -->
            </div>
        </a>

    </div>

    <div class="col-md-6 d-flex justify-content-end nav-bar-right">
        <!-- <i class="fa-solid fa-bell white d-flex align-items-center" style="margin-right: 40px"></i> -->

        <div class="ml-5 d-flex align-items-center">
            <p class="initials fs-5 fw-semibold" style="margin: 10px 10px 0px 0px;"><?= $initials; ?></p></a>
        </div>

        <div class="dropdown user-profile d-flex align-items-center">
            <button class="btn white" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <?= $row['first_name'] . ' ' . $row['last_name']; ?>

                <i class="pl-1 fas fa-chevron-down main-color fs-6" style="margin-left: 10px"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                <li class="px-xl-2"><a class="dropdown-item" href="student-profile.php">Profile</a></li>
                <li class="px-xl-2"><a class="dropdown-item" href="user-logout.php">Log Out</a></li>
            </ul>

        </div>
    </div>
</div>
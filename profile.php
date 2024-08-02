<?php
include 'dbconfig.php';
session_start();
$username = $_SESSION['username'];

$sql = "SELECT * FROM users WHERE username='$username';";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);

$name = $info['name'];
$email = $info['email'];


$datetimeString = $info['created_at'];
$date = new DateTime($datetimeString);
$formattedDate = $date->format('F Y');

$profilePhoto = $info['profile_photo'] ?? 'default.png';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIXTREAM - <?php echo $name; ?></title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="library-files/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="additional-files/extra.css">
    <link rel="stylesheet" href="additional-files/me.css">
    <link rel="icon" type="image/x-icon" href="assets/LOGO_tab.svg" />
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">        
        <a class="navbar-brand" href="#">
            <img src="assets/LOGO.svg" width="30" height="30" class="d-inline-block align-top" alt="" id="dash-icon">
            <b>PIXTREAM</b>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="dashboard.php"><b>Home</b><span class="sr-only"></span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        User
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav> <br>

    <div class="container">
        <div class="main-body">                        
            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                    <!-- Profile Card -->

                    <div class="card h-100 position-relative">
                        <div class="card-body d-flex flex-column">
                            <div class="dropdown position-absolute top-0 end-0 p-2">
                                <button class="btn btn-light custom-dropdown-btn" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis custom-ellipsis"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="#" onclick="document.getElementById('profile_photo').click();">Change Profile Photo</a></li>
                                    <li><a class="dropdown-item" href="remove-profile-photo.php">Remove Profile Photo</a></li>
                                </ul>
                            </div>

                            <div class="d-flex flex-column align-items-center text-center">
                                <div class="profile-img-container">
                                    <img src="assets/<?php echo $profilePhoto; ?>" alt="Profile Photo" class="rounded-circle" width="150">
                                    <form action="profile-photo.php" method="post" enctype="multipart/form-data">
                                        <input type="file" name="profile_photo" id="profile_photo" style="display: none;" onchange="this.form.submit()">
                                    </form>
                                </div>
                                <div class="mt-3">
                                    <h4><?php echo $name; ?></h4>
                                    <button class="btn btn-custom btn-sm">Follow</button>
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- Info Card -->
                    <div class="card mt-3 h-50">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-3">
                                <h6 class="mb-0">Full Name</h6>
                                <span class="text-secondary"><?php echo $name; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-3">
                                <h6 class="mb-0">Username</h6>
                                <span class="text-secondary"><?php echo $username; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-3">
                                <h6 class="mb-0">Email</h6>
                                <span class="text-secondary"><?php echo $email; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-3">
                                <h6 class="mb-0">Date Joined</h6>
                                <span class="text-secondary"><?php echo $formattedDate; ?></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-8">
                    <!-- Personal Info Card -->
                    <div class="card mb-3 h-80">
                        <div class="card-body">
                            <div class="row"></div>                            
                        </div>
                    </div>

                    <!-- Project Status Cards -->
                    <div class="row gutters-sm">
                        <div class="col-sm-12 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="d-flex align-items-center mb-3">RecenPosts</h6>
                                    <small>Web Design</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Website Markup</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>One Page</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Mobile Template</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Backend API</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>                                                
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="library-files/js/main.js"></script>
</body>
</html>
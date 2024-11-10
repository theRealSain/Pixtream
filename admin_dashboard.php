<?php
include 'dbconfig.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('location: admin.php'); // Redirect to login if not logged in
    exit();
}

// Fetch all users from the database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

$sql_user_count = "SELECT COUNT(*) AS user_count FROM users";
$result_user_count = $conn->query($sql_user_count);
$user_count = 0; // Initialize in case no users are found

if ($result_user_count && $result_user_count->num_rows > 0) {
    $row = $result_user_count->fetch_assoc();
    $user_count = $row['user_count'];
}

// Fetch total number of complaints
$sql_complaints_count = "SELECT COUNT(*) AS complaints_count FROM complaints";
$result_complaints_count = $conn->query($sql_complaints_count);
$complaints_count = 0;
if ($result_complaints_count && $result_complaints_count->num_rows > 0) {
    $row = $result_complaints_count->fetch_assoc();
    $complaints_count = $row['complaints_count'];
}

// Fetch total number of reports
$sql_reports_count = "SELECT COUNT(*) AS reports_count FROM reports";
$result_reports_count = $conn->query($sql_reports_count);
$reports_count = 0;
if ($result_reports_count && $result_reports_count->num_rows > 0) {
    $row = $result_reports_count->fetch_assoc();
    $reports_count = $row['reports_count'];
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIXTREAM - Administrator</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="library-files/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/me.css">
    <link rel="icon" type="image/x-icon" href="assets/img/LOGO_tab.svg" />

    <style>

    </style>

</head>
<body>

    <!-- Toggle Button -->
    <button class="btn mybtn-outline toggle-btn" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>

    <div class="container-fluid d-flex admin-2divs">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="text-center mb-4">
                <img src="assets/img/LOGO_text.svg" width="200" alt="PIXTREAM Logo" class="sidebar-logo">            
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active-now" href="#">
                        <b>Dashboard</b>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link1" href="admin_users.php">
                        <b>Manage Users</b>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_complaints.php">
                        <b>View Complaints</b>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_reports.php">
                        <b>View Reports</b>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">
                        <b>Log Out</b>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->

        <!-- Main Content -->
        <div class="content" id="mainContent">
            <!-- Dashboard Content -->
            <div class="container admin-dash">            
                <p class="mt-5 fs-5"><b>Welcome to the Pixtream Admin Dashboard. Manage users, view complaints, and oversee reports here.</b></p>

                <!-- Example Cards for Quick Stats -->
                <div class="row mt-5">
                    <!-- Total Users Card -->
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <a class="link1" href="admin_users.php">
                            <div class="card text-center admin-card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Users: <?php echo $user_count; ?></h5>
                                    <p class="card-text mt-4">Pixtream has a total of <strong></strong> users registered. Stay updated on the growth of your platform as new users sign up.</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Complaints Card -->
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <a class="link1" href="admin_complaints.php">
                            <div class="card text-center admin-card">
                                <div class="card-body">
                                    <h5 class="card-title">Complaints: <?php echo $complaints_count; ?></h5>
                                    <p class="card-text mt-4">There are currently complaints pending review. Please address these issues promptly to maintain user satisfaction.</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Reports Card -->
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <a class="link1" href="admin_reports.php">
                            <div class="card text-center admin-card">
                                <div class="card-body">
                                    <h5 class="card-title">Reports: <?php echo $reports_count; ?></h5>
                                    <p class="card-text mt-4">You have new reports from users concerning inappropriate content or behavior. Ensure to investigate and take necessary actions.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Add more content here -->
            </div>
            <!-- End of Dashboard Content -->
        </div>
        <!-- Main Content -->
    </div>

    <!-- JS files -->

    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
        });
    </script>

    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

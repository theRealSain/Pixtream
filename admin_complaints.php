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

$com_sql = "SELECT * FROM complaints;";
$com_result = mysqli_query($conn, $com_sql);
$com_info = mysqli_fetch_all($com_result);

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
</head>
<body>

    <button class="btn mybtn-outline toggle-btn" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>

    <div class="container-fluid d-flex admin-2divs">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="text-center mb-4">
                <img src="assets/img/LOGO_text.svg" width="200" alt="PIXTREAM Logo">
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">
                        <b>Dashboard</b>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_users.php">
                        <b>Manage Users</b>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active-now" href="#">
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
            <div class="container my-5 table-contain">
                    <h2 class="mb-4"><b>Manage Complaints</b></h2>

                    <?php
                    foreach($com_info as $complaint) {
                        // Format the date to "24 October 2020"
                        $date = new DateTime($complaint[3]);
                        $formatted_date = $date->format('d F Y');

                        $u_id = $complaint[1];
                        $c_sql = "SELECT * FROM users WHERE id = $u_id;";
                        $c_result = mysqli_query($conn, $c_sql);
                        $c_info = mysqli_fetch_assoc($c_result);
                        $u_name = $c_info['name'];

                    ?>

                        <div class="card mb-3">
                            <div class="card-header">
                                Complaint by: <?php echo $u_name ?> 
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-danger"><?php echo $complaint[2]; ?></h5>                            
                                <p class="card-text"><small class="text-muted">Filed on: <?php echo $formatted_date; ?> </small></p>
                            </div>
                        </div>
                    
                    <?php
                    }
                    ?>



            </div>        
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
    <script>
        const sidebar = document.getElementById('sidebarMenu');
        const mainContent = document.getElementById('mainContent');
        const tableContainer = document.getElementById('tableContainer');
        
        // Function to toggle sidebar visibility
        sidebar.addEventListener('show.bs.collapse', function () {
            mainContent.classList.remove('full-width');
            tableContainer.style.width = '90%'; // Set table container width when sidebar is visible
        });
        
        sidebar.addEventListener('hide.bs.collapse', function () {
            mainContent.classList.add('full-width');
            tableContainer.style.width = '100%'; // Set table container width when sidebar is hidden
        });
    </script>
</body>
</html>

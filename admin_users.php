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

if (!$result) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIXTREAM - Administrator</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">    
    <link rel="stylesheet" href="assets/css/me.css">
    <link rel="icon" type="image/x-icon" href="assets/img/LOGO_tab.svg" />
    <style>
        .user-img {
            width: 30px;
            height: 30px;
            border-radius: 50%; /* Circular profile picture */
            margin-right: 10px; /* Spacing between image and name */
        }
        .table {
            width: 100%; /* Ensure the table uses full width */
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">        
        <a class="navbar-brand" href="#">
            <img src="assets/img/LOGO.svg" width="30" height="30" class="d-inline-block align-top" alt="" id="dash-icon">
            <b>PIXTREAM Admin</b>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>
    <!-- Navbar -->

    <!-- Sidebar -->
    <div class="sidebar collapse show" id="sidebarMenu">
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
                <a class="nav-link active-now" href="#">
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
    <div class="content table-main" id="mainContent">        
        <!-- Dashboard Content -->
        <div class="container my-5 table-contain">
            <h2 class="mb-4"><b>Manage Users</b></h2>
        
            <!-- Scrollable Table -->
            <div class="table-container" id="tableContainer">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Location</th>
                            <th>Joined Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Determine profile picture path
                            $profile_picture = !empty($row['profile_picture']) && file_exists("profile_picture/{$row['profile_picture']}")
                                ? $row['profile_picture'] 
                                : 'profile_picture/default.png'; // Ensure this path is correct
                            
                            echo "<tr>";                                
                            echo "<td><img src='profile_picture/$profile_picture' alt='User DP' class='user-img'> {$row['name']}</td>";
                            echo "<td>{$row['email']}</td>";
                            echo "<td>{$row['location']}</td>";
                            echo "<td>" . date('F Y', strtotime($row['created_at'])) . "</td>";
                            echo "<td>
                                    <button class='btn mybtn-outline btn-sm'>View</button>
                                    <button class='btn mybtn btn-sm'>Delete</button>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No users found</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <!-- End of Scrollable Table -->
        </div>
        <!-- End of Dashboard Content -->
    </div>
    <!-- Main Content -->




    <!-- JS files -->
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

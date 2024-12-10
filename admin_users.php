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
                    <table class="table table-bordered table-hover admin-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Username</th>
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
                                echo "<td>{$row['username']}</td>";
                                echo "<td>{$row['email']}</td>";
                                echo "<td>{$row['location']}</td>";
                                echo "<td>" . date('F Y', strtotime($row['created_at'])) . "</td>";
                                
                                // Corrected anchor tag with username in the href
                                echo "<td>
                                        <a href='admin_user_view.php?username={$row['username']}' class='btn mybtn-outline btn-sm'>View</a>
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

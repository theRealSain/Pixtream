<?php
include 'dbconfig.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the admin is logged in
if (!isset($_SESSION['id'])) {
    header('location: admin.php'); // Redirect to login if not logged in
    exit();
}

// Initialize success message
$success_message = "";

// Handle report approval
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Get current date and add 1 day
    $suspended_until = date('Y-m-d H:i:s', strtotime('+1 day'));

    // Approve the report and store the suspension time in a session variable
    $approve_sql = "UPDATE reports SET approval = 1 WHERE reported_user = $user_id;";

    if (mysqli_query($conn, $approve_sql)) {
        $_SESSION['suspended_user'] = $user_id;
        $_SESSION['suspended_until'] = $suspended_until; // Set suspension end time
        $success_message = 'Report successfully approved! User has been suspended for 1 day.';
    }
}

// Fetch distinct reported users who have not been approved
$rep_sql = "SELECT reported_user, COUNT(*) AS report_count FROM reports WHERE approval = 0 GROUP BY reported_user;";
$rep_result = mysqli_query($conn, $rep_sql);
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
                <a class="nav-link" href="admin_users.php">
                    <b>Manage Users</b>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_complaints.php">
                    <b>View Complaints</b>
                </a>
            </li>
            <li class="nav-item active-now">
                <a class="nav-link" href="#">
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

            <?php if ($success_message): ?>
                <div class="alert alert-success" style="width: 50%; margin: 0 auto;">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <h2 class="mb-4"><b>User Reports</b></h2>

            <?php
            if (mysqli_num_rows($rep_result) > 0) {
                while ($user = mysqli_fetch_assoc($rep_result)) {
                    $reported_user_id = $user['reported_user'];
                    $report_count = $user['report_count']; // Count of reports

                    // Fetch reported user details
                    $user_sql = "SELECT * FROM users WHERE id = $reported_user_id;";
                    $user_result = mysqli_query($conn, $user_sql);
                    $reported_user_info = mysqli_fetch_assoc($user_result);
                    $reported_user_name = $reported_user_info['name'];
                    $reported_user_email = $reported_user_info['email'];

                    // Get the most recent report date for this user
                    $latest_report_sql = "SELECT created_at FROM reports WHERE reported_user = $reported_user_id ORDER BY created_at DESC LIMIT 1;";
                    $latest_report_result = mysqli_query($conn, $latest_report_sql);
                    $latest_report_info = mysqli_fetch_assoc($latest_report_result);
                    $date = new DateTime($latest_report_info['created_at']);
                    $formatted_date = $date->format('d F Y');

                    // Get all users who reported this user
                    $reporters_sql = "SELECT users.name AS reporter_name FROM reports 
                                      JOIN users ON reports.reported_by = users.id
                                      WHERE reports.reported_user = $reported_user_id;";
                    $reporters_result = mysqli_query($conn, $reporters_sql);
            ?>

                <div class="card mb-3">
                    <div class="card-header text-danger fs-5">
                        Reported User: <?php echo htmlspecialchars($reported_user_name); ?>
                    </div>
                    <div class="card-body">                        
                        <p class="card-text">Email: <?php echo htmlspecialchars($reported_user_email); ?></p>                        
                        <p class="card-text"><small class="text-muted">Most recent report filed on: <?php echo $formatted_date; ?></small></p>

                        <p class="card-text">Reported by <?php echo $report_count; ?> user(s)</p>
                        <ul>
                            <?php
                            while ($reporter = mysqli_fetch_assoc($reporters_result)) {
                                echo "<li>" . htmlspecialchars($reporter['reporter_name']) . "</li>";
                            }
                            ?>
                        </ul>

                        <form method="POST" action="">
                            <input type="hidden" name="user_id" value="<?php echo $reported_user_id; ?>">
                            <button type="submit" class="btn mybtn-outline">Approve report</button>
                        </form>
                    </div>
                </div>

            <?php
                }
            } else {
                echo '<p>No users have been reported yet.</p>';
            }
            ?>

        </div>        
    </div>
    <!-- Main Content -->

    <!-- JS files -->
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebarMenu');
        const mainContent = document.getElementById('mainContent');
        
        // Function to toggle sidebar visibility
        sidebar.addEventListener('show.bs.collapse', function () {
            mainContent.classList.remove('full-width');
        });
        
        sidebar.addEventListener('hide.bs.collapse', function () {
            mainContent.classList.add('full-width');
        });
    </script>
</body>
</html>

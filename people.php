<?php
include 'dbconfig.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('location:authen.php');
}

$username = $_SESSION['username'];

// Fetch logged-in user's information
$sql = "SELECT * FROM users WHERE username='$username';";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);

$name = $info['name'];
$email = $info['email'];

// Fetch all users except the currently logged-in user
$sql = "SELECT * FROM users WHERE username != '$username';";
$result = mysqli_query($conn, $sql);

// Fetch users the current user is following
$followSql = "SELECT followed_id FROM follows WHERE follower_id = (SELECT id FROM users WHERE username='$username');";
$followResult = mysqli_query($conn, $followSql);
$following = [];
while ($followRow = mysqli_fetch_assoc($followResult)) {
    $following[] = $followRow['followed_id']; // Store the followed user IDs
}

// Fetching the current user's ID for easier reference
$currentUserIdResult = mysqli_query($conn, "SELECT id FROM users WHERE username='$username';");
$currentUserIdRow = mysqli_fetch_assoc($currentUserIdResult);
$currentUserId = $currentUserIdRow['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIXTREAM - People</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/me.css">
    <link rel="icon" type="image/x-icon" href="assets/img/LOGO_tab.svg" />
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="assets/img/LOGO.svg" width="30" height="30" class="d-inline-block align-top" alt="" id="dash-icon">
            <b>PIXTREAM</b>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php"><b>Home</b></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="people.php"><b>People</b></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="chat.php"><b>Chat</b></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <b><?php echo htmlspecialchars($name); ?></b>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Navbar -->

    <div class="container mt-4">
        <h2>People</h2>
        <ul class="list-group">
        <?php
        if (mysqli_num_rows($result) > 0) {
            // Inside your while loop where you fetch each user
            while ($row = mysqli_fetch_assoc($result)) {
                $profilePhoto = $row['profile_picture'] ?? 'default.png'; // Default photo if none is set
            
                // Check if the current user is following this user
                $isFollowing = in_array($row['id'], $following); // Use user ID for follow check
                $buttonClass = $isFollowing ? 'mybtn-outline' : 'mybtn'; // Change class based on following status
                $buttonText = $isFollowing ? 'Following' : 'Follow'; // Change text based on following status
                $action = $isFollowing ? 'unfollow' : 'follow'; // Define action based on following status
            
                echo "<li class='list-group-item user-card'>";
                echo "<a href='user_profile.php?username=" . urlencode($row['username']) . "' class='text-decoration-none'>";
                echo "<img src='profile_picture/" . htmlspecialchars($profilePhoto) . "' alt='Profile Photo' class='profile-photo'>";
                echo "<div class='card-body'>";
                echo "<div>";
                echo "<h5 class='mb-1'>" . htmlspecialchars($row['name']) . "</h5>"; // User name link to user_profile.php
                echo "<p class='mb-1'>" . htmlspecialchars($row['username']) . "</p>";
                echo "</div></a>";
                echo "<form action='follow_unfollow.php' method='POST' style='display:inline;'>"; // Added form for follow/unfollow
                echo "<input type='hidden' name='username' value='" . htmlspecialchars($row['username']) . "'>";
                echo "<input type='hidden' name='action' value='$action'>"; // Action to perform
                echo "<button type='submit' class='btn $buttonClass'>$buttonText</button>"; // Button to submit form
                echo "</form>";
                echo "</div>";
                echo "</li>";
            }
        } else {
            echo "<li class='list-group-item'>No users found.</li>";
        }
        ?>
        </ul>
    </div>

    <!-- JS files -->
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="library-files/js/jquery-3.6.0.min.js"></script>
</body>
</html>

<?php
include 'dbconfig.php';
session_start();
$username = $_SESSION['username'];

// Fetch all users except the currently logged-in user
$sql = "SELECT * FROM users WHERE username != '$username';";
$result = mysqli_query($conn, $sql);

// Fetch users the current user is following
$followSql = "SELECT following FROM follows WHERE follower = '$username';";
$followResult = mysqli_query($conn, $followSql);
$following = [];
while ($followRow = mysqli_fetch_assoc($followResult)) {
    $following[] = $followRow['following'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>People - PIXTREAM</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="additional-files/extra.css">
    <link rel="stylesheet" href="additional-files/me.css">
    <link rel="icon" type="image/x-icon" href="assets/LOGO_tab.svg" />
    <script src="library-files/js/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Navbar -->
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
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php"><b>Home</b></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="people.php"><b>People</b></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <b><?php echo htmlspecialchars($username); ?></b>
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
                while ($row = mysqli_fetch_assoc($result)) {
                    $profilePhoto = $row['profile_photo'] ?? 'default.png'; // Default photo if none is set
                    $isFollowing = in_array($row['username'], $following);
                    $btnClass = $isFollowing ? 'btn-following' : 'btn-custom';
                    $btnText = $isFollowing ? 'Following' : 'Follow';
                    echo "<li class='list-group-item user-card'>";
                    echo "<img src='assets/" . htmlspecialchars($profilePhoto) . "' alt='Profile Photo' class='profile-photo'>";
                    echo "<div class='card-body'>";
                    echo "<div>";
                    echo "<h5 class='mb-1'>" . htmlspecialchars($row['name']) . "</h5>";
                    echo "<p class='mb-1'>" . htmlspecialchars($row['username']) . "</p>";
                    echo "</div>";
                    echo "<button class='btn $btnClass follow-btn' data-username='" . htmlspecialchars($row['username']) . "'>$btnText</button>";
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
    <script>
        $(document).ready(function() {
            $('.follow-btn').on('click', function() {
                var button = $(this);
                var username = button.data('username');
                var action = button.text().trim() === 'Follow' ? 'follow' : 'unfollow';

                $.ajax({
                    url: 'follow-unfollow.php',
                    type: 'POST',
                    data: {
                        action: action,
                        username: username
                    },
                    success: function(response) {
                        if (response.trim() === 'success') {
                            if (action === 'follow') {
                                button.removeClass('btn-custom').addClass('btn-following').text('Following');
                            } else {
                                button.removeClass('btn-following').addClass('btn-custom').text('Follow');
                            }
                        } else {
                            console.error("Error in AJAX response: " + response);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX error: " + textStatus + " - " + errorThrown);
                    }
                });
            });
        });
    </script>
</body>
</html>

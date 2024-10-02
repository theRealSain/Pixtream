<?php
include 'dbconfig.php';
session_start();

if(!isset($_SESSION['username']))
{
  header('location:authen.php');
}


$username = $_SESSION['username'];
$log_sql = "SELECT * FROM users WHERE username='$username';";
$log_result = mysqli_query($conn, $log_sql);
$log_info = mysqli_fetch_assoc($log_result);
$log_name = $log_info['name'];

$viewedUsername = $_GET['username']; // Username of the viewed profile
// Fetch user information
$sql = "SELECT * FROM users WHERE username='$viewedUsername';";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);
$user_id = $info['id'];
$name = $info['name'];
$email = $info['email'];
$profilePhoto = $info['profile_photo'] ?? 'default.png';
$bio = $info['bio'];
$location = $info['location'];

// Fetch user interests
$interests = [];
$sql = "SELECT o.option_name 
        FROM user_selections us
        JOIN options o ON us.option_id = o.id
        WHERE us.user_id = (SELECT id FROM users WHERE username = ? LIMIT 1)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $viewedUsername);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $interests[] = $row['option_name'];
}


// Format join date
$datetimeString = $info['created_at'];
$date = new DateTime($datetimeString);
$formattedDate = $date->format('F Y');

// Fetch number of followers
$followersSql = "SELECT COUNT(*) AS follower_count FROM follows WHERE followed_id='$user_id';";
$followersResult = mysqli_query($conn, $followersSql);
$followersCount = mysqli_fetch_assoc($followersResult)['follower_count'];

// Fetch number of following
$followingSql = "SELECT COUNT(*) AS following_count FROM follows WHERE follower_id='$user_id';";
$followingResult = mysqli_query($conn, $followingSql);
$followingCount = mysqli_fetch_assoc($followingResult)['following_count'];


// Fetch followers list with names
$followersListSql = "SELECT follower_id FROM follows WHERE followed_id='$user_id';";
$followersListResult = mysqli_query($conn, $followersListSql);
$followersListInfo = mysqli_fetch_all($followersListResult, MYSQLI_ASSOC);

// Fetch following list with names
$followingListSql = "SELECT followed_id FROM follows WHERE follower_id='$user_id';";
$followingListResult = mysqli_query($conn, $followingListSql);
$followingListInfo = mysqli_fetch_all($followingListResult, MYSQLI_ASSOC);

// Fetch user's photos
$photosSql = "SELECT * FROM posts WHERE username='$viewedUsername' ORDER BY created_at DESC;";
$photosResult = mysqli_query($conn, $photosSql);
$photos = [];
while ($row = mysqli_fetch_assoc($photosResult)) {
    $photos[] = $row;
}

// Posts count
$postSql = "SELECT COUNT(*) FROM posts WHERE username = '$viewedUsername';";
$postResult = mysqli_query($conn, $postSql);
$postInfo = mysqli_fetch_array($postResult);
$postCount = $postInfo[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIXTREAM - <?php echo htmlspecialchars($log_name); ?></title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="library-files/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/me.css">
    <link rel="icon" type="image/x-icon" href="assets/img//LOGO_tab.svg" />
</head>
<body>

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
                <li class="nav-item active">
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
                        <b><?php echo htmlspecialchars($log_name); ?></b>
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
                            <div class="d-flex flex-column align-items-center text-center">
                                <div class="profile-img-container">
                                    <img src="assets/img/<?php echo htmlspecialchars($profilePhoto); ?>" alt="Profile Photo" class="rounded-circle" width="150">
                                </div>
                                <div class="mt-3">
                                    <h4><?php echo htmlspecialchars($name); ?></h4>
                                </div>
                                <div class="d-flex justify-content-around stats">
                                    <div class="stat">
                                        <p><b>Posts</b></p>
                                        <p><?php echo htmlspecialchars($postCount); ?></p>
                                    </div>
                                    <div class="stat" data-bs-toggle="modal" data-bs-target="#followersModal">
                                        <p><b>Followers</b></p>
                                        <p><?php echo htmlspecialchars($followersCount); ?></p>
                                    </div>
                                    <div class="stat" data-bs-toggle="modal" data-bs-target="#followingModal">
                                        <p><b>Following</b></p>
                                        <p><?php echo htmlspecialchars($followingCount); ?></p>
                                    </div>
                                </div>

                                <!-- Bio Section Below Stats -->
                                <div class="mt-3 bio-text text-start">
                                    <p><?php echo nl2br(htmlspecialchars($bio)); ?></p>
                                    <span class="mt-3 profile-location">
                                        <i class="fa-solid fa-location-dot"></i> <?php echo $location; ?>
                                    </span>
                                </div>

                                <div class="mt-5 profile-interest">
                                    <div class="d-flex flex-wrap">
                                        <?php foreach ($interests as $interest): ?>
                                            <span class="badge mybadge2 me-2 mb-2"><?php echo htmlspecialchars($interest); ?></span> <!-- Bootstrap badge -->
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="card mt-3 info-card">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-3">
                                <h6 class="mb-0">Full Name</h6>
                                <span class="text-secondary"><?php echo htmlspecialchars($name); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-3">
                                <h6 class="mb-0">Username</h6>
                                <span class="text-secondary"><?php echo htmlspecialchars($viewedUsername); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-3">
                                <h6 class="mb-0">Email</h6>
                                <span class="text-secondary"><?php echo htmlspecialchars($email); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-3">
                                <h6 class="mb-0">Date Joined</h6>
                                <span class="text-secondary"><?php echo htmlspecialchars($formattedDate); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-8">
                    <!-- Photos Section -->
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Posts - <?php echo htmlspecialchars($name); ?></h3>
                            <div class="photo-grid">
                                <?php foreach ($photos as $photo): ?>
                                    <div class="photo-item" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#photoModal" 
                                        data-photo-src="posts/<?php echo htmlspecialchars($photo['photo_path']); ?>"
                                        data-caption="<?php echo htmlspecialchars($photo['caption']); ?>"
                                        data-created-at="<?php
                                            $photoDate = new DateTime($photo['created_at']);
                                            echo $photoDate->format('F j, Y, g:i A');
                                        ?>">
                                        <img src="posts/<?php echo htmlspecialchars($photo['photo_path']); ?>" alt="Photo">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Followers Modal -->
    <div class="modal fade" id="followersModal" tabindex="-1" aria-labelledby="followersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="followersModalLabel">Followers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <?php 
                        foreach ($followersListInfo as $followers) {
                            $follower_user_id = $followers['follower_id'];
                            $nameSql = "SELECT * FROM users WHERE id = '$follower_user_id';";
                            $nameResult = mysqli_query($conn, $nameSql);
                            $nameInfo = mysqli_fetch_assoc($nameResult);
                            $follower_name = $nameInfo['name'];
                            $follower_username = $nameInfo['username'];
                        ?>
                            <li class="list-group-item">
                            <?php if ($follower_username === $username): ?>
                                <a href="profile.php" class="text-decoration-none">
                                <strong><?php echo htmlspecialchars($follower_name); ?></strong>
                                    <div class="text-muted"><?php echo htmlspecialchars($follower_username); ?></div>
                                </a>
                            <?php else: ?>
                                <a href="user_profile.php?username=<?php echo htmlspecialchars($follower_username); ?>" class="text-decoration-none">
                                    <strong><?php echo htmlspecialchars($follower_name); ?></strong>
                                    <div class="text-muted"><?php echo htmlspecialchars($follower_username); ?></div>
                                </a>
                            <?php endif; ?>                                
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Following Modal -->
    <div class="modal fade" id="followingModal" tabindex="-1" aria-labelledby="followingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="followingModalLabel">Following</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <?php
                        foreach ($followingListInfo as $following) {
                            $following_user_id = $following['followed_id'];
                            $nameSql = "SELECT * FROM users WHERE id = '$following_user_id';";
                            $nameResult = mysqli_query($conn, $nameSql);
                            $nameInfo = mysqli_fetch_assoc($nameResult);
                            $following_name = $nameInfo['name'];
                            $following_username = $nameInfo['username'];
                        ?>
                            <li class="list-group-item">
                            <?php if ($following_username === $username): ?>
                                <a href="profile.php" class="text-decoration-none">
                                    <strong><?php echo htmlspecialchars($following_name); ?></strong>
                                    <div class="text-muted"><?php echo htmlspecialchars($following_username); ?></div>
                                </a>
                            <?php else: ?>
                                <a href="user_profile.php?username=<?php echo htmlspecialchars($following_username); ?>" class="text-decoration-none">
                                    <strong><?php echo htmlspecialchars($following_name); ?></strong>
                                    <div class="text-muted"><?php echo htmlspecialchars($following_username); ?></div>
                                </a>
                            <?php endif; ?>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <img src="assets/<?php echo $profilePhoto; ?>" alt="Profile Photo" class="rounded-circle" width="55" style="border: none; padding: 0px;">&nbsp;
                    <h5 class="modal-title" id="photoModalLabel"><?php echo htmlspecialchars($name); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="photo-card">
                        <p><small><span id="modalCreatedAt"></span></small></p>
                        <img src="" id="modalPhoto" class="img-fluid" alt="Photo">
                        <div class="mt-3">
                            <p><strong><span id="modalCaption"></span></strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    


    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var photoModal = document.getElementById('photoModal');
        photoModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var photoSrc = button.getAttribute('data-photo-src'); // Get photo path
            var caption = button.getAttribute('data-caption'); // Get caption
            var createdAt = button.getAttribute('data-created-at'); // Get creation date

            // Update modal content
            var modalPhoto = document.getElementById('modalPhoto');
            var modalCaption = document.getElementById('modalCaption');
            var modalCreatedAt = document.getElementById('modalCreatedAt');

            modalPhoto.src = photoSrc;
            modalCaption.textContent = caption ? caption : "No caption available"; // Default if no caption
            modalCreatedAt.textContent = createdAt;
        });
    });
    </script>

</body>
</html>

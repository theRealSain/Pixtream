<?php
include 'dbconfig.php';
session_start();
$username = $_SESSION['username'];

// Fetch user information
$sql = "SELECT * FROM users WHERE username='$username';";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);

$name = $info['name'];
$email = $info['email'];
$profilePhoto = $info['profile_photo'] ?? 'default.png';

// Format join date
$datetimeString = $info['created_at'];
$date = new DateTime($datetimeString);
$formattedDate = $date->format('F Y');

// Fetch number of followers
$followersSql = "SELECT COUNT(*) AS follower_count FROM follows WHERE following='$username';";
$followersResult = mysqli_query($conn, $followersSql);
$followersCount = mysqli_fetch_assoc($followersResult)['follower_count'];

// Fetch number of following
$followingSql = "SELECT COUNT(*) AS following_count FROM follows WHERE follower='$username';";
$followingResult = mysqli_query($conn, $followingSql);
$followingCount = mysqli_fetch_assoc($followingResult)['following_count'];

// Fetch followers list
$followersListSql = "SELECT follower FROM follows WHERE following='$username';";
$followersListResult = mysqli_query($conn, $followersListSql);
$followersList = [];
while ($row = mysqli_fetch_assoc($followersListResult)) {
    $followersList[] = $row['follower'];
}

// Fetch following list
$followingListSql = "SELECT following FROM follows WHERE follower='$username';";
$followingListResult = mysqli_query($conn, $followingListSql);
$followingList = [];
while ($row = mysqli_fetch_assoc($followingListResult)) {
    $followingList[] = $row['following'];
}

// Fetch user's photos
$photosSql = "SELECT * FROM photos WHERE username='$username' ORDER BY created_at DESC;";
$photosResult = mysqli_query($conn, $photosSql);
$photos = [];
while ($row = mysqli_fetch_assoc($photosResult)) {
    $photos[] = $row;
}

//Posts count
$postSql = "SELECT COUNT(*) FROM photos WHERE username = '$username';";
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
    <title>PIXTREAM - <?php echo $name; ?></title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="library-files/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="additional-files/extra.css">
    <link rel="stylesheet" href="additional-files/me.css">
    <link rel="icon" type="image/x-icon" href="assets/LOGO_tab.svg" />
    <style>        
    </style>
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
                <li class="nav-item active">
                    <a class="nav-link" href="people.php"><b>People</b><span class="sr-only"></span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="chat.php"><b>Chat</b><span class="sr-only"></span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <b><?php echo $name; ?></b>
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
                                </div>
                                <div class="d-flex justify-content-around stats">
                                    <div class="stat">
                                        <p><b>Posts</b></p>
                                        <p><?php echo $postCount; ?></p>
                                    </div>
                                    <div class="stat" data-bs-toggle="modal" data-bs-target="#followersModal">
                                        <p><b>Followers</b></p>
                                        <p><?php echo $followersCount; ?></p>
                                    </div>
                                    <div class="stat" data-bs-toggle="modal" data-bs-target="#followingModal">
                                        <p><b>Following</b></p>
                                        <p><?php echo $followingCount; ?></p>
                                    </div>
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
                                <h6 class="mb0">Username</h6>
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
                    <!-- Your Posts Section -->
                    <div class="row gutters-sm">
                        <div class="col-sm-12 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="d-flex align-items-center mb-3">Your Posts - <?php echo $name; ?></h3>
                                    <!-- Photo Grid -->
                                    <div class="photo-grid">
                                        <?php foreach ($photos as $photo): ?>
                                            <?php
                                            $photoPath = $photo['photo_path'];
                                            $caption = $photo['caption'];
                                            $createdAt = new DateTime($photo['created_at']);
                                            $formattedDate = $createdAt->format('F j, Y g:i A');
                                            ?>
                                            <div class="photo-item">
                                                <img src="posts/<?php echo htmlspecialchars($photoPath); ?>" alt="Photo" data-bs-toggle="modal" data-bs-target="#photoModal" data-photo-path="posts/<?php echo htmlspecialchars($photoPath); ?>" data-caption="<?php echo htmlspecialchars($caption); ?>" data-created-at="<?php echo htmlspecialchars($formattedDate); ?>" data-username="<?php echo htmlspecialchars($username); ?>">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>                                                
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel"><?php echo $name; ?> - <?php echo $username; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="photo-card">
                        <p><strong><span id="modal-username">username</span></strong></p>
                        <p>Posted on <span id="modal-created-at"></span></p>
                        <img src="posts/default.png" alt="Photo" class="img-fluid">
                        <div class="mt-3">
                            <p><strong><span id="modal-username">username</span></strong> <span id="modal-caption"></span></p>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Followers Modal -->
    <div class="modal fade" id="followersModal" tabindex="-1" aria-labelledby="followersModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="followersModalLabel">Followers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <?php foreach ($followersList as $follower): ?>
                            <li class="list-group-item"><?php echo htmlspecialchars($follower); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Following Modal -->
    <div class="modal fade" id="followingModal" tabindex="-1" aria-labelledby="followingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="followingModalLabel">Following</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <?php foreach ($followingList as $following): ?>
                            <li class="list-group-item"><?php echo htmlspecialchars($following); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="library-files/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var photoModal = document.getElementById('photoModal');
            photoModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var photoPath = button.getAttribute('data-photo-path');
                var caption = button.getAttribute('data-caption');
                var createdAt = button.getAttribute('data-created-at');
                var username = button.getAttribute('data-username');
                
                var modalImage = photoModal.querySelector('.photo-card img');
                var modalCaption = photoModal.querySelector('#modal-caption');
                var modalCreatedAt = photoModal.querySelector('#modal-created-at');
                var modalUsername = photoModal.querySelector('#modal-username');
                
                modalImage.src = photoPath;
                modalCaption.textContent = caption;
                modalCreatedAt.textContent = createdAt;
                modalUsername.textContent = username;
            });
        });
    </script>
</body>
</html>


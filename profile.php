<?php
include 'dbconfig.php';
session_start();

if(!isset($_SESSION['username']))
{
  header('location:authen.php');
}
$username = $_SESSION['username'];

// Fetch user information
$sql = "SELECT * FROM users WHERE username='$username';";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);

$name = $info['name'];
$email = $info['email'];
$profilePhoto = $info['profile_picture'] ?? 'default.png';

// Format join date
$datetimeString = $info['created_at'];
$date = new DateTime($datetimeString);
$formattedDate = $date->format('F Y');

// Fetch number of followers
$followersSql = "SELECT COUNT(*) AS follower_count FROM follows WHERE followed_id='$username';";
$followersResult = mysqli_query($conn, $followersSql);
$followersCount = mysqli_fetch_assoc($followersResult)['follower_count'];

// Fetch number of following
$followingSql = "SELECT COUNT(*) AS following_count FROM follows WHERE follower_id='$username';";
$followingResult = mysqli_query($conn, $followingSql);
$followingCount = mysqli_fetch_assoc($followingResult)['following_count'];

// Fetch followers list
$followersListSql = "SELECT follower_id FROM follows WHERE followed_id='$username';";
$followersListResult = mysqli_query($conn, $followersListSql);
$followersListInfo = mysqli_fetch_all($followersListResult, MYSQLI_ASSOC);

// Fetch following list
$followingListSql = "SELECT followed_id FROM follows WHERE follower_id='$username';";
$followingListResult = mysqli_query($conn, $followingListSql);
$followingListInfo = mysqli_fetch_all($followingListResult, MYSQLI_ASSOC);

// Fetch user's photos
$photosSql = "SELECT * FROM posts WHERE username='$username' ORDER BY created_at DESC;";
$photosResult = mysqli_query($conn, $photosSql);
$photos = [];
while ($row = mysqli_fetch_assoc($photosResult)) {
    $photos[] = $row;
}

//Posts count
$postSql = "SELECT COUNT(*) FROM posts WHERE username = '$username';";
$postResult = mysqli_query($conn, $postSql);
$postInfo = mysqli_fetch_array($postResult);
$postCount = $postInfo[0];

// Fetch user interests
$interests = [];
$sql = "SELECT o.option_name 
        FROM user_selections us
        JOIN options o ON us.option_id = o.id
        WHERE us.user_id = (SELECT id FROM users WHERE username = ? LIMIT 1)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $interests[] = $row['option_name'];
}

$bioSql = "SELECT * FROM users WHERE username = '$username';";
$bioResult = mysqli_query($conn, $bioSql);
$bioInfo = mysqli_fetch_assoc($bioResult);
$bio = $bioInfo['bio'];
$location = $bioInfo['location'];

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
    <link rel="stylesheet" href="assets/css/me.css">
    <link rel="icon" type="image/x-icon" href="assets/img/LOGO_tab.svg" />
    <style>        
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">        
        <a class="navbar-brand" href="dashboard.php">
            <img src="assets/img/LOGO.svg" width="30" height="30" class="d-inline-block align-top" alt="" id="dash-icon">
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
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#interestsModal">User Interests</a></li>
                                </ul>
                            </div>

                            <div class="d-flex flex-column align-items-center text-center">
                                <div class="profile-img-container">
                                    <img src="profile_picture/<?php echo $profilePhoto; ?>" alt="Profile Photo" class="rounded-circle" width="150">
                                    <form action="profile-photo.php" method="post" enctype="multipart/form-data">
                                        <input type="file" name="profile_picture" id="profile_photo" style="display: none;" onchange="this.form.submit()">
                                    </form>
                                </div>
                                <div class="mt-3 mb-4">
                                    <h4><?php echo $name; ?></h4>
                                </div>
                                <div class="d-flex justify-content-around stats mb-3">
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

                    <!-- User Interests Modal -->                    
                    <div class="modal fade" id="interestsModal" tabindex="-1" aria-labelledby="interestsModalLabel" aria-hidden="true">
                        <div class="modal-dialog mt-5">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="interestsModalLabel">Your Interests</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body d-flex justify-content-center flex-wrap">
                                    <?php if (!empty($interests)): ?>
                                        <div class="d-flex flex-wrap">
                                            <?php foreach ($interests as $interest): ?>
                                                <span class="badge mybadge me-2 mb-2"><?php echo htmlspecialchars($interest); ?></span> <!-- Bootstrap badge -->
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div>No interests added.</div>
                                    <?php endif; ?>
                                </div>
                                <div class="modal-footer justify-content-center"> <!-- Center the footer content -->
                                    <a href="user_details.php" class="btn mybtn-outline">Edit Interests</a> <!-- Edit button -->
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Info Card -->
                    <div class="card mt-3 mb-5 info-card">
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
                        <?php 
                        foreach ($followersListInfo as $follower_id) {
                            $follower_user = $follower_id['follower_id'];
                            $nameSql = "SELECT name FROM users WHERE username = '$follower_user';";
                            $nameResult = mysqli_query($conn, $nameSql);
                            $nameInfo = mysqli_fetch_assoc($nameResult);
                            $follower_name = $nameInfo['name'];
                        ?>
                            <li class="list-group-item">
                                <a href="user-profile.php?username=<?php echo htmlspecialchars($follower_user); ?>" class="text-decoration-none">
                                    <strong><?php echo htmlspecialchars($follower_name); ?></strong>
                                    <div class="text-muted"><?php echo htmlspecialchars($follower_user); ?></div>
                                </a>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="followingModalLabel">Following</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <?php 
                        foreach ($followingListInfo as $following) {
                            $following_user = $following['following'];
                            $nameSql = "SELECT name FROM users WHERE username = '$following_user';";
                            $nameResult = mysqli_query($conn, $nameSql);
                            $nameInfo = mysqli_fetch_assoc($nameResult);
                            $following_name = $nameInfo['name'];
                        ?>
                            <li class="list-group-item">
                                <a href="user-profile.php?username=<?php echo htmlspecialchars($following_user); ?>" class="text-decoration-none">
                                    <strong><?php echo htmlspecialchars($following_name); ?></strong>
                                    <div class="text-muted"><?php echo htmlspecialchars($following_user); ?></div>
                                </a>
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
        <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;"> <!-- Fixed width for the modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <img src="assets/<?php echo $profilePhoto; ?>" alt="Profile Photo" class="rounded-circle" width="55" style="border: none; padding: 0px;"> &nbsp;
                    <h5 class="modal-title" id="photoModalLabel"><?php echo htmlspecialchars($name); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="photo-card">                    
                        <p><small><span id="modal-created-at"></span></small></p>
                        <img src="posts/default.png" id="modalPhoto" class="img-fluid" alt="Photo">
                        <div class="row like-share-comment">
                            <div class="col-lg-4">
                                <i class="far fa-heart"></i>
                            </div>
                            <div class="col-lg-4">
                                <i class="far fa-comment"></i>
                            </div>
                            <div class="col-lg-4">
                                <i class="far fa-share"></i>
                            </div>
                        </div>

                        <div class="mt-3">
                            <p><strong><span id="modal-caption"></span></strong></p>
                        </div>
                    </div>                    
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
            var button = event.relatedTarget; // Button that triggered the modal
            var photoPath = button.getAttribute('data-photo-path'); // Corrected to 'data-photo-path'
            var caption = button.getAttribute('data-caption');
            var createdAt = button.getAttribute('data-created-at');

            var modalImage = photoModal.querySelector('.photo-card img');
            var modalCaption = photoModal.querySelector('#modal-caption');
            var modalCreatedAt = photoModal.querySelector('#modal-created-at');

            modalImage.src = photoPath;
            modalCaption.textContent = caption ? caption : "No caption available";
            modalCreatedAt.textContent = createdAt;
        });
    });

    </script>
</body>
</html>


<?php
include 'dbconfig.php';
session_start();

// error_reporting(0);

if(!isset($_SESSION['username']))
{
  header('location:authen.php');
}

$username = $_SESSION['username'];
$log_sql = "SELECT * FROM users WHERE username='$username';";
$log_result = mysqli_query($conn, $log_sql);
$log_info = mysqli_fetch_assoc($log_result);
$log_id = $log_info['id'];
$log_name = $log_info['name'];

$viewedUsername = $_GET['username']; // Username of the viewed profile
$sql = "SELECT * FROM users WHERE username='$viewedUsername';";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);
$user_id = $info['id'];
$user_name = $info['username'];
$name = $info['name'];
$email = $info['email'];
$profilePhoto = $info['profile_picture'] ?? 'default.png';
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
$photosSql = "SELECT * FROM posts WHERE user_id='$viewedUsername' ORDER BY created_at DESC;";
$photosResult = mysqli_query($conn, $photosSql);
$photos = [];
while ($row = mysqli_fetch_assoc($photosResult)) {
    $photos[] = $row;
}

// Posts count
$postSql = "SELECT COUNT(*) FROM posts WHERE user_id = '$user_id';";
$postResult = mysqli_query($conn, $postSql);
$postInfo = mysqli_fetch_array($postResult);
$postCount = $postInfo[0];

$isFollowingQuery = "SELECT * FROM follows WHERE follower_id = '$log_id' AND followed_id = '$user_id'";
$isFollowingResult = mysqli_query($conn, $isFollowingQuery);
$isFollowing = mysqli_num_rows($isFollowingResult) > 0;

// Check if the logged-in user has blocked the viewed user
$isBlockedQuery = "SELECT * FROM user_blocks WHERE blocked_by = '$log_id' AND blocked_user = '$user_id'";
$isBlockedResult = mysqli_query($conn, $isBlockedQuery);
$isBlocked = mysqli_num_rows($isBlockedResult) > 0;


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
                            <div class="dropdown position-absolute top-0 end-0 p-2">
                                <button class="btn btn-light custom-dropdown-btn" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis custom-ellipsis"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#accountInfoModal">About this Account</a></li>
                                    <!-- Only show Report option if user is not blocked -->
                                    <?php if (!$isBlocked): ?>
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#reportModal">Report</a></li>
                                        <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#blockModal">Block User</a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="d-flex flex-column align-items-center text-center">
                                <div class="profile-img-container">
                                    <img src="profile_picture/<?php echo htmlspecialchars($profilePhoto); ?>" alt="Profile Photo" class="rounded-circle" width="150">
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

                                <!-- Follow/Unfollow or Unblock Button Section -->
                                <div class="mt-3 w-100 d-flex">
                                    <!-- Unblock Button if blocked -->
                                    <?php if ($isBlocked): ?>
                                        <form action="unblock_user.php" method="POST" class="w-100">
                                            <input type="hidden" name="unblock_user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                                            <button type="submit" class="btn mybtn w-100">Unblock</button>
                                        </form>
                                    <?php else: ?>
                                        <!-- Follow/Unfollow Button -->
                                        <form action="follow_unfollow.php" method="POST" class="w-50 me-1">
                                            <input type="hidden" name="username" value="<?php echo htmlspecialchars($user_name); ?>">
                                            <?php if ($isFollowing): ?>
                                                <input type="hidden" name="action" value="unfollow">
                                                <button type="submit" class="btn mybtn-outline w-100">Following</button>
                                            <?php else: ?>
                                                <input type="hidden" name="action" value="follow">
                                                <button type="submit" class="btn mybtn w-100">Follow</button>
                                            <?php endif; ?>
                                        </form>

                                        <!-- Message Button -->
                                        <form action="message.php" method="POST" class="w-50 ms-1">
                                            <input type="hidden" name="message_user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                                            <a href="chat_screen.php?user_id=<?= htmlspecialchars($user_id);?>
                                                <button type="submit" class="btn mybtn-outline w-100">Message</button>
                                            </a>
                                        </form>
                                    <?php endif; ?>
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
                                            <span class="badge mybadge2 me-2 mb-2"><?php echo htmlspecialchars($interest); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        // Display session messages
                        if (isset($_SESSION['report_success'])) {
                            echo '<div class="alert alert-success">' . $_SESSION['report_success'] . '</div>';
                            unset($_SESSION['report_success']); // Clear the message after displaying
                        }

                        if (isset($_SESSION['report_fail'])) {
                            echo '<div class="alert alert-danger">' . $_SESSION['report_fail'] . '</div>';
                            unset($_SESSION['report_fail']); // Clear the message after displaying
                        }

                        if (isset($_SESSION['block_success'])) {
                            echo '<div class="alert alert-success">' . $_SESSION['block_success'] . '</div>';
                            unset($_SESSION['block_success']); // Clear the message after displaying
                        }

                        if (isset($_SESSION['block_fail'])) {
                            echo '<div class="alert alert-danger">' . $_SESSION['block_fail'] . '</div>';
                            unset($_SESSION['block_fail']); // Clear the message after displaying
                        }                        
                        ?>

                    </div>

                    <!-- Account Info Modal -->
                    <div class="modal fade" id="accountInfoModal" tabindex="-1" aria-labelledby="accountInfoModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="accountInfoModalLabel">Account Information</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h6 class="mb-0">Full Name</h6>
                                    <span class="text-secondary mb-5"><?php echo htmlspecialchars($name); ?></span>

                                    <h6 class="mb-0 mt-4">Username</h6>
                                    <span class="text-secondary mb-5"><?php echo htmlspecialchars($viewedUsername); ?></span>

                                    <h6 class="mb-0 mt-4">Email</h6>
                                    <span class="text-secondary mb-5"><?php echo htmlspecialchars($email); ?></span>

                                    <h6 class="mb-0 mt-4">Date Joined</h6>
                                    <span class="text-secondary mb-5"><?php echo htmlspecialchars($formattedDate); ?></span>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn mybtn-outline" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>                                        

                    <!-- User Report Modal -->
                    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="userReportModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="userReportModalLabel">Report User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-center">Please note that reporting a user is a serious matter. Make sure to use this feature responsibly. If you still wish to proceed with reporting this user, click the button below:</p>
                                    <form name="userReport" id="userReportForm" method="POST" action="" class="d-flex justify-content-center">
                                        <input type="hidden" name="action" value="report"> <!-- Distinguish action -->
                                        <input type="hidden" name="reported_user" value="<?php echo $user_id; ?>"> <!-- Pass the reported user's ID -->
                                        <button type="submit" class="btn mybtn">Report <?php echo $name;?></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Block User Modal -->
                    <div class="modal fade" id="blockModal" tabindex="-1" aria-labelledby="blockUserModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="blockUserModalLabel">Block User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-center">Warning: Blocking a user will prevent them from interacting with you. Are you sure you want to proceed?</p>
                                    <form name="userBlock" id="userBlockForm" method="POST" action="" class="d-flex justify-content-center">
                                        <input type="hidden" name="action" value="block"> <!-- Distinguish action -->
                                        <input type="hidden" name="blocked_user" value="<?php echo $user_id; ?>"> <!-- Pass the blocked user's ID -->
                                        <button type="submit" class="btn mybtn" style="background-color: #7a0b11;">Block <?php echo $name;?></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php

                    ob_start();

                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Initialize variables for actions
                        $action = isset($_POST['action']) ? $_POST['action'] : '';

                        if ($action === 'report') {
                            $reported_by = $log_id; // ID of the user who is reporting
                            $reported_user = $_POST['reported_user']; // Get the reported user's ID from the form

                            // Insert into the reports table
                            $report_sql = "INSERT INTO reports (reported_by, reported_user, created_at) VALUES ('$reported_by', '$reported_user', NOW())";
                            $report_result = mysqli_query($conn, $report_sql);

                            // Check if the query was successful
                            if ($report_result) {
                                $_SESSION['report_success'] = 'User Reported!';
                            } else {
                                $_SESSION['report_fail'] = 'User Not Reported! Error: ' . mysqli_error($conn); // Capture the error
                            }
                        } 
                        
                        elseif ($action === 'block') {
                            $blocked_by = $log_id; // ID of the user who is blocking
                            $blocked_user = $_POST['blocked_user']; // Get the blocked user's ID from the form

                            // Insert into the user_blocks table
                            $block_sql = "INSERT INTO user_blocks (blocked_by, blocked_user, created_at) VALUES ('$blocked_by', '$blocked_user', NOW())";
                            $block_result = mysqli_query($conn, $block_sql);

                            // Check if the query was successful
                            if ($block_result) {
                                $_SESSION['block_success'] = 'User Blocked!';
                            } else {
                                $_SESSION['block_fail'] = 'User Not Blocked! Error: ' . mysqli_error($conn); // Capture the error
                            }
                        }

                        // Redirect to the same page to prevent form resubmission
                        header('Location: ' . $_SERVER['PHP_SELF']); // Redirects to the same page
                        exit();
                    }
                    ?>

                </div>


                <!-- Right Blank Section -->
                <div class="col-md-8">
                    <!-- Your Posts Section -->
                    <div class="row gutters-sm">
                        <div class="col-sm-12 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="d-flex align-items-center mb-3">Posts - <?php echo $name; ?></h3>
                                    <!-- Media Grid -->                                    
                                    <div class="media-grid">

                                    <?php
                                    // Fetch the user's posts
                                    $postsSql = "SELECT * FROM posts WHERE user_id='$user_id' ORDER BY created_at DESC;";
                                    $postsResult = mysqli_query($conn, $postsSql);

                                    // Check if there are any posts
                                    if (mysqli_num_rows($postsResult) === 0) {
                                        echo "<p class='fs-5 mt-4'><b>No posts to show!</b></p>";
                                    } 
                                    else 
                                    {
                                        while ($post = mysqli_fetch_assoc($postsResult)) 
                                        {
                                            $post_id = $post['id'];
                                            $mediaPath = $post['post_path'];
                                            $caption = $post['caption'];
                                            $createdAt = new DateTime($post['created_at']);
                                            $formattedDate = $createdAt->format('F j, Y g:i A');
                                            $category = $post['category'];

                                            // Get the like count for each post
                                            $likeCountSql = "SELECT COUNT(*) AS like_count FROM likes WHERE post_id='$post_id';";
                                            $likeCountResult = mysqli_query($conn, $likeCountSql);
                                            $likeCountRow = mysqli_fetch_assoc($likeCountResult);
                                            $likeCount = $likeCountRow['like_count'];

                                            // Determine if the media is a video or image
                                            $fileExtension = strtolower(pathinfo($mediaPath, PATHINFO_EXTENSION));
                                            $isVideo = in_array($fileExtension, ['mp4', 'mov', 'avi', 'wmv']);
                                    ?>

                                        <a href="post_info.php?user_id=<?php echo $user_id; ?>&post_id=<?php echo $post_id; ?>">
                                            <div class="media-item">
                                                <?php if ($isVideo): ?>
                                                    <div class="video-thumbnail">
                                                        <video muted>
                                                            <source src="<?php echo htmlspecialchars($mediaPath); ?>" type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                        <div class="video-icon-overlay">
                                                            <i class="fas fa-play-circle"></i>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <img src="<?php echo htmlspecialchars($mediaPath); ?>" alt="Pixtream Post" class="img-fluid" style="cursor:pointer;">
                                                <?php endif; ?>

                                                <!-- Like Count Overlay -->
                                                <div class="like-count-overlay">
                                                    <i class="fa-solid fa-thumbs-up"></i>&nbsp;<?php echo $likeCount; ?>
                                                </div>

                                            </div>
                                        </a>

                                <?php 
                                    }
                                }
                                ?>

                                    </div>
                                </div>               
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>                   

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const commentModal = document.getElementById('commentModal');
            const commentModalImage = document.getElementById('commentModalImage');
            const commentModalVideo = document.getElementById('commentModalVideo');
            const commentModalVideoSource = document.getElementById('commentModalVideoSource');
            const postIdInput = document.getElementById('postId');

            // Populate the media source when the comment modal is shown
            commentModal.addEventListener('show.bs.modal', function (event) {
                const modalMediaContainer = document.getElementById('modalMediaContainer');
                const mediaElement = modalMediaContainer.querySelector('img, video');

                if (mediaElement) {
                    if (mediaElement.tagName === 'IMG') {
                        commentModalImage.src = mediaElement.src;
                        commentModalImage.style.display = 'block'; // Show the image
                        commentModalVideo.style.display = 'none'; // Hide the video
                    } else if (mediaElement.tagName === 'VIDEO') {
                        commentModalVideoSource.src = mediaElement.querySelector('source').src; // Get the video source
                        commentModalVideo.load(); // Load the new video source
                        commentModalVideo.style.display = 'block'; // Show the video
                        commentModalImage.style.display = 'none'; // Hide the image
                    }
                }

                const postId = event.relatedTarget.getAttribute('data-post-id');
                postIdInput.value = postId; // Set the post ID in the hidden input
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = new bootstrap.Modal(document.getElementById('postModal'));
            const modalMediaContainer = document.getElementById('modalMediaContainer');
            const modalPostId = document.getElementById('modal-post-id');
            const cmtModalPostId = document.getElementById('post-id-fetch');
            const modalCaption = document.getElementById('modal-caption');
            const modalCreatedAt = document.getElementById('modal-created-at');
            const modalCategory = document.getElementById('modal-category');

            document.querySelectorAll('.media-item img, .video-thumbnail').forEach(mediaItem => {
                mediaItem.addEventListener('click', function () {
                    const cmtPostId = this.getAttribute('data-post-id');
                    const postId = this.getAttribute('data-post-id');
                    const mediaPath = this.getAttribute('data-media-path');
                    const caption = this.getAttribute('data-caption');
                    const createdAt = this.getAttribute('data-created-at');
                    const category = this.getAttribute('data-category');
                    const isVideo = mediaPath.match(/\.(mp4|mov|avi|wmv)$/i);

                    // Clear previous content
                    modalMediaContainer.innerHTML = '';

                    if (isVideo) {
                        const videoElement = document.createElement('video');
                        videoElement.setAttribute('controls', 'controls');
                        videoElement.setAttribute('class', 'img-fluid');
                        videoElement.innerHTML = `<source src="${mediaPath}" type="video/mp4">Your browser does not support the video tag.`;
                        modalMediaContainer.appendChild(videoElement);
                    } else {
                        const imgElement = document.createElement('img');
                        imgElement.setAttribute('src', mediaPath);
                        imgElement.setAttribute('class', 'img-fluid');
                        imgElement.setAttribute('alt', 'Pixtream Post');
                        modalMediaContainer.appendChild(imgElement);
                    }

                    // Set other modal details
                    cmtModalPostId.value = postId;
                    modalPostId.textContent = postId;
                    modalCaption.textContent = caption;
                    modalCreatedAt.textContent = createdAt;
                    modalCategory.textContent = category;

                    // Show modal
                    modal.show();
                });
            });
        });
    </script>

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
    
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>   

</body>
</html>
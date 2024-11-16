<?php
include 'dbconfig.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('location: admin.php'); // Redirect to login if not logged in
    exit();
}


$user_id = $_GET['user_id'];

// Fetch user information
$sql = "SELECT * FROM users WHERE id='$user_id';";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);

$id = $info['id'];
$name = $info['name'];
$email = $info['email'];
$location = $info['location'];
$bio = $info['bio'];
$profilePhoto = $info['profile_picture'] ?? 'default.png';

// Format join date
$datetimeString = $info['created_at'];
$date = new DateTime($datetimeString);
$formattedDate = $date->format('F Y');

// Fetch number of followers
$followersSql = "SELECT COUNT(*) AS follower_count FROM follows WHERE followed_id='$id';";
$followersResult = mysqli_query($conn, $followersSql);
$followersCount = mysqli_fetch_assoc($followersResult)['follower_count'];

// Fetch number of following
$followingSql = "SELECT COUNT(*) AS following_count FROM follows WHERE follower_id='$id';";
$followingResult = mysqli_query($conn, $followingSql);
$followingCount = mysqli_fetch_assoc($followingResult)['following_count'];

// Fetch followers list
$followersListSql = "SELECT follower_id FROM follows WHERE followed_id='$id';";
$followersListResult = mysqli_query($conn, $followersListSql);
$followersListInfo = mysqli_fetch_all($followersListResult, MYSQLI_ASSOC);

// Fetch following list
$followingListSql = "SELECT followed_id FROM follows WHERE follower_id='$id';";
$followingListResult = mysqli_query($conn, $followingListSql);
$followingListInfo = mysqli_fetch_all($followingListResult, MYSQLI_ASSOC);

//Posts count
$postSql = "SELECT COUNT(*) FROM posts WHERE user_id = '$id';";
$postResult = mysqli_query($conn, $postSql);
$postInfo = mysqli_fetch_array($postResult);
$postCount = $postInfo[0];

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

        <div class="container mt-5">
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
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#accountInfoModal">About Account</a></li>
                                    <li><a class="dropdown-item text-danger" href="">De-activate Account</a></li>
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
                                
                                <!-- Bio Section Below Stats -->
                                <div class="mt-3 bio-text text-start">
                                    <p><?php echo nl2br(htmlspecialchars($bio)); ?></p>
                                    <span class="mt-3 profile-location">
                                        <i class="fa-solid fa-location-dot"></i> <?php echo $location; ?>
                                    </span>
                                </div>                                
                            </div>
                        </div>
                    </div>

                    <!-- Info Modal -->
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
                                    <span class="text-secondary mb-5"><?php echo htmlspecialchars($username); ?></span>

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

                </div>

                <div class="col-md-8">
                    <!-- Posts Section -->
                    <div class="row gutters-sm">
                        <div class="col-sm-12 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="d-flex align-items-center mb-3">Posts - <?php echo $name; ?></h3>
                                    <!-- Photo Grid -->
                                    <div class="media-grid">

                                        <?php
                                        // Fetch user's photos
                                        $postsSql = "SELECT * FROM posts WHERE user_id='$id' ORDER BY created_at DESC;";
                                        $postsResult = mysqli_query($conn, $postsSql);
                                        $posts = [];
                                        while ($row = mysqli_fetch_assoc($postsResult)) {
                                            $posts[] = $row;
                                        }
                                        foreach ($posts as $post)
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

                                        <a href="post_info.php?user_id=<?php echo $id; ?>&post_id=<?php echo $post_id; ?>">
                                            <div class="media-item">
                                                <?php if ($isVideo): ?>
                                                    <div class="video-thumbnail" data-bs-toggle="modal" data-bs-target="#postModal" data-media-path="<?php echo htmlspecialchars($mediaPath); ?>" data-caption="<?php echo htmlspecialchars($caption); ?>" data-created-at="<?php echo htmlspecialchars($formattedDate); ?>" data-category="<?php echo htmlspecialchars($category); ?>" data-username="<?php echo htmlspecialchars($username); ?>">
                                                        <video muted>
                                                            <source src="<?php echo htmlspecialchars($mediaPath); ?>" type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                        <div class="video-icon-overlay">
                                                            <i class="fas fa-play-circle"></i>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <img src="<?php echo htmlspecialchars($mediaPath); ?>" alt="Pixtream Post" data-bs-toggle="modal" data-bs-target="#postModal" data-media-path="<?php echo htmlspecialchars($mediaPath); ?>" data-caption="<?php echo htmlspecialchars($caption); ?>" data-created-at="<?php echo htmlspecialchars($formattedDate); ?>" data-category="<?php echo htmlspecialchars($category); ?>" data-username="<?php echo htmlspecialchars($username); ?>">
                                                <?php endif; ?>

                                                <!-- Like Count Overlay -->
                                                <div class="like-count-overlay">
                                                    <i class="fa-solid fa-thumbs-up"></i>&nbsp;<?php echo $likeCount; ?>
                                                </div>

                                            </div>
                                        </a>

                                        <?php
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

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

$id = $info['id'];
$name = $info['name'];
$email = $info['email'];
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
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#accountInfoModal">About your Account</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="document.getElementById('profile_photo').click();">Change Profile Photo</a></li>
                                    <li><a class="dropdown-item" href="remove-profile-photo.php">Remove Profile Photo</a></li>
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#complaintModal">Submit Complaints</a></li>
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

                                <div class="w-100 mb-3">
                                    <a href="user_details.php">
                                        <button type="button" class="btn mybtn-outline w-100">Edit Profile</button>
                                    </a>
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

                    <!-- Complaints Modal -->
                    <div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="complaintModalLabel">Submit a Complaint</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="complaintForm" method="POST" action="">
                                        <div class="mb-3">
                                            <label for="complaintCategory" class="form-label">Category</label>
                                            <select class="form-select" id="complaintCategory" name="complaint_category" required>
                                                <option value="" disabled selected>Select a category</option>
                                                <option value="Technical Issue">Technical Issue</option>
                                                <option value="User Behavior">User Behavior</option>
                                                <option value="Inappropriate Content">Inappropriate Content</option>
                                                <option value="Privacy Violation">Privacy Violation</option>
                                                <option value="Harassment">Harassment</option>
                                                <option value="Spam or Scams">Spam or Scams</option>
                                                <option value="Account Access Issues">Account Access Issues</option>
                                                <option value="Billing or Payments">Billing or Payments</option>
                                                <option value="Security or Hacking">Security or Hacking</option>
                                                <option value="Data Protection Request">Data Protection Request</option>
                                                <option value="Copyright Violation">Copyright Violation</option>
                                                <option value="Feedback or Suggestion">Feedback or Suggestion</option>
                                                <option value="Misinformation or Fake News">Misinformation or Fake News</option>
                                                <option value="Phishing Attempts">Phishing Attempts</option>
                                                <option value="Fraud or Identity Theft">Fraud or Identity Theft</option>
                                                <option value="Impersonation">Impersonation</option>
                                                <option value="Malware or Viruses">Malware or Viruses</option>
                                                <option value="Inappropriate Advertisements">Inappropriate Advertisements</option>
                                                <option value="Slow Performance">Slow Performance</option>
                                                <option value="App or Website Crashes">App or Website Crashes</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>

                                        <!-- Input for custom complaint text (enabled only when "Other" is selected) -->
                                        <div class="mb-3" id="customComplaintField" style="display: none;">
                                            <label for="customComplaint" class="form-label">Please Describe</label>
                                            <textarea class="form-control" id="customComplaint" name="custom_complaint_text" placeholder="Type your issue here..."></textarea>
                                        </div>

                                        <button type="submit" class="btn mybtn">Submit Complaint</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    include 'dbconfig.php';

                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $complaint_category = $_POST['complaint_category'];
                        
                        // Check if "Other" is selected and a custom complaint is provided
                        if ($complaint_category === 'Other' && !empty($_POST['custom_complaint_text'])) {
                            $complaint_text = $_POST['custom_complaint_text']; // Custom complaint text
                        } 
                        else {
                            $complaint_text = $complaint_category; // Predefined complaint category
                        }

                        // Insert complaint into the database
                        $sql = "INSERT INTO complaints (user_id, complaint_text, created_at) VALUES (?, ?, NOW())";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('is', $id, $complaint_text);

                        if ($stmt->execute()) {
                            // Redirect back to the dashboard or display a success message
                            header("Location: profile.php?complaint=submitted");
                        } else {
                            echo "Error: " . $conn->error;
                        }

                        $stmt->close();
                        $conn->close();
                    }
                    ?>


                </div>

                <div class="col-md-8">
                    <!-- Your Posts Section -->
                    <div class="row gutters-sm">
                        <div class="col-sm-12 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="d-flex align-items-center mb-3">Your Posts - <?php echo $name; ?></h3>
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
                                        if (empty($posts)) 
                                        {
                                            echo "<p class='fs-5 mt-4'><b>No posts to show!</b></p>";
                                        }
                                        else 
                                        {
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
            const modal = new bootstrap.Modal(document.getElementById('postModal'));
            const modalMediaContainer = document.getElementById('modalMediaContainer');
            const modalCaption = document.getElementById('modal-caption');
            const modalCreatedAt = document.getElementById('modal-created-at');
            const modalCategory = document.getElementById('modal-category');

            document.querySelectorAll('.media-item img, .video-thumbnail').forEach(mediaItem => {
                mediaItem.addEventListener('click', function () {
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
        <div class="modal-dialog">
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
                                <a href="user_profile.php?username=<?php echo htmlspecialchars($follower_username); ?>" class="text-decoration-none">
                                    <strong><?php echo htmlspecialchars($follower_name); ?></strong>
                                    <div class="text-muted"><?php echo htmlspecialchars($follower_username); ?></div>
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
                            $following_user_id = $following['followed_id'];
                            $nameSql = "SELECT * FROM users WHERE id = '$following_user_id';";
                            $nameResult = mysqli_query($conn, $nameSql);
                            $nameInfo = mysqli_fetch_assoc($nameResult);
                            $following_name = $nameInfo['name'];
                            $following_username = $nameInfo['username'];
                        ?>
                            <li class="list-group-item">
                                <a href="user_profile.php?username=<?php echo htmlspecialchars($following_username); ?>" class="text-decoration-none">
                                    <strong><?php echo htmlspecialchars($following_name); ?></strong>
                                    <div class="text-muted"><?php echo htmlspecialchars($following_username); ?></div>
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

    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="library-files/js/main.js"></script>

    <script>
    document.getElementById('complaintCategory').addEventListener('change', function () {
        var customField = document.getElementById('customComplaintField');
        if (this.value === 'Other') {
            customField.style.display = 'block'; // Show the custom input field
        } else {
            customField.style.display = 'none'; // Hide the custom input field
        }
    });
    </script>

</body>
</html>


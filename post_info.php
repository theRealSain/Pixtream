<?php
include 'dbconfig.php';
session_start();

error_reporting(0);

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

$user_id = $_GET['user_id'];
$post_id = $_GET['post_id'];

$sql = "SELECT * FROM users WHERE id='$user_id';";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);
$user_id = $info['id'];
$user_name = $info['username'];
$name = $info['name'];
$email = $info['email'];
$profilePhoto = $info['profile_picture'];
$bio = $info['bio'];
$location = $info['location'];

$post_sql = "SELECT * FROM posts WHERE id = '$post_id';";
$post_result = mysqli_query($conn, $post_sql);
$post_info = mysqli_fetch_assoc($post_result);

$post_path = $post_info['post_path'];
$isVideo = preg_match('/\.(mp4|webm|ogg)$/i', $post_path);  // Adjust the pattern for any other video formats

$post_created_at = $post_info['created_at'];
$caption = $post_info['caption'];

// Handling comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentText = $_POST['commentText'];

    if (!empty($commentText)) {
        $cmt_sql = "INSERT INTO comments (user_id, post_id, comment, created_at) VALUES ($log_id, $post_id, '$commentText', NOW());";
        $cmt_result = mysqli_query($conn, $cmt_sql);

        if ($cmt_result) {
            // Set a success message and redirect
            $_SESSION['cmt-success'] = "Comment Added!";
            header("Location: post_info.php?post_id=$post_id&user_id=$user_id"); // Redirect to avoid form resubmission
            exit;
        } else {
            // Set a failure message and redirect
            $_SESSION['cmt-fail'] = "Failed to add comment!";
            header("Location: post_info.php?post_id=$post_id&user_id=$user_id"); // Redirect to avoid form resubmission
            exit;
        }
    }
}


$like_count_sql = "SELECT COUNT(*) FROM likes WHERE post_id = '$post_id';";
$like_count_result = mysqli_query($conn, $like_count_sql);
$like_count_info = mysqli_fetch_array($like_count_result);
$like_count = $like_count_info[0];

// Check if user has liked this post
$user_has_liked = false;
$like_check_query = "SELECT 1 FROM likes WHERE user_id = '$log_id' AND post_id = '$post_id' LIMIT 1";
$like_check_result = mysqli_query($conn, $like_check_query);
if (mysqli_num_rows($like_check_result) > 0) {
    $user_has_liked = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIXTREAM</title>
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
    </nav>

    <!-- Post Container -->
    <div class="post-container">
        <div class="post-header d-flex align-items-center">
            <img src="profile_picture/<?php echo htmlspecialchars($profilePhoto); ?>" alt="Profile Picture" width="50" class="me-2" style="border-radius: 50%;"/>
            <div class="post-head">
                <span><?php echo htmlspecialchars($name); ?></span><br>
                <span class="small"><?php echo date("M d Y", strtotime($post_created_at)); ?></span>
            </div>
            
            <!-- Bookmark Button -->
            <?php
                // Check if the post is already saved for this user
                $query = "SELECT * FROM saved_posts WHERE user_id = ? AND post_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ii", $log_id, $post_id);
                $stmt->execute();
                $isSaved = $stmt->get_result()->num_rows > 0;
            ?>

            <div class="dropdown post-menu">
                <i id="bookmarkIcon-<?php echo $post_id; ?>" 
                class="<?php echo $isSaved ? 'fa-solid fa-bookmark' : 'fa-regular'; ?> fa-bookmark"
                style="font-size: 1.3rem; cursor: pointer;" 
                onclick="toggleSavePost(<?php echo $post_id; ?>)"></i>
            </div>
        </div>

        <script>
            // Function to toggle the save status
            function toggleSavePost(postId) {
                const bookmarkIcon = document.getElementById(`bookmarkIcon-${postId}`);

                // Send AJAX request to save/unsave post
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "post_save.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Update icon style based on server response
                        if (xhr.responseText.trim() === "saved") {
                            bookmarkIcon.classList.remove('fa-regular');
                            bookmarkIcon.classList.add('fa-solid');
                        } else if (xhr.responseText.trim() === "unsaved") {
                            bookmarkIcon.classList.remove('fa-solid');
                            bookmarkIcon.classList.add('fa-regular');
                        }
                    }
                };
                xhr.send("post_id=" + postId);
            }
        </script>

        
        <!-- Post Area -->
        <div class="post">
            <div class="post-info">
                <?php if ($isVideo): ?>
                    <video controls width="300" class="img-fluid">
                        <source src="<?php echo htmlspecialchars($post_path); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php else: ?>
                    <img src="<?php echo htmlspecialchars($post_path); ?>" alt="Pixtream Post" class="img-fluid">
                <?php endif; ?>
            </div>
            <div class="post-details">                
                <p><strong><?php echo htmlspecialchars($caption); ?></strong></p>
                <p><strong><span class="badge mybadge2"></span></strong></p>

                <!-- Like, Comment, Share Section -->
                <div class="row like-share-comment mt-3 g-2">
                    <!-- Like Button -->
                    <div class="col-sm-4 text-center like-button <?php echo ($user_has_liked ? 'post-liked' : 'post-inter'); ?>" 
                        data-liked="<?php echo ($user_has_liked ? 'true' : 'false'); ?>">
                        <b id="like-text">
                            <?php echo ($user_has_liked ? "<i class='fa-solid fa-thumbs-up'></i>&nbsp;Liked" : "<i class='fa-solid fa-thumbs-up'></i>&nbsp;Like"); ?>
                        </b>                        
                    </div>

                    <!-- Comment Button -->
                    <div class="col-sm-4 text-center post-inter" data-bs-toggle="modal" data-bs-target="#commentModal">
                        <i class="fa-solid fa-comment"></i>&nbsp;<b>Comment</b>
                    </div>

                    <!-- Share Button -->
                    <div class="col-sm-4 text-center post-inter" data-bs-toggle="modal" data-bs-target="#shareModal">
                        <i class="fa-solid fa-share"></i>&nbsp;<b>Share</b>
                    </div>
                </div>
            </div>
        </div>
        <span class="confirm-alert">                    

            <?php
            if($_SESSION['cmt-success'])
            {
                echo $_SESSION['cmt-success'];
            }
            unset($_SESSION['cmt-success']);

            if($_SESSION['cmt-fail'])
            {
                echo $_SESSION['cmt-fail'];
            }
            unset($_SESSION['cmt-fail']);
            ?>

        </span>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const likeButton = document.querySelector(".like-button");
        const likeText = document.getElementById("like-text");
        const likeCount = document.getElementById("like-count");
        let isLiked = likeButton.getAttribute("data-liked") === "true";
        const postId = <?php echo json_encode($post_id); ?>;
        const userId = <?php echo json_encode($log_id); ?>;

        function updateLikeButton(likedStatus, count) {
            isLiked = likedStatus;
            likeButton.setAttribute("data-liked", likedStatus);
            likeButton.classList.toggle("post-liked", likedStatus);
            likeText.innerHTML = likedStatus ? "<i class='fa-solid fa-thumbs-up'></i>&nbsp;Liked" : "<i class='fa-solid fa-thumbs-up'></i>&nbsp;Like";
            likeCount.textContent = count;
        }

        likeButton.addEventListener("click", function() {
            const newLikedStatus = !isLiked;

            fetch("post_like.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    post_id: postId,
                    user_id: userId,
                    liked: newLikedStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateLikeButton(newLikedStatus, data.like_count);
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });
    </script>



    <!-- Comment Modal (Secondary Modal) -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="max-height: 90vh;">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel">Add Comment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Comment Form -->
                    <form id="commentForm" method="POST" name="comment_form">
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <textarea class="form-control" name="commentText" id="commentText" placeholder="Comment as <?php echo $log_name; ?>" rows="1" required></textarea>                                
                                <button type="submit" class="btn mybtn"><i class="fa-solid fa-caret-right"></i></button>
                            </div>
                        </div>
                    </form>

                    <?php
                        $comment_sql = "SELECT * FROM comments WHERE post_id = '$post_id'";
                        $comment_result = mysqli_query($conn, $comment_sql);
                        $comment_info = mysqlI_fetch_all($comment_result);                        
                        
                    ?>
                    
                    <!-- Display Comments Here -->
                    <div class="comments-section mt-4">
                    <h5 class="mb-3"><b>Comments</b></h5>
                        <?php 
                        if ($comment_result && mysqli_num_rows($comment_result) > 0) {
                            foreach ($comment_info as $comment)
                            {
                                $cmt_user_id = $comment[1];

                                $name_sql = "SELECT * FROM users WHERE id = '$cmt_user_id';";
                                $name_result = mysqli_query($conn, $name_sql);
                                $name_info = mysqli_fetch_assoc($name_result);

                                $cmt_name = $name_info['name'];
                                $cmt_username = $name_info['username'];
                                $cmt_dp = $name_info['profile_picture'];
                        ?>

                                <div class="comment mb-3">
                                    <div class="d-flex align-items-center">
                                        <img src="profile_picture/<?php echo htmlspecialchars($cmt_dp); ?>" alt="User Profile" width="40" class="me-2" style="border-radius: 50%;">
                                        <div>
                                            <strong><?php echo htmlspecialchars($cmt_username); ?></strong> &nbsp;<span class="mt-2"><?php echo htmlspecialchars($comment[3]); ?></span> <br>
                                            <span class="small"><?php echo date("M d Y", strtotime($comment[4])); ?></span>
                                        </div>
                                    </div>
                                    
                                </div>

                        <?php
                            }
                        }
                        else{
                            echo "<h6 class='text-center'><b>No Comments yet!</b></h6>
                            <p class='text-center'><small>Be first to comment!</small></p>";
                        }
                        ?>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Share Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shareModalLabel">Share Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="shareForm">
                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>"> <!-- Pass the post ID -->
                        <div class="mb-3">
                            <label for="userSelect" class="form-label">Select a user to share with:</label>
                            <select class="form-select" id="userSelect" name="to_user_id" required>
                                <?php
                                // Fetch the list of users the logged-in user follows
                                $follower_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session
                                $query = "SELECT users.id, users.username FROM follows
                                        JOIN users ON follows.followed_id = users.id
                                        WHERE follows.follower_id = ?";
                                $stmt = $pdo->prepare($query);
                                $stmt->execute([$follower_id]);
                                $followers = $stmt->fetchAll();
                                
                                foreach ($followers as $follower) {
                                    echo "<option value='" . $follower['id'] . "'>" . htmlspecialchars($follower['username']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Share</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    if ( window.history.replaceState )
    {
        window.history.replaceState( null, null, window.location.href );
    }
    </script>

    <!-- JS files -->
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
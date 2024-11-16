<?php
include 'dbconfig.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('location:authen.php');
}

$username = $_SESSION['username'];

// Display success message if set
if (isset($_SESSION['upload_message'])) {
    $upload_message = $_SESSION['upload_message'];
    unset($_SESSION['upload_message']); // Clear message after displaying
}

// Fetch user info
$sql = "SELECT * FROM users WHERE username='$username';";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);

$name = $info['name'];
$email = $info['email'];

$name = $info['name'];
$email = $info['email'];
$user_id = $info['id'];

// Fetch posts from followed users
$post_query = "
    SELECT posts.id, posts.user_id, posts.post_path, posts.caption, posts.category, posts.created_at, users.name, users.username, users.profile_picture
    FROM posts
    JOIN follows ON follows.followed_id = posts.user_id
    JOIN users ON users.id = posts.user_id
    WHERE follows.follower_id = $user_id
    ORDER BY posts.created_at DESC
";
$post_result = mysqli_query($conn, $post_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIXTREAM - <?php echo htmlspecialchars($name); ?></title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="library-files/fontawesome/css/all.min.css">
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
                <li class="nav-item active">
                    <a class="nav-link" href="#"><b>Home</b></a>
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
        <!-- Show success message if available -->
        <?php if (isset($upload_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($upload_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="text-right">
            <button type="button" class="btn mybtn" data-bs-toggle="modal" data-bs-target="#newPost">
                New Post
            </button>
        </div>

        <!-- News Feed Section -->
        <div id="newsFeed" class="mt-5">
            <?php while ($post = mysqli_fetch_assoc($post_result)): ?>

                <?php
                    $post_user_id = $post['user_id'];
                    $feed_post_id = $post['id'];
                    $post_modal_id = "commentModal-" . $feed_post_id; // Unique modal ID
                    $post_path = $post['post_path'];
                    $isVideo = preg_match('/\.(mp4|webm|ogg)$/i', $post_path);  // Check if post is a video
                ?>
                
                <div class="post-header d-flex align-items-center" style="border-radius: 8px 8px 0px 0px;">
                    <a href="user_profile.php?username=<?php echo $post['username']; ?>">
                        <img src="profile_picture/<?php echo htmlspecialchars($post['profile_picture']); ?>" alt="Profile Picture" width="50" class="me-2" style="border-radius: 50%;"/>
                    </a>
                    <div class="post-head">
                        <a href="user_profile.php?username=<?php echo $post['username']; ?>">
                            <span><?php echo htmlspecialchars($post['name']); ?></span><br>
                        </a>
                        <span class="small"><?php echo date("M d Y", strtotime($post['created_at'])); ?></span>
                    </div>
                </div>

                <!-- Post Area -->
                <div class="post feed-post">
                    <div class="post-info">
                        <?php if ($isVideo): ?>
                            <video controls width="300" class="img-fluid">
                                <source src="<?php echo htmlspecialchars($post_path); ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        <?php else: ?>
                            <img src="<?php echo htmlspecialchars($post['post_path']); ?>" alt="Pixtream Post" class="img-fluid">
                        <?php endif; ?>
                    </div>

                    <div class="post-details">
                        <p><strong><?php echo htmlspecialchars($post['caption']); ?></strong></p>
                        <p><strong><span class="badge mybadge2"><?php echo htmlspecialchars($post['category']); ?></span></strong></p>

                        <?php
                        // Check if the user has liked this post
                        $user_has_liked = false;
                        $like_check_query = "SELECT 1 FROM likes WHERE user_id = '$user_id' AND post_id = '$feed_post_id' LIMIT 1";
                        $like_check_result = mysqli_query($conn, $like_check_query);
                        if (mysqli_num_rows($like_check_result) > 0) {
                            $user_has_liked = true;
                        }

                        // Fetch the like count
                        $like_count_query = "SELECT COUNT(*) AS like_count FROM likes WHERE post_id = '$feed_post_id'";
                        $like_count_result = mysqli_query($conn, $like_count_query);
                        $like_count = mysqli_fetch_assoc($like_count_result)['like_count'];
                        ?>

                        <!-- Like, Comment, and Share Section -->
                        <div class="row like-share-comment align-items-center mt-3 text-center">
                            <!-- Like Section -->
                            <div class="col like-button <?php echo ($user_has_liked ? 'post-liked' : 'post-inter'); ?>"
                                data-liked="<?php echo ($user_has_liked ? 'true' : 'false'); ?>"
                                data-post-id="<?php echo $feed_post_id; ?>">
                                <b id="like-text-<?php echo $feed_post_id; ?>">
                                    <?php echo ($user_has_liked ? "<i class='fa-solid fa-thumbs-up'></i>&nbsp;Liked" : "<i class='fa-solid fa-thumbs-up'></i>&nbsp;Like"); ?>
                                </b>
                            </div>

                            <!-- Comment Section (Entire div is now clickable) -->
                            <div class="col post-inter">
                                <a href="post_info.php?user_id=<?php echo $post_user_id; ?>&post_id=<?php echo $feed_post_id; ?>" class="text-decoration-none d-flex justify-content-center align-items-center h-100">
                                    <i class="fa-solid fa-comment"></i>&nbsp;<b>Comment</b>
                                </a>
                            </div>

                            <!-- Share Section (Entire div is now clickable) -->
                            <div class="col post-inter">
                                <a href="post_info.php?user_id=<?php echo $post_user_id; ?>&post_id=<?php echo $feed_post_id; ?>" class="text-decoration-none d-flex justify-content-center align-items-center h-100">
                                    <i class="fa-solid fa-share"></i>&nbsp;<b>Share</b>
                                </a>
                            </div>
                        </div>

                    </div>

                </div>

            <?php endwhile; ?>
            
            <!-- 'You're all caught up' message -->
            <div class="text-center feed-end mt-4 mb-5">
                <i class="fa-regular fa-circle-check"></i>
                <p class="fs-5"><b>You're all caught up!</b></p>
            </div>

        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".like-button").forEach(likeButton => {
            const postId = likeButton.getAttribute("data-post-id");
            const likeText = document.getElementById(`like-text-${postId}`);
            const likeCount = document.getElementById(`like-count-${postId}`);

            function updateLikeButton(isLiked, count) {
                likeButton.setAttribute("data-liked", isLiked);
                likeButton.classList.toggle("post-liked", isLiked);
                likeText.innerHTML = isLiked
                    ? "<i class='fa-solid fa-thumbs-up'></i>&nbsp;Liked"
                    : "<i class='fa-solid fa-thumbs-up'></i>&nbsp;Like";
                likeCount.textContent = count;
            }

            likeButton.addEventListener("click", function () {
                const currentLiked = likeButton.getAttribute("data-liked") === "true";
                const newLikedStatus = !currentLiked;

                fetch("post_like.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        post_id: postId,
                        user_id: <?php echo json_encode($user_id); ?>,
                        liked: newLikedStatus
                    }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateLikeButton(newLikedStatus, data.like_count);
                        } else {
                            console.error("Failed to update like:", data.message);
                        }
                    })
                    .catch(error => console.error("Fetch error:", error));
            });
        });
    });
</script>




    <!-- New Post Modal -->
    <div class="modal fade" id="newPost" tabindex="-1" aria-labelledby="newPostLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newPostLabel">New Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="post_upload.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="mediaInput" class="custom-file-upload">
                                <i class="fa-solid fa-upload"></i> Choose file
                            </label>
                            <input type="file" class="form-control-file" id="mediaInput" name="media" accept="image/*,video/*" style="display: none;" required>
                        </div>
                        <div class="mt-3">
                            <div id="preview" style="display: none;"></div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="caption">Caption</label>
                            <textarea class="form-control" id="caption" name="caption" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="" hidden selected>Select a category</option>
                                <option value="photos">Photos</option>
                                <option value="videos">Videos</option>
                                <option value="music">Music</option>
                                <option value="articles">Articles</option>
                                <option value="memes">Memes</option>
                                <option value="art-design">Art & Design</option>
                                <option value="photography">Photography</option>
                                <option value="travel-adventure">Travel & Adventure</option>
                                <option value="food-cooking">Food & Cooking</option>
                                <option value="others">Others</option>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn mybtn">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('mediaInput').addEventListener('change', function(event) {
            var file = event.target.files[0];
            var reader = new FileReader();
            var preview = document.getElementById('preview');

            if (file) {
                var fileType = file.type;
                preview.style.display = 'none';
                preview.innerHTML = '';

                if (fileType.startsWith('image/')) {
                    reader.onload = function(e) {
                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '100%';
                        img.style.border = '1px solid #ddd';
                        img.style.borderRadius = '4px';
                        preview.appendChild(img);
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else if (fileType.startsWith('video/')) {
                    var video = document.createElement('video');
                    video.src = URL.createObjectURL(file);
                    video.controls = true;
                    video.style.maxWidth = '100%';
                    video.style.border = '1px solid #ddd';
                    video.style.borderRadius = '4px';
                    video.addEventListener('loadeddata', function() {
                        URL.revokeObjectURL(video.src);
                    });
                    preview.appendChild(video);
                    preview.style.display = 'block';
                } else {
                    alert('Please upload an image or video file.');
                    event.target.value = '';
                }
            } else {
                preview.style.display = 'none';
            }
        });
    </script>

    <!-- JS files -->
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

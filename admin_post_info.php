<?php
include 'dbconfig.php';
session_start();

if (!isset($_SESSION['id'])) {
    header('location: admin.php'); // Redirect to login if not logged in
    exit();
}

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
$category = $post_info['category'];

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
                    <a class="nav-link active-now" href="admin_users.php">
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

    <!-- Post Container -->
    <div class="post-container">
        <div class="post-header d-flex align-items-center">
            <a href="admin_user_view.php?username=<?php echo $user_name;?>">
                <img src="profile_picture/<?php echo htmlspecialchars($profilePhoto); ?>" alt="Profile Picture" width="50" class="me-2 dp"/>
            </a>
            <div class="post-head">
                <a href="admin_user_view.php?username=<?php echo $user_name;?>">
                    <span><?php echo htmlspecialchars($name); ?></span><br>
                </a>
                <span class="small" style="margin-left: -30px; font-weight: lighter;"><?php echo date("M d Y", strtotime($post_created_at)); ?></span>
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
        </div>        

        
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
                <p><strong><span class="badge mybadge2"><?php echo htmlspecialchars($category); ?></span></strong></p>

                <!-- Like, Comment, Share Section -->
                <div class="row like-share-comment mt-3 g-2">
                    <!-- Comment Button -->
                    <div class="col-sm-4 text-center post-inter" data-bs-toggle="modal" data-bs-target="#commentModal" style="width: fit-content;">
                        <i class="fa-solid fa-comment"></i>&nbsp;<b>Comments on this Post</b>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])) {
            $commentId = intval($_POST['comment_id']);
        
            // Deleting the comment
            $deleteCommentQuery = "DELETE FROM comments WHERE id = '$commentId';";
            $cmt_result = mysqli_query($conn, $deleteCommentQuery);
        
            if ($cmt_result) {
                echo "<span class='confirm-alert'>Comment deleted successfully.</span>";
            }
        }
        ?>

        <!-- Comment Modal (Secondary Modal) -->
        <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="max-height: 90vh;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="commentModalLabel">All Comments</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Comment Form -->
                        <form id="commentForm" method="POST" name="comment_form">
                            <div class="form-group mb-3">
                            </div>
                        </form>

                        <?php
                            $comment_sql = "SELECT * FROM comments WHERE post_id = '$post_id'";
                            $comment_result = mysqli_query($conn, $comment_sql);
                            $comment_info = mysqlI_fetch_all($comment_result);
                        ?>
                        
                        <!-- Display Comments Here -->
                        <div class="comments-section mt-4" style="max-height: 600px; overflow-y: auto;">
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
                                    <div class="flex-grow-1">
                                        <strong><?php echo htmlspecialchars($cmt_username); ?></strong>
                                        &nbsp;<span class="mt-2"><?php echo htmlspecialchars($comment[3]); ?></span> <br>
                                        <span class="small"><?php echo date("M d Y", strtotime($comment[4])); ?></span>
                                    </div>
                                    <!-- Trash Icon -->
                                    <form method="POST" action="" class="ms-2">
                                        <input type="hidden" name="comment_id" value="<?php echo htmlspecialchars($comment[0]); ?>">
                                        <button type="submit" name="delete_comment" class="btn btn-link" style="color: black;" title="Delete Comment">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <?php
                                }
                            }
                            else{
                                echo "<h6 class='text-center'><b>No Comments yet!</b></h6>";
                            }
                            ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const deleteButtons = document.querySelectorAll(".delete-comment-btn");

            deleteButtons.forEach(button => {
                button.addEventListener("click", function () {
                    const form = this.closest(".delete-comment-form");
                    const commentId = form.querySelector('input[name="comment_id"]').value;

                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "", true); // Send request to the same file
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);

                            const confirmAlert = document.querySelector(".confirm-alert");
                            if (response.success) {
                                // Display success message
                                confirmAlert.innerHTML = `<div class='mt-3 text-success'>${response.message}</div>`;

                                // Remove the deleted comment from the DOM
                                form.closest(".comment").remove();
                            } else {
                                // Display error message
                                confirmAlert.innerHTML = `<div class='mt-3 text-danger'>${response.message}</div>`;
                            }
                        }
                    };

                    // Send comment ID
                    xhr.send(`delete_comment=true&comment_id=${encodeURIComponent(commentId)}`);
                });
            });
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
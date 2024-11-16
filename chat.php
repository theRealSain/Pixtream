<?php
include 'dbconfig.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('location:authen.php');
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);
$id = $info['id'];
$name = $info['name'];
$profilePhoto = $info['profile_picture'] ?? 'default.png';

// Function to fetch recent users
function fetchRecentUsers($conn, $username) {
    $recentUsers = [];
    $recentUsersSql = "SELECT DISTINCT receiver_id FROM messages WHERE sender_id = (SELECT id FROM users WHERE username = '$username') ORDER BY created_at DESC";
    $recentUsersResult = mysqli_query($conn, $recentUsersSql);
    
    while ($row = mysqli_fetch_assoc($recentUsersResult)) {
        $receiverId = $row['receiver_id'];
        $userSql = "SELECT * FROM users WHERE id = $receiverId";
        $userResult = mysqli_query($conn, $userSql);
        if ($userRow = mysqli_fetch_assoc($userResult)) {
            $recentUsers[] = $userRow; // Store the recent user info
        }
    }
    return $recentUsers;
}

$recentUsers = fetchRecentUsers($conn, $username);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIXTREAM - Chat</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="library-files/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/me.css">
    <link rel="icon" type="image/x-icon" href="assets/img/LOGO_tab.svg" />
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <a class="navbar-brand" href="dashboard.php">
            <img src="assets/img/LOGO.svg" width="30" height="30" class="d-inline-block align-top" alt="" id="dash-icon">
            <b>PIXTREAM</b>
        </a>
        <div class="collapse navbar-collapse">
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
                        <b><?php echo $name; ?></b>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h3 class="mb-4">Pixtream Chat</h3>

        <div class="input-group mb-3">
            <input type="text" id="searchUser" class="form-control" placeholder="Search users..." aria-label="Search users">
            <button class="btn mybtn-outline" type="button" id="clearSearch">Clear</button>
        </div>

        <div class="position-relative">
            <div id="searchResults" class="dropdown-menu chat-dropdown">
                <a href="chat_screen.php?user_id=123&username=johndoe">
                </a>
            </div>
        </div>

        <?php
        $userSQL = "SELECT id FROM users WHERE username = '$username'";
        $userResult = mysqli_query($conn, $userSQL);
        $userData = mysqli_fetch_assoc($userResult);
        $user_id = $userData['id'];

        // Query to fetch distinct users who have messaged 'abijith' or whom 'abijith' has messaged
        $messageSQL = "
            SELECT u.id AS user_id, u.name, u.username, u.profile_picture, m.message, m.created_at
            FROM users u
            JOIN messages m ON (m.sender_id = u.id OR m.receiver_id = u.id)
            WHERE (m.sender_id = '$user_id' OR m.receiver_id = '$user_id') 
            AND u.username != '$username'  -- Exclude the logged-in user from the results
            ORDER BY m.created_at DESC";  // Most recent messages first

        $messageResult = mysqli_query($conn, $messageSQL);

        // Array to store the latest chat for each user
        $recentChats = [];

        while ($chat = mysqli_fetch_assoc($messageResult)) {
            $partner_id = $chat['user_id'];  // ID of the user in the chat

            // Check if we have already stored the chat for this user
            if (!isset($recentChats[$partner_id]) || strtotime($recentChats[$partner_id]['created_at']) < strtotime($chat['created_at'])) {
                // Store or update the chat with the most recent message
                $recentChats[$partner_id] = [
                    'user_id' => $chat['user_id'],
                    'name' => $chat['name'],
                    'username' => $chat['username'],
                    'profile_picture' => !empty($chat['profile_picture']) ? $chat['profile_picture'] : 'profile_picture/default.png',
                    'message' => $chat['message'],
                    'created_at' => $chat['created_at']
                ];
            }
        }

        // Now $recentChats contains the most recent chat for each user
        $recentUsers = array_values($recentChats);
        ?>


        <!-- Recent Chats Section -->
        <div class="recent-chats">
            <h5 class="mt-4 mb-5"><b>Recent Chats</b></h5>
            <button class="btn mybtn btn-sm shared-posts-btn" data-bs-toggle="modal" data-bs-target="#sharedPostsModal">Shared Posts</button>
            <div id="recentChatsContainer">

                <?php if (empty($recentUsers)): ?>
                    <p class="text-center fs-5 mt-5"><b>No recent chats</b></p>
                <?php else: ?>
                    <?php foreach ($recentUsers as $user): 
                        $userDP = !empty($user['profile_picture']) ? $user['profile_picture'] : 'default.png'; // Use default if empty
                        $user_user_id = $user['user_id']; // User ID from database                    
                        ?>

                        <div class="user-item p-3">
                            <a href="chat_screen.php?user_id=<?= htmlspecialchars($user_user_id);?>" class="list-group-item list-group-item-action">
                                <div class="profile-img-container">
                                    <img src="profile_picture/<?= htmlspecialchars($userDP); ?>" alt="Profile Photo" width="50">
                                </div>
                                <strong class="fs-5"><?= htmlspecialchars($user['name']); ?></strong>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php
            
        ?>

        <!-- Modal for Shared Posts -->
        <div class="modal fade" id="sharedPostsModal" tabindex="-1" aria-labelledby="sharedPostsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="width: 100%;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sharedPostsModalLabel">Shared Posts</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="max-height: 800px; overflow-y: auto;">
                        <?php
                        // Fetch shared posts for the logged-in user
                        $shared_sql = "SELECT * FROM shares WHERE to_user_id = '$id';";
                        $shared_result = mysqli_query($conn, $shared_sql);
                        $shared_info = mysqli_fetch_all($shared_result);

                        if (empty($shared_info)) {
                            echo "<p class='text-center'><b>No shared posts!</b></p>";
                        }
                        else
                        {
                            foreach($shared_info as $shared) {
                                $fr_u_id = $shared[1];
                                $to_u_id = $shared[2];
                                $p_id = $shared[3];
                                $shared_on = $shared[4];

                                // Fetch the user who shared the post
                                $u_sql = "SELECT * FROM users WHERE id = '$fr_u_id';";
                                $u_result = mysqli_query($conn, $u_sql);
                                $u_info = mysqli_fetch_all($u_result);

                                foreach($u_info as $fr_users) {                                    
                                    // Fetch the shared post details
                                    $p_sql = "SELECT * FROM posts WHERE id = '$p_id';";
                                    $p_result = mysqli_query($conn, $p_sql);
                                    $p_info = mysqli_fetch_all($p_result);

                                    foreach($p_info as $posts) {
                                        $pu_id = $posts[1];
                                        $post_path = $posts[2];                                    

                                        // Fetch the original user who posted
                                        $pu_sql = "SELECT * FROM users WHERE id = '$pu_id';";
                                        $pu_result = mysqli_query($conn, $pu_sql);
                                        $pu_info = mysqli_fetch_all($pu_result);

                                        foreach($pu_info as $post_user) {
                                            ?>
                                            
                                            
                                            <p style="margin-left: 10px;">
                                                <a href="user_profile.php?username=<?php echo $fr_users[2]; ?>" class='text-decoration-none'>
                                                    <b><?php echo $fr_users[1]; ?></b>
                                                </a>

                                                Shared you a post by
                                                <a href="user_profile.php?username=<?php echo $post_user[2]; ?>" class='text-decoration-none'>
                                                    <b><?php echo $post_user[1]; ?></b> 
                                                </a>
                                                on <?php echo date("F d, Y", strtotime($shared_on)); ?>
                                            </p>

                                            <a href="post_info.php?user_id=<?php echo $post_user[0]; ?>&post_id=<?php echo $p_id; ?>">
                                                <div class="shared-post d-flex" style="overflow-y: auto; max-height: 300px;">
                                                    <!-- Post Preview on the Left Side -->
                                                    <div class="post-preview">

                                                        <?php
                                                        $isVideo = preg_match('/\.(mp4|webm|ogg)$/i', $post_path);
                                                        if ($isVideo) {
                                                            ?>

                                                            <video controls style="width: 300px !important; margin-right: 30px !important; border-radius: 8px !important;">
                                                                <source src="<?php echo htmlspecialchars($post_path); ?>" type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>

                                                            <?php
                                                        } 
                                                        else {
                                                            ?>

                                                            <img src="<?php echo htmlspecialchars($post_path); ?>" alt="Pixtream Post" class="img-fluid" style="width: 300px !important; margin-right: 30px !important; border-radius: 8px !important;">

                                                            <?php
                                                        }
                                                        ?>

                                                    </div>

                                                    <!-- Post Information on the Right Side -->
                                                    <div class="post-info">
                                                        <div class="post-header d-flex align-items-center" style="background-color: #00000000;">
                                                            <img src="profile_picture/<?php echo $post_user[6]; ?>" alt="" class="dp shared-dp">
                                                            <div class="post-head">
                                                                <span><?php echo $post_user[1]; ?></span><br>
                                                                <span class="small text-muted" style="margin-left: -30px;"><?php echo $post_user[2]; ?></span>
                                                            </div>            
                                                        </div> 
                                                        <div class="mb-2">
                                                            <div class="mt-2">
                                                                <span>
                                                                    <?php echo $posts[3]; ?>
                                                                </span><br>
                                                                <span class="badge mybadge"><?php echo $posts[4]; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            
                                            <hr>

                                            <?php
                                        }
                                    }
                                }
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to fetch recent users
            function fetchRecentUsers() {
                $.ajax({
                    url: 'fetch_recent_users.php', // Create this file to handle fetching recent users
                    method: 'GET',
                    success: function(response) {
                        $('#recentChatsContainer').html(response);
                    }
                });
            }

            // Search users
            $('#searchUser').on('input', function() {
                var query = $(this).val();
                if (query.length > 0) {
                    $.ajax({
                        url: 'messaging.php',
                        method: 'POST',
                        data: { searchUser: query },
                        success: function(response) {
                            $('#searchResults').html(response).show();
                        }
                    });
                } else {
                    $('#searchResults').hide();
                }
            });

            // Clear search input
            $('#clearSearch').on('click', function() {
                $('#searchUser').val('');
                $('#searchResults').hide();
            });

            // Select user from dropdown
            $(document).on('click', '.user-item', function() {
                var username = $(this).data('username');
                $('#receiver').val(username);
                $('#modalTitle').text('Send Message to ' + username);
                $('#messageModal').modal('show');
                $('#searchResults').hide();
            });

            // Handle message sending
            $('#messageForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: 'messaging.php',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#messageStatus').text(response);
                        $('#message').val(''); // Clear the message input

                        // Fetch recent users after sending a message
                        fetchRecentUsers();
                    }
                });
            });

            // Initial fetch of recent users
            fetchRecentUsers();
        });
    </script>
</body>
</html>

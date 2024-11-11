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

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
        <a class="navbar-brand" href="#">
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
            <div id="searchResults" class="dropdown-menu chat-dropdown"></div>
        </div>

       <!-- Recent Chats Section -->
<div class="recent-chats">
    <h5 class="mt-4 mb-5"><b>Recent Chats</b></h5>
    <div id="recentChatsContainer">
        <?php foreach ($recentUsers as $user): 
            $userDP = !empty($user['profile_picture']) ? $user['profile_picture'] : 'default.png'; // Use default if empty
            $user_user_id = $user['id']; // User ID from database                    
            
            // Count followers
            $fcount_sql = "SELECT COUNT(*) FROM follows WHERE followed_id = '$user_user_id';";
            $fcount_result = mysqli_query($conn, $fcount_sql);
            $followCount = mysqli_fetch_array($fcount_result)[0];
            
            // Count posts
            $pcount_sql = "SELECT COUNT(*) FROM posts WHERE user_id = '$user_user_id';";
            $pcount_result = mysqli_query($conn, $pcount_sql);
            $postCount = mysqli_fetch_array($pcount_result)[0];
        ?>
            <div class="user-item p-3"
                data-name="<?php echo htmlspecialchars($user['name']); ?>"
                data-user-name="<?php echo htmlspecialchars($user['username']); ?>"
                data-profile-picture="<?php echo "profile_picture/$userDP"; ?>"
                data-follow-count="<?php echo htmlspecialchars($followCount); ?>"
                data-post-count="<?php echo htmlspecialchars($postCount); ?>"
                data-user-id="<?php echo htmlspecialchars($user_user_id); ?>"
                onclick="showUserDetails(this)">

                <a href="#" class="list-group-item list-group-item-action">
                    <div class="profile-img-container">
                        <img src="profile_picture/<?php echo htmlspecialchars($userDP); ?>" alt="Profile Photo" width="50">
                    </div>
                    <strong class="fs-5"><?php echo htmlspecialchars($user['name']); ?></strong>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function showUserDetails(userElement) {            
    const name = userElement.getAttribute('data-name');
    const userName = userElement.getAttribute('data-user-name');
    const profilePicture = userElement.getAttribute('data-profile-picture') || 'profile_picture/default.png'; // Use default if empty
    const followCount = userElement.getAttribute('data-follow-count');
    const postCount = userElement.getAttribute('data-post-count');
    const userId = userElement.getAttribute('data-user-id'); // Retrieve user ID correctly

    // Debugging: Check if userId is retrieved correctly
    console.log('User ID:', userId);

    // Populate the modal with user details            
    document.getElementById('messageModalLabel').innerText = name;
    document.getElementById('modalProfilePicture').src = profilePicture;
    document.getElementById('modalUserName').innerText = name;
    document.getElementById('modalUserId').innerText = `User ID: ${userId}`; // Set user ID in the modal
    document.getElementById('modalUserFollowCount').innerText = `${followCount} Followers`;
    document.getElementById('modalUserPostCount').innerText = `${postCount} Posts`;

    // Set the hidden receiver input value to the user's ID
    document.getElementById('receiver').value = userId;

    // Update the View Profile button href correctly
    document.getElementById('viewProfileButton').href = 'user_profile.php?username=' + encodeURIComponent(userName);

    // Show the modal
    $('#messageModal').modal('show');
}

</script>




        <!-- Message Modal -->
        <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable chat-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="messageModalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="chat-area">
                            <div id="chatWindow" class="mb-3 chat-window">
                                <img id="modalProfilePicture" src="" class="rounded-circle mb-2" alt="Profile Photo" style="width: 130px;">
                                <p class="text-center"><strong id="modalUserName"></strong></p>
                                <a href="user_profile.php?username=" id="viewProfileButton" class="btn mybtn-outline btn-sm mb-3">View Profile</a>
                                <p class="text-center"><span id="modalUserId"></span></p>
                                <p class="text-center"><span id="modalUserFollowCount"></span> &emsp; <span id="modalUserPostCount"></span></p>                            
                            </div>

                            <div class="chat-area">
                                
                            </div>
                        </div>
                    </div>
                    <div class="message-area">
                        <form id="messageForm" action="#" method="POST">
                            <div class="input-group">
                                <input type="hidden" id="receiver" name="receiver" value="">
                                <textarea class="form-control" id="message" name="message" rows="1" placeholder="Type your message..."></textarea>
                                <button type="submit" class="btn mybtn"><i class="fa-solid fa-arrow-up"></i></button>
                            </div>
                        </form>
                    </div>

                    <?php
                    // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    //     // Get the username of the receiver and the message
                    //     $sender = $info['id']; // Get sender's ID from user info
                    //     $receiver = $_POST['receiver']; // Receiver's username
                    //     $message = $_POST['message']; // Message content

                        

                    //     echo $sender, $reciever, $message;
                    
                    //     // Prepare the SQL insert statement to add the message
                    //     $insertSql = "INSERT INTO messages (sender_id, receiver_id, message, created_at) VALUES ('$sender', '$receiver', '$message', NOW())";
                    //     $insertResult = mysqli_query($conn, $insertSql);
                        
                    //     if ($insertResult){
                    //         echo "Message sent!";
                    //         echo $sender, $reciever, $message;
                    //     }
                    //     else{
                    //         echo "Message not sent!";
                    //     }
                    // }
                    ?>

                    <div id="messageStatus" class="mt-2"></div>
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

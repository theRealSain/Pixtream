<?php
include 'dbconfig.php';
session_start();

error_reporting(0);

if (!isset($_SESSION['username'])) {
    header('location:authen.php');
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);
$log_id = $info['id'];
$name = $info['name'];

$receiver_id = $_GET['user_id'];
$user_sql = "SELECT * FROM users WHERE id='$receiver_id'";
$user_result = mysqli_query($conn, $user_sql);
$user_info = mysqli_fetch_assoc($user_result);
$receiver_id = $user_info['id'];
$receiver_name = $user_info['name'];
$receiver_username = $user_info['username'];
$receiver_profile_pic = $user_info['profile_picture'];

// Count followers
$fcount_sql = "SELECT COUNT(*) FROM follows WHERE followed_id = '$receiver_id';";
$fcount_result = mysqli_query($conn, $fcount_sql);
$fcount_info = mysqli_fetch_array($fcount_result);
$followCount = $fcount_info[0];

// Count posts
$pcount_sql = "SELECT COUNT(*) FROM posts WHERE user_id = '$receiver_id';";
$pcount_result = mysqli_query($conn, $pcount_sql);
$pcount_info = mysqli_fetch_array($pcount_result);
$postCount = $pcount_info[0];

// Fetch messages
$messages_query = "SELECT * FROM messages WHERE (sender_id='$log_id' AND receiver_id='$receiver_id') 
                   OR (sender_id='$receiver_id' AND receiver_id='$log_id') ORDER BY created_at";
$messages_result = mysqli_query($conn, $messages_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
    
    <!-- Chat Container -->
    <div class="chat-container">

        <!-- Header with Receiver's Profile Picture and Name -->
        <div class="chat-header d-flex align-items-center">
            <img src="profile_picture/<?php echo htmlspecialchars($receiver_profile_pic); ?>" 
                alt="Profile Picture" width="30" height="30" class="me-2" style="border-radius: 50%;"/>
            <span><?php echo htmlspecialchars($receiver_name); ?></span>
        </div>
        
        <!-- Messages Area -->
        <div class="messages" id="messages">
            <div class="chat-info">
                <div class="mb-3">
                    <img id="modalProfilePicture" src="profile_picture/<?php echo htmlspecialchars($receiver_profile_pic); ?>" class="rounded-circle chat-dp mb-2" alt="Profile Photo" style="width: 130px;">
                    <p class="text-center"><strong id="modalUserName"><?php echo htmlspecialchars($receiver_name); ?></strong></p>
                    <a href="user_profile.php?username=<?php echo htmlspecialchars($receiver_username); ?>"> <p class="btn mybtn-outline btn-sm mb-3 chat-pf">View Profile</p></a>
                    <p class="text-center"><span id="modalUserFollowCount"><?php echo htmlspecialchars($followCount); ?> Followers</span> &emsp; 
                    <span id="modalUserPostCount"><?php echo htmlspecialchars($postCount); ?> Posts</span></p>
                </div>
            </div>
        
            <?php while ($msg = mysqli_fetch_assoc($messages_result)): ?>
            <div class="message <?php echo $msg['sender_id'] == $log_id ? 'sent' : 'received'; ?>">
                <?php echo htmlspecialchars($msg['message']); ?>
                <span class="timestamp"><?php echo date("g:i A", strtotime($msg['created_at'])); ?></span>
            </div>
            <?php endwhile; ?>

        </div>

        <form class="message-form" action="messaging.php" method="POST">
            <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
            <div class="input-group">
                <textarea name="message" class="form-control" placeholder="Type a message..." rows="1" required></textarea>                
                <button type="submit" class="btn mybtn" name="send-msg"><i class="fa-solid fa-caret-right"></i></button>
            </div>
        </form>

        <span class="confirm-alert">
            &nbsp;
            <?php
            if($_SESSION['msg-success'])
            {
                echo $_SESSION['msg-success'];
            }
            unset($_SESSION['msg-success']);

            if($_SESSION['msg-fail'])
            {
                echo $_SESSION['msg-fail'];
            }
            unset($_SESSION['msg-fail']);
            ?>
        </span>
        
    </div>

    <script>
        // JavaScript to handle sending messages
        function sendMessage() {
            let message = document.getElementById('messageInput').value;
            if (message.trim() !== '') {
                // Logic for sending message to the server
                alert('Message sent: ' + message);
                document.getElementById('messageInput').value = ''; // Clear input after sending
            }
        }
    </script>

    <script>
        window.onload = function() {
            // Get the messages container
            var messagesContainer = document.getElementById('messages');
            
            // Scroll to the bottom of the messages container
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    </script>


    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</body>
</html>
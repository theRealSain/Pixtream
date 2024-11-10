<?php
include 'dbconfig.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('location:authen.php');
    exit();
}

$username = $_SESSION['username'];

if (isset($_POST['searchUser'])) {
    $searchQuery = mysqli_real_escape_string($conn, $_POST['searchUser']);
    $searchedUsers = [];

    if (!empty($searchQuery)) {
        $searchSQL = "
            SELECT u.* 
            FROM users u 
            WHERE (u.username LIKE '$searchQuery%' OR u.name LIKE '$searchQuery%') 
            AND u.username != '$username'";
        
        $searchResult = mysqli_query($conn, $searchSQL);

        while ($user = mysqli_fetch_assoc($searchResult)) {
            $profilePic = !empty($user['profile_picture']) ? $user['profile_picture'] : 'profile_picture/default.png';
            echo '
            <a href="chat_screen.php?user_id=' . $user['id'] . '" style="text-decoration: none; display: flex; align-items: center;">
                <div class="dropdown-item user-item d-flex align-items-center">                
                        <img src="profile_picture/' . $profilePic . '" alt="Profile Picture" width="30" height="30" class="me-2">
                        <div class="user-info">
                            <div class="name">' . htmlspecialchars($user['name']) . '</div>
                            <div class="username text-muted" style="font-size: 0.85em;">' . htmlspecialchars($user['username']) . '</div>
                        </div>                    
                </div>
            </a>';
        }        
    } else {
        echo '<div class="dropdown-item text-center">No users found</div>';
    }
    exit();
}

// Send message
if (isset($_POST['send-msg']))
{
    $message = $_POST['message'];
    $receiver_id = $_POST['receiver_id'];

    if (!empty($message) && !empty($receiver_id)) {
        // Get sender's user ID
        $senderSQL = "SELECT * FROM users WHERE username='$username'";
        $senderResult = mysqli_query($conn, $senderSQL);
        $senderData = mysqli_fetch_assoc($senderResult);
        $sender_id = $senderData['id'];

        // Insert message into messages table
        $insertSQL = "INSERT INTO messages (sender_id, receiver_id, message, created_at) VALUES ('$sender_id', '$receiver_id', '$message', NOW())";
        echo $insertSQL;
        $insertResult = mysqli_query($conn, $insertSQL);
        if ($insertResult) {
            $_SESSION['msg-success'] = 'Sent!';
            header("location:chat_screen.php?user_id=" . urlencode($receiver_id));
        } 
        else {
            $_SESSION['msg-fail'] = 'Failed!';
            header("location:chat_screen.php?user_id=" . urlencode($receiver_id));
        }
    }
    exit();
}

// Fetch messages between the logged-in user and selected user
if (isset($_POST['fetchMessages'])) {
    $partnerUsername = mysqli_real_escape_string($conn, $_POST['partnerUsername']);

    // Get sender's user ID
    $senderSQL = "SELECT id FROM users WHERE username='$username'";
    $senderResult = mysqli_query($conn, $senderSQL);
    $senderData = mysqli_fetch_assoc($senderResult);
    $sender_id = $senderData['id'];

    // Get receiver's user ID
    $receiverSQL = "SELECT id FROM users WHERE username='$partnerUsername'";
    $receiverResult = mysqli_query($conn, $receiverSQL);
    $receiverData = mysqli_fetch_assoc($receiverResult);
    $receiver_id = $receiverData['id'];

    // Fetch messages between the two users
    $messagesSQL = "
        SELECT m.*, u.name, u.username 
        FROM messages m 
        JOIN users u ON u.id = IF(m.sender_id = '$sender_id', m.receiver_id, m.sender_id)
        WHERE (m.sender_id = '$sender_id' AND m.receiver_id = '$receiver_id') 
           OR (m.sender_id = '$receiver_id' AND m.receiver_id = '$sender_id')
        ORDER BY m.created_at ASC"; // Change ASC to DESC if you want the newest messages first
    $messagesResult = mysqli_query($conn, $messagesSQL);
    
    while ($message = mysqli_fetch_assoc($messagesResult)) {
        echo '
        <div class="message ' . ($message['sender_id'] == $sender_id ? 'sent' : 'received') . '">
            <strong>' . htmlspecialchars($message['username']) . ':</strong> ' . htmlspecialchars($message['message']) . '
            <span class="timestamp">' . date('Y-m-d H:i:s', strtotime($message['created_at'])) . '</span>
        </div>';
    }
    exit();
}
?>

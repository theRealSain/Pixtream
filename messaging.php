<?php
include 'dbconfig.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('location:authen.php');
    exit();
}

$username = $_SESSION['username'];

// Function to fetch recent chats
function getRecentChats($conn, $username) {
    $recentChats = [];
    
    // Get sender's user ID
    $senderSQL = "SELECT id FROM users WHERE username='$username'";
    $senderResult = mysqli_query($conn, $senderSQL);
    $senderData = mysqli_fetch_assoc($senderResult);
    $sender_id = $senderData['id'];

    // Fetch recent messages for the logged-in user
    $messageSQL = "
        SELECT m.*, u.name, u.username, u.profile_picture 
        FROM messages m 
        JOIN users u ON (u.id = m.sender_id OR u.id = m.receiver_id)
        WHERE (m.sender_id = '$sender_id' OR m.receiver_id = '$sender_id')
        ORDER BY m.created_at DESC
        LIMIT 10"; // Adjust the limit as needed
    $messageResult = mysqli_query($conn, $messageSQL);

    while ($chat = mysqli_fetch_assoc($messageResult)) {
        // Store recent chat with sender/receiver information
        $recentChats[] = [
            'chat_id' => $chat['id'],
            'message' => $chat['message'],
            'created_at' => $chat['created_at'],
            'partner_username' => ($chat['sender_id'] == $sender_id) ? $chat['receiver_id'] : $chat['sender_id'],
            'partner_name' => $chat['name'],
            'profile_picture' => !empty($chat['profile_picture']) ? $chat['profile_picture'] : 'profile_picture/default.png',
            'is_sender' => $chat['sender_id'] == $sender_id
        ];
    }
    
    return $recentChats;
}

// Load recent chats
$recentChats = getRecentChats($conn, $username);

if (isset($_POST['searchUser'])) {
    $searchQuery = mysqli_real_escape_string($conn, $_POST['searchUser']);
    $searchedUsers = [];

    if (!empty($searchQuery)) {
        $searchSQL = "
            SELECT u.*, 
                   (SELECT COUNT(*) FROM follows WHERE followed_id = u.id) AS follow_count, 
                   (SELECT COUNT(*) FROM posts WHERE user_id = u.id) AS post_count 
            FROM users u 
            WHERE (u.username LIKE '$searchQuery%' OR u.name LIKE '$searchQuery%') 
            AND u.username != '$username'";
        
        $searchResult = mysqli_query($conn, $searchSQL);

        while ($user = mysqli_fetch_assoc($searchResult)) {
            $profilePic = !empty($user['profile_picture']) ? $user['profile_picture'] : 'profile_picture/default.png';
            echo '
            <div class="dropdown-item user-item" 
                 data-name="' . htmlspecialchars($user['name']) . '" 
                 data-user-name="' . htmlspecialchars($user['username']) . '" 
                 data-profile-picture="profile_picture/' . htmlspecialchars($profilePic) . '" 
                 data-follow-count="' . htmlspecialchars($user['follow_count']) . '" 
                 data-post-count="' . htmlspecialchars($user['post_count']) . '" 
                 onclick="showUserDetails(this)">
                <img src="profile_picture/' . $profilePic . '" alt="Profile Picture" width="30">
                <div>
                    <div class="name">' . htmlspecialchars($user['name']) . '</div>
                    <div class="username">' . htmlspecialchars($user['username']) . '</div>                    
                </div>
            </div>';
        }
    } else {
        echo '<div class="dropdown-item text-center">No users found</div>';
    }
    exit();
}



// Send message
if (isset($_POST['message'], $_POST['receiver'])) {
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $receiver = mysqli_real_escape_string($conn, $_POST['receiver']);

    if (!empty($message) && !empty($receiver)) {
        // Get sender's user ID
        $senderSQL = "SELECT id FROM users WHERE username='$username'";
        $senderResult = mysqli_query($conn, $senderSQL);
        $senderData = mysqli_fetch_assoc($senderResult);
        $sender_id = $senderData['id'];

        // Get receiver's user ID
        $receiverSQL = "SELECT id FROM users WHERE username='$receiver'";
        $receiverResult = mysqli_query($conn, $receiverSQL);
        $receiverData = mysqli_fetch_assoc($receiverResult);
        $receiver_id = $receiverData['id'];

        // Insert message into messages table
        $insertSQL = "INSERT INTO messages (sender_id, receiver_id, message, created_at) VALUES ('$sender_id', '$receiver_id', '$message', NOW())";
        if (mysqli_query($conn, $insertSQL)) {
            echo 'Message sent';
        } else {
            echo 'Failed to send message';
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

// Display recent chats in the modal
if (!empty($recentChats)) {
    foreach ($recentChats as $chat) {
        echo '
        <div class="recent-chat">
            <a href="#" class="chat-link" data-username="' . htmlspecialchars($chat['partner_username']) . '">
                <img src="profile_picture/' . htmlspecialchars($chat['profile_picture']) . '" alt="Profile Picture">
                <div class="chat-info">
                    <span class="chat-name">' . htmlspecialchars($chat['partner_name']) . '</span>
                    <span class="chat-message">' . htmlspecialchars($chat['message']) . '</span>
                    <span class="chat-timestamp">' . date('Y-m-d H:i:s', strtotime($chat['created_at'])) . '</span>
                </div>
            </a>
        </div>';
    }
}
?>

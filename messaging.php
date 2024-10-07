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
        $searchSQL = "SELECT * FROM users WHERE (username LIKE '$searchQuery%' OR name LIKE '$searchQuery%') AND username != '$username'";
        $searchResult = mysqli_query($conn, $searchSQL);

        while ($user = mysqli_fetch_assoc($searchResult)) {
            $searchedUsers[] = $user;
        }
    }

    if (!empty($searchedUsers)) {
        foreach ($searchedUsers as $user) {
            $profilePic = !empty($user['profile_picture']) ? $user['profile_picture'] : 'profile_picture/default.png';
            echo '
            <a class="dropdown-item user-item" data-username="' . $user['username'] . '">
                <img src="profile_picture/' . $profilePic . '" alt="Profile Picture">
                <div>
                    <div class="name">' . $user['name'] . '</div>
                    <div class="username">' . $user['username'] . '</div>
                </div>
            </a>';
        }
    } else {
        echo '<div class="dropdown-item text-center">No users found</div>';
    }
    exit();
}

if (isset($_POST['message'], $_POST['receiver'])) {
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $receiver = mysqli_real_escape_string($conn, $_POST['receiver']);

    if (!empty($message) && !empty($receiver)) {
        $insertSQL = "INSERT INTO messages (sender, receiver, content) VALUES ('$username', '$receiver', '$message')";
        if (mysqli_query($conn, $insertSQL)) {
            echo 'Message sent';
        } else {
            echo 'Failed to send message';
        }
    }
    exit();
}
?>

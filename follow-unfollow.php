<?php
include 'dbconfig.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    echo 'error';
    exit();
}

$current_user = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure required fields are available
    if (isset($_POST['action']) && isset($_POST['username'])) {
        $action = $_POST['action'];
        $username = $_POST['username'];

        // Check if the user is attempting to follow or unfollow themselves
        if ($current_user === $username) {
            echo 'error';
            exit();
        }

        // Use prepared statements to prevent SQL injection
        if ($action == 'follow') {
            $stmt = $conn->prepare("INSERT INTO follows (follower, following, created_at) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $current_user, $username);
        } else if ($action == 'unfollow') {
            $stmt = $conn->prepare("DELETE FROM follows WHERE follower=? AND following=?");
            $stmt->bind_param("ss", $current_user, $username);
        } else {
            echo 'error';
            exit();
        }

        // Execute the prepared statement and check the result
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }

        $stmt->close();
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>

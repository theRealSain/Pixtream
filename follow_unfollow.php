<?php
include 'dbconfig.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['username'])) {
    header('Location: authen.php');
    exit;
}

$followerUsername = $_SESSION['username'];
$followedUsername = $_POST['username'];
$action = $_POST['action'];

// Get the follower's ID
$followerQuery = "SELECT id FROM users WHERE username='$followerUsername'";
$followerResult = mysqli_query($conn, $followerQuery);
$followerRow = mysqli_fetch_assoc($followerResult);
$followerId = $followerRow['id'];

// Get the followed user's ID
$followedQuery = "SELECT id FROM users WHERE username='$followedUsername'";
$followedResult = mysqli_query($conn, $followedQuery);
$followedRow = mysqli_fetch_assoc($followedResult);
$followedId = $followedRow['id'];

if ($action === 'follow') {
    // Ensure both IDs are valid before inserting
    if ($followerId && $followedId) {
        $sql = "INSERT INTO follows (follower_id, followed_id) VALUES ('$followerId', '$followedId')";
        if (mysqli_query($conn, $sql)) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            echo "Failed to follow user.";
        }
    } else {
        echo "Invalid follower or followed user.";
    }
} elseif ($action === 'unfollow') {
    if ($followerId && $followedId) {
        $sql = "DELETE FROM follows WHERE follower_id = '$followerId' AND followed_id = '$followedId'";
        if (mysqli_query($conn, $sql)) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            echo "Failed to unfollow user.";
        }
    } else {
        echo "Invalid follower or followed user.";
    }
} else {
    echo "Invalid action.";
}

mysqli_close($conn);
?>

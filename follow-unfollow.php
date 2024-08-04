<?php
include 'dbconfig.php';
session_start();
$current_user = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $username = $_POST['username'];

    if ($action == 'follow') {
        $sql = "INSERT INTO follows (follower, following, created_at) VALUES ('$current_user', '$username', NOW())";
    } else if ($action == 'unfollow') {
        $sql = "DELETE FROM follows WHERE follower='$current_user' AND following='$username'";
    }

    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>

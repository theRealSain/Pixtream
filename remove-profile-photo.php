<?php
include 'dbconfig.php';
session_start();

$username = $_SESSION['username'];

// Update the profile photo to default.png in the database
$sql = "UPDATE users SET profile_picture = 'default.png' WHERE username='$username'";
if (mysqli_query($conn, $sql)) {
    $_SESSION['profile_picture'] = 'default.png';
}

header("Location: profile.php");
exit();
?>

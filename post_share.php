<?php
include 'dbconfig.php';
session_start();

// error_reporting(0);

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if(!isset($_SESSION['username']))
{
  header('location:authen.php');
}

$username = $_SESSION['username'];
$log_sql = "SELECT * FROM users WHERE username='$username';";
$log_result = mysqli_query($conn, $log_sql);
$log_info = mysqli_fetch_assoc($log_result);
$log_id = $log_info['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate the form fields
        $to_user_id = $_POST['to_user_id'];
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];

        // Prepare the query to insert the share record
        $share_sql = "INSERT INTO shares (from_user_id, to_user_id, post_id, created_at) VALUES ('$log_id', '$to_user_id', '$post_id', NOW());";
        echo $share_sql;
        $share_result = mysqli_query($conn, $share_sql);

        // Execute the statement and check for success
        if ($share_result) {
            $_SESSION['share-success'] = "Post Shared!!";
            header("Location: post_info.php?post_id=$post_id&user_id=$user_id"); // Redirect to avoid form resubmission
            exit;
        } 
        else {
            $_SESSION['share-fail'] = "Failed to share post!";
            header("Location: post_info.php?post_id=$post_id&user_id=$user_id"); // Redirect to avoid form resubmission
            exit;
        }
}
?>
<?php
session_start();
include 'dbconfig.php'; // Include your database connection

// Check if the user is logged in and post_id is provided
if (isset($_SESSION['user_id']) && isset($_POST['post_id'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];

    // Check if the post is already saved
    $query = "SELECT * FROM saved_posts WHERE user_id = ? AND post_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Post is already saved, so unsave it
        $query = "DELETE FROM saved_posts WHERE user_id = ? AND post_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $post_id);
        $stmt->execute();
        echo "unsaved"; // Response for unsave action
    } else {
        // Post is not saved, so save it
        $query = "INSERT INTO saved_posts (user_id, post_id, created_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $post_id);
        $stmt->execute();
        echo "saved"; // Response for save action
    }
}
?>

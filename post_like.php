    <?php
    include 'dbconfig.php';
    session_start();

    // Get the data from the request
    $data = json_decode(file_get_contents("php://input"), true);
    $post_id = $data['post_id'];
    $user_id = $data['user_id'];
    $liked = $data['liked'];

    // Check if the user already liked the post
    $check_sql = "SELECT * FROM likes WHERE user_id = '$user_id' AND post_id = '$post_id'";
    $check_result = mysqli_query($conn, $check_sql);

    if ($liked) {
        // If the user wants to like the post, insert a new like record
        if (mysqli_num_rows($check_result) == 0) {
            $insert_sql = "INSERT INTO likes (user_id, post_id) VALUES ('$user_id', '$post_id')";
        mysqli_query($conn, $insert_sql);
    }
} else {
    // If the user wants to remove the like, delete the like record
    if (mysqli_num_rows($check_result) > 0) {
        $delete_sql = "DELETE FROM likes WHERE user_id = '$user_id' AND post_id = '$post_id'";
        mysqli_query($conn, $delete_sql);
    }
}

// Get the updated like count
$like_count_sql = "SELECT COUNT(*) FROM likes WHERE post_id = '$post_id'";
$like_count_result = mysqli_query($conn, $like_count_sql);
$like_count_info = mysqli_fetch_array($like_count_result);
$like_count = $like_count_info[0];

// Return the response
echo json_encode(['success' => true, 'like_count' => $like_count]);
?>

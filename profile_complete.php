<?php
// Include the database connection
include 'dbconfig.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login if the session does not exist
    header("Location: authen.php");
    exit();
}

// Fetch the user_id based on the logged-in username
$username = $_SESSION['username'];
$query_user_id = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($query_user_id);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted interests and user details
    $selected_interests = isset($_POST['interests']) ? $_POST['interests'] : [];
    $location = $_POST['location'] ?? '';
    $bio = $_POST['bio'] ?? '';

    // Update user location and bio
    $query_update_details = "UPDATE users SET location = ?, bio = ? WHERE id = ?";
    $stmt_details = $conn->prepare($query_update_details);
    $stmt_details->bind_param("ssi", $location, $bio, $user_id);
    $stmt_details->execute();
    $stmt_details->close();

    // Fetch currently selected interests
    $query_current_interests = "SELECT option_id FROM user_selections WHERE user_id = ?";
    $stmt_interests = $conn->prepare($query_current_interests);
    $stmt_interests->bind_param("i", $user_id);
    $stmt_interests->execute();
    $result_interests = $stmt_interests->get_result();

    // Store existing interests in an array
    $current_interests = [];
    while ($row = $result_interests->fetch_assoc()) {
        $current_interests[] = $row['option_id'];
    }
    $stmt_interests->close();

    // Find interests to remove
    $interests_to_remove = array_diff($current_interests, $selected_interests);
    foreach ($interests_to_remove as $remove_id) {
        $query_remove_interest = "DELETE FROM user_selections WHERE user_id = ? AND option_id = ?";
        $stmt_remove = $conn->prepare($query_remove_interest);
        $stmt_remove->bind_param("ii", $user_id, $remove_id);
        $stmt_remove->execute();
        $stmt_remove->close();
    }

    // Find interests to add
    $interests_to_add = array_diff($selected_interests, $current_interests);
    foreach ($interests_to_add as $add_id) {
        $query_add_interest = "INSERT INTO user_selections (user_id, option_id) VALUES (?, ?)";
        $stmt_add = $conn->prepare($query_add_interest);
        $stmt_add->bind_param("ii", $user_id, $add_id);
        $stmt_add->execute();
        $stmt_add->close();
    }

    // Redirect to the profile page or dashboard after successful update
    header("Location: profile.php");
    exit();
}
?>

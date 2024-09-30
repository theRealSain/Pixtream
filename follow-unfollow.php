<?php
include 'dbconfig.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$current_user = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receive the raw POST body
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Ensure required fields are available
    if (isset($data['action']) && isset($data['username'])) {
        $action = $data['action'];
        $username = $data['username'];

        // Check if the user is attempting to follow or unfollow themselves
        if ($current_user === $username) {
            echo json_encode(['success' => false, 'message' => 'Cannot follow yourself']);
            exit();
        }

        // Use prepared statements to prevent SQL injection
        if ($action == 'follow') {
            $stmt = $conn->prepare("INSERT INTO follows (follower_id, followed_id, created_at) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $current_user, $username);
        } else if ($action == 'unfollow') {
            $stmt = $conn->prepare("DELETE FROM follows WHERE follower_id=? AND followed_id=?");
            $stmt->bind_param("ss", $current_user, $username);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            exit();
        }

        // Execute the prepared statement and check the result
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>

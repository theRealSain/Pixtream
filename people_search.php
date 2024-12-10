<?php
include 'dbconfig.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
    $query = htmlspecialchars($_POST['query'], ENT_QUOTES, 'UTF-8');
    $username = $_SESSION['username'];

    // Exclude the logged-in user from the search
    $stmt = $conn->prepare("SELECT id, username, profile_picture, name FROM users WHERE (username LIKE ? OR name LIKE ?) AND username != ? LIMIT 10");
    $searchTerm = $query . '%'; // Match names that start with the query
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $profilePicture = !empty($row['profile_picture']) ? htmlspecialchars($row['profile_picture']) : 'assets/img/default.png';
            echo '<a href="user_profile.php?username=' . htmlspecialchars($row['username']) . '" class="dropdown-item people-dd-i">';
            echo '<img src="profile_picture/' . $profilePicture . '" alt="' . htmlspecialchars($row['username']) . '">';
            echo '<div class="user-info">';
            echo '<strong>' . htmlspecialchars($row['name']) . '</strong>';  // Display name in bold
            echo '<br><span class="username" style="font-weight: 500">' . htmlspecialchars($row['username']) . '</span>';  // Display username in light text
            echo '</div>';
            echo '</a>';
        }
    } else {
        echo '<div class="dropdown-item text-muted">No users found</div>';
    }

    $stmt->close();
    $conn->close();
}
?>

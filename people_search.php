<?php
// Include database connection
include 'dbconfig.php';

if (isset($_GET['query']) && !empty($_GET['query'])) {
    $searchQuery = mysqli_real_escape_string($conn, $_GET['query']); // Sanitize user input

    // Query to search for users based on the search query
    $sql = "SELECT id, name, username, profile_picture 
            FROM users 
            WHERE name LIKE '%$searchQuery%' OR username LIKE '%$searchQuery%' 
            LIMIT 5";  // Limiting to 5 results to avoid overload
    $result = mysqli_query($conn, $sql);

    // Check if any results were found
    if (mysqli_num_rows($result) > 0) {
        // Display each result
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<a class="dropdown-item" href="profile.php?id=' . $row['id'] . '">';
            echo '<img src="' . $row['profile_picture'] . '" alt="' . $row['name'] . '" class="rounded-circle" width="30" height="30"> ';
            echo $row['name'] . ' (' . $row['username'] . ')';
            echo '</a>';
        }
    } else {
        // If no results, display a message
        echo '<a class="dropdown-item disabled">No users found</a>';
    }
} else {
    echo '<a class="dropdown-item disabled">Please enter a search query</a>';
}

mysqli_close($conn); // Close the database connection
?>

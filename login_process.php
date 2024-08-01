<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change if you have a different database username
$password = "root"; // Change if you have a database password
$dbname = "s-pixtream";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Fetch user
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if ($password == $user['password']) { // Simple string comparison
            echo "Login successful!";
            // Start session and set session variables
            session_start();
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            // Redirect to dashboard or home page
            header("Location: dashboard.php");
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with that username or email!";
    }
}

$conn->close();
?>

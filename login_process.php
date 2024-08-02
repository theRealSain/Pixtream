<?php
// Database connection
include 'dbconfig.php';
session_start();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Fetch user
    $sql = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if ($password == $user['password']) { // Simple string comparison
            // Start session and set session variables
            $_SESSION['username'] = $user['username'];
            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password!";
            header("Location: auth.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "No user found with that username!";
        header("Location: auth.php");
        exit();
    }
}

$conn->close();
?>

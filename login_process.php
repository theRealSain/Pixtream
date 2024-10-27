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

        // Check if the user has an approved report
        $reportCheckSql = "SELECT approval FROM reports WHERE reported_user = " . $user['id'] . " AND approval = TRUE LIMIT 1";
        $reportCheckResult = $conn->query($reportCheckSql);

        // If user has an approved report, show a suspension message
        if ($reportCheckResult->num_rows > 0) {
            $_SESSION['error'] = "Your account is suspended for 1 day due to a report. Please try again later.";
            header("Location: authen.php");
            exit();
        }

        // Verify password (ensure to use password hashing in real scenarios)
        if ($password == $user['password']) { // Simple string comparison; ideally, use password_verify() here
            // Start session and set session variables
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id']; // Store user ID for future use
            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password!";
            header("Location: authen.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "No user found with that username!";
        header("Location: authen.php");
        exit();
    }
}

$conn->close();
?>

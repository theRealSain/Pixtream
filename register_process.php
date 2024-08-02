<?php
// Database connection
include 'dbconfig.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // Check if username or email already exists
    $check_user = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $result = $conn->query($check_user);

    if ($result->num_rows > 0) {
        echo "Username or Email already exists";
    } else {
        $sql = "INSERT INTO users (name, username, email, password) VALUES ('$name', '$username', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful!";
            // Redirect to login page or dashboard
            header("Location: index.html");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

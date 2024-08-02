<?php
session_start();
include 'dbconfig.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the photo file is set
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size (limit to 5 MB)
        if ($_FILES["photo"]["size"] > 5000000) {
            echo "Sorry, your file is too large. Maximum file size is 5 MB.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // Ensure the upload directory exists
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true); // Create the directory with appropriate permissions
            }

            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $username = $_SESSION['username']; // Get the username from session
                $caption = $conn->real_escape_string($_POST['caption']); // Get and sanitize the caption
                $created_at = date('Y-m-d H:i:s'); // Current timestamp

                $sql = "INSERT INTO photos (username, photo_path, caption, created_at) VALUES ('$username', '".basename($_FILES["photo"]["name"])."', '$caption', '$created_at')";

                if ($conn->query($sql) === TRUE) {
                    echo "The file ". htmlspecialchars(basename($_FILES["photo"]["name"])). " has been uploaded.";
                    header("Location: dashboard.php"); // Redirect to dashboard on success
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error; // Display database error
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "No file uploaded or there was an upload error.";
    }
}

$conn->close();
?>

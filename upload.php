<?php
include 'dbconfig.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $caption = mysqli_real_escape_string($conn, $_POST['caption']);
    
    // File upload handling
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = $_FILES['photo']['name'];
        $fileSize = $_FILES['photo']['size'];
        $fileType = $_FILES['photo']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $uploadFileDir = './posts/';

        // Create the 'posts' directory if it doesn't exist
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }

        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $sql = "INSERT INTO photos (username, photo_path, caption) VALUES ('$username', '$newFileName', '$caption')";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['upload_message'] = "Photo uploaded successfully!";
            } else {
                $_SESSION['upload_message'] = "Failed to upload photo. Please try again.";
            }
        } else {
            $_SESSION['upload_message'] = "Error moving uploaded file. Please try again.";
        }
    } else {
        $_SESSION['upload_message'] = "No file was uploaded or there was an upload error.";
    }
    
    header('Location: dashboard.php');
    exit();
}

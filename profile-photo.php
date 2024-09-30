<?php
include 'dbconfig.php';
session_start();

// Check if a file was uploaded
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
    $username = $_SESSION['username'];
    $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
    $fileName = $_FILES['profile_picture']['name'];
    $fileSize = $_FILES['profile_picture']['size'];
    $fileType = $_FILES['profile_picture']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    
    // Define allowed file extensions and file size limit
    $allowedExts = array('jpg', 'jpeg', 'png', 'gif');
    $fileSizeLimit = 5 * 1024 * 1024; // 5MB

    if (in_array($fileExtension, $allowedExts) && $fileSize <= $fileSizeLimit) {
        // Create a new file name
        $newFileName = $username . '.' . $fileExtension;
        $uploadFileDir = './profile_picture/';
        $dest_path = $uploadFileDir . $newFileName;
        
        // Move the file to the assets folder
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Update the user's profile photo in the database
            $sql = "UPDATE users SET profile_picture='$newFileName' WHERE username='$username'";
            if (mysqli_query($conn, $sql)) {
                // Redirect back to profile page
                header('Location: profile.php?upload_success=1');
                exit();
            } else {
                echo 'Database update failed: ' . mysqli_error($conn);
            }
        } else {
            echo 'Error moving the uploaded file. Check the permissions on the upload folder.';
        }
    } else {
        echo 'Invalid file type or file size exceeds the limit.';
    }
} else {
    echo 'No file uploaded or an upload error occurred.';
}
?>

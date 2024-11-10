<?php
include 'dbconfig.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('location:authen.php');
    exit();
}

$username = $_SESSION['username'];

// Retrieve user ID based on the username
$user_query = "SELECT id FROM users WHERE username='$username';";
$user_result = mysqli_query($conn, $user_query);
$user_info = mysqli_fetch_assoc($user_result);
$user_id = $user_info['id'] ?? null;

if (!$user_id) {
    $_SESSION['upload_message'] = 'User ID not found.';
    header('location: dashboard.php');
    exit();
}

// Check if form data is received and a file is uploaded
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['media'])) {
    error_log("File upload initiated."); // Log upload initiation

    $caption = mysqli_real_escape_string($conn, $_POST['caption']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    
    // Allowed file types for upload
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/x-msvideo', 'video/x-flv', 'video/ogg', 'video/webm'];
    $upload_dir = 'post_uploads/';
    
    // File information
    $file = $_FILES['media'];
    $file_name = $file['name'];
    $file_tmp_name = $file['tmp_name'];
    $file_type = $file['type'];
    $file_error = $file['error'];
    $file_size = $file['size'];

    // Log file details
    error_log("Uploaded file: $file_name, Type: $file_type, Size: $file_size bytes");

    // Validate file type
    if (!in_array($file_type, $allowed_types)) {
        error_log("Invalid file type: $file_type");
        $_SESSION['upload_message'] = 'Invalid file type. Only images (JPEG, PNG, GIF) and supported video formats are allowed.';
        header('location: dashboard.php');
        exit();
    }

    // Check for upload errors
    if ($file_error !== UPLOAD_ERR_OK) {
        switch ($file_error) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $_SESSION['upload_message'] = 'File size exceeds the allowed limit.';
                break;
            case UPLOAD_ERR_PARTIAL:
                $_SESSION['upload_message'] = 'File was only partially uploaded. Please try again.';
                break;
            case UPLOAD_ERR_NO_FILE:
                $_SESSION['upload_message'] = 'No file was uploaded. Please choose a file.';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $_SESSION['upload_message'] = 'Temporary folder is missing. Please contact support.';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $_SESSION['upload_message'] = 'Failed to write file to disk.';
                break;
            case UPLOAD_ERR_EXTENSION:
                $_SESSION['upload_message'] = 'A PHP extension stopped the file upload.';
                break;
            default:
                $_SESSION['upload_message'] = 'An unknown error occurred. Error code: ' . $file_error;
                break;
        }
        header('location: dashboard.php');
        exit();
    }

    // Check if the file size exceeds server limits (100MB)
    if ($file_size > 104857600) { // 100 MB limit
        $_SESSION['upload_message'] = 'File size exceeds the allowed limit of 100MB.';
        header('location: dashboard.php');
        exit();
    }

    // Generate a unique file name
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $new_file_name = uniqid('post_') . '.' . $file_extension;
    $upload_path = $upload_dir . $new_file_name;

    // Ensure the upload directory exists and has the correct permissions
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            $_SESSION['upload_message'] = 'Failed to create upload directory.';
            header('location: dashboard.php');
            exit();
        }
    }

    // Move file to upload directory
    if (move_uploaded_file($file_tmp_name, $upload_path)) {
        // Insert post details into the database using user_id
        $sql = "INSERT INTO posts (user_id, post_path, caption, category, created_at) 
                VALUES ('$user_id', '$upload_path', '$caption', '$category', NOW())";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['uploaded_post'] = [
                'path' => $upload_path,
                'caption' => $caption,
                'category' => $category,
            ];
            $_SESSION['upload_message'] = 'Post uploaded successfully!';
        } else {
            $_SESSION['upload_message'] = 'Error uploading post to the database: ' . mysqli_error($conn);
        }
    } else {
        $_SESSION['upload_message'] = 'Error moving file to upload directory. Please check permissions.';
    }
} else {
    $_SESSION['upload_message'] = 'No file uploaded.';
}

// Redirect back to the dashboard with the message
header('location: dashboard.php');
exit();
?>

<?php
include 'dbconfig.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('location:authen.php');
}

$username = $_SESSION['username'];

// Display success message if set
if (isset($_SESSION['upload_message'])) {
    $upload_message = $_SESSION['upload_message'];
    unset($_SESSION['upload_message']); // Clear message after displaying
}

// Fetch user info
$sql = "SELECT * FROM users WHERE username='$username';";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);

$name = $info['name'];
$email = $info['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIXTREAM - <?php echo htmlspecialchars($name); ?></title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="library-files/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/me.css">
    <link rel="icon" type="image/x-icon" href="assets/img/LOGO_tab.svg" />
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="assets/img/LOGO.svg" width="30" height="30" class="d-inline-block align-top" alt="" id="dash-icon">
            <b>PIXTREAM</b>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#"><b>Home</b></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="people.php"><b>People</b></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="chat.php"><b>Chat</b></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <b><?php echo htmlspecialchars($name); ?></b>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Navbar -->

    <div class="container mt-4">
        <!-- Show success message if available -->
        <?php if (isset($upload_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($upload_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="text-right">
            <button type="button" class="btn mybtn" data-bs-toggle="modal" data-bs-target="#newPost">
                New Post
            </button>
        </div>
    </div>

    <!-- New Post Modal -->
    <div class="modal fade" id="newPost" tabindex="-1" aria-labelledby="newPostLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newPostLabel">New Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="post_upload.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="mediaInput" class="custom-file-upload">
                                <i class="fa-solid fa-upload"></i> Choose file
                            </label>
                            <input type="file" class="form-control-file" id="mediaInput" name="media" accept="image/*,video/*" style="display: none;" required>
                        </div>
                        <div class="mt-3">
                            <div id="preview" style="display: none;"></div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="caption">Caption</label>
                            <textarea class="form-control" id="caption" name="caption" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="" hidden selected>Select a category</option>
                                <option value="photos">Photos</option>
                                <option value="videos">Videos</option>
                                <option value="music">Music</option>
                                <option value="articles">Articles</option>
                                <option value="memes">Memes</option>
                                <option value="art-design">Art & Design</option>
                                <option value="photography">Photography</option>
                                <option value="travel-adventure">Travel & Adventure</option>
                                <option value="food-cooking">Food & Cooking</option>
                                <option value="others">Others</option>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn mybtn">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('mediaInput').addEventListener('change', function(event) {
            var file = event.target.files[0];
            var reader = new FileReader();
            var preview = document.getElementById('preview');

            if (file) {
                var fileType = file.type;
                preview.style.display = 'none';
                preview.innerHTML = '';

                if (fileType.startsWith('image/')) {
                    reader.onload = function(e) {
                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '100%';
                        img.style.border = '1px solid #ddd';
                        img.style.borderRadius = '4px';
                        preview.appendChild(img);
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else if (fileType.startsWith('video/')) {
                    var video = document.createElement('video');
                    video.src = URL.createObjectURL(file);
                    video.controls = true;
                    video.style.maxWidth = '100%';
                    video.style.border = '1px solid #ddd';
                    video.style.borderRadius = '4px';
                    video.addEventListener('loadeddata', function() {
                        URL.revokeObjectURL(video.src);
                    });
                    preview.appendChild(video);
                    preview.style.display = 'block';
                } else {
                    alert('Please upload an image or video file.');
                    event.target.value = '';
                }
            } else {
                preview.style.display = 'none';
            }
        });
    </script>

    <!-- JS files -->
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include 'dbconfig.php';
session_start();
$username = $_SESSION['username'];

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
    <title>PIXTREAM - <?php echo $name; ?></title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="additional-files/extra.css">
    <link rel="stylesheet" href="additional-files/me.css">
    <link rel="icon" type="image/x-icon" href="assets/LOGO_tab.svg" />
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">        
        <a class="navbar-brand" href="#">
            <img src="assets/LOGO.svg" width="30" height="30" class="d-inline-block align-top" alt="" id="dash-icon">
            <b>PIXTREAM</b>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#"><b>Home</b><span class="sr-only"></span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="people.php"><b>People</b><span class="sr-only"></span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <b><?php echo $name; ?></b>
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
        <div class="text-right">
            <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#newPost">
                New Post
            </button>
        </div>
    </div>


    <div class="modal fade" id="newPost" tabindex="-1" aria-labelledby="newPostLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newPostLabel">New Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="fileInput" class="custom-file-upload">
                                <i class="bi bi-upload"></i> Choose file
                            </label>
                            <input type="file" class="form-control-file" id="fileInput" style="display: none;">
                        </div>
                        <div class="mt-3">
                            <img id="preview" src="#" alt="Image preview" style="display: none; max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                        <div class="form-group mt-3">
                            <label for="caption">Caption</label>
                            <textarea class="form-control" id="caption" name="caption" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-custom">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('fileInput').addEventListener('change', function(event) {
            var file = event.target.files[0];
            var reader = new FileReader();

            reader.onload = function(e) {
                var img = document.getElementById('preview');
                img.src = e.target.result;
                img.style.display = 'block'; // Show the image
            };

            if (file) {
                reader.readAsDataURL(file); // Read the file as a data URL
            } else {
                var img = document.getElementById('preview');
                img.style.display = 'none'; // Hide the image if no file is selected
            }
        });
    </script>
    

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-custom-width mt-5">
                    <div class="card-body">
                        <h5 class="card-title">News Feed</h5>
                        <div class="row">
                            <?php
                            $sql = "SELECT * FROM photos WHERE username='".$_SESSION['username']."'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<div class="col-md-4 mb-3">';
                                    echo '<img src="uploads/'.htmlspecialchars($row['photo_path']).'" class="img-fluid" alt="User Photo">';
                                    echo '</div>';
                                }
                            } else {
                                echo "You are all caught up!";
                            }
                            $conn->close();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Upload Photo</h5>
                        <form action="upload.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="photo">Choose Photo</label>
                                <input type="file" class="form-control-file" id="photo" name="photo">
                            </div>
                            <div class="form-group mt-3">
                                <label for="caption">Caption</label>
                                <textarea class="form-control" id="caption" name="caption" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Upload</button>
                        </form>
                    </div>
                </div>                
            </div>
        </div>
    </div>

    <!-- JS files -->
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="additional-files/extra.css">
    <link rel="stylesheet" href="additional-files/me.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">s-pixtream</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="#">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Profile</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Upload</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Profile Information</h5>
                    <p class="card-text">Name: <?php echo $_SESSION['name']; ?></p>
                    <p class="card-text">Username: <?php echo $_SESSION['username']; ?></p>
                    <p class="card-text">Email: <?php echo $_SESSION['email']; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Upload Photo</h5>
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="photo">Choose Photo</label>
                            <input type="file" class="form-control-file" id="photo" name="photo">
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Your Photos</h5>
                    <div class="row">
                        <?php
                        // Fetch user photos from database
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        $sql = "SELECT * FROM photos WHERE username='".$_SESSION['username']."'";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo '<div class="col-md-4">';
                                echo '<img src="uploads/'.$row['photo'].'" class="img-fluid">';
                                echo '</div>';
                            }
                        } else {
                            echo "No photos found.";
                        }
                        $conn->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

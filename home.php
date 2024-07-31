<?php
session_start();
#error_reporting(0);

include('config.php');

if(!isset($_SESSION['username']))
{
  header('location: index.php');
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = '$username' ";
$result = mysqli_query($data, $sql);
$info = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>s-pixtream - Home</title>
</head>
<body>
    <h1>Welcome <?php echo $username; ?></h1>

    Profile <br>
    
    <?php
    echo "username: " . $username . "<br>";
    echo "email: " . $info['email'];
    ?>

    <a href="logout.php">
        <button>Logout</button>
    </a>

</body>
</html>
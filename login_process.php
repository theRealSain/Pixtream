<?php
include 'config.php';
#error_reporting(0);
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username' && password = '$password'";
$result = mysqli_query($conn, $sql);
$num = mysqli_num_rows($result);

if($num == 1)
{
  $_SESSION['username'] = $username;
  header('location:home.php');
}
else
{
  $_SESSION['login_error'] = 'Incorrect Username or Password!';
  header("location:index.php");
}

?>

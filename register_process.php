<?php
include 'config.php';
error_reporting(0);

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

$s = "SELECT * FROM users WHERE username = '$username'";

$result = mysqli_query($conn, $s);

$num =  mysqli_num_rows($result);

if($num==1)
{
  $_SESSION['username_error'] = 'Username already exists!';
  header("location: index.php");
}
else
{
  $reg = "INSERT INTO users (username, email, password) VALUES('$username','$email','$password');";
  mysqli_query($conn, $reg);
  $_SESSION['reg_true'] = 'Registration was successful! <br> Please login again.';
  header('location: index.php');
}
?>
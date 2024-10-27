<?php
// Database connection
include 'dbconfig.php';
session_start();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Fetch user
    $sql = "SELECT * FROM admin WHERE username='$username' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if ($password == $user['password']) { // Simple string comparison
            // Start session and set session variables
            $_SESSION['id'] = $user['id'];
            // Redirect to dashboard or home page
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password!";
            header("Location: admin.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid username or password!";
        header("Location: admin.php");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title>PIXTREAM</title>
   <link rel="stylesheet" href="library-files/css/all.min.css.css">
   <link rel="stylesheet" href="library-files/css/fontawesome.min.css">
   <link rel="stylesheet" href="library-files/css/bootstrap.min.css">
   <link rel="stylesheet" href="assets/css/me.css">
   <link rel="icon" type="image/x-icon" href="assets/img/LOGO_tab.svg" />
</head>
<body>

    <div class="container">
        <div class="row">
    		<div class="col-md-5 mx-auto">

    		<div id="login">
    			<div class="myform form ">
    				 <div class="logo mb-3">
    					 <div class="col-md-12 text-center">
    						<h1 class="auth-head">Administrator Login</h1>
    					 </div>
    				</div>
                   <form action="" method="post">

                        <?php
                           if(isset($_SESSION['error'])): ?>
                              <div class="alert alert-danger">
                                 <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                              </div>
                           <?php endif;
                        ?>

                        <div class="form-group">
                           <label for="exampleInputUsername">Username</label>
                           <input type="text" name="username"  class="form-control" id="exampleInputUsername" placeholder="Enter username">
                        </div>
                        <div class="form-group">
                           <label for="exampleInputPassword">Password</label>
                           <input type="password" name="password" id="exampleInputPassword"  class="form-control" placeholder="Enter Password">
                        </div>                        
                        <div class="col-md-12 text-center ">
                           <button type="submit" class="btn btn-block mybtn btn-primary tx-tfm">Login</button>
                        </div>                
                        <div class="col-md-12 mb-3">
                           <p class="text-center"></p>
                        </div>
                    </form>             
    			</div>
    		</div>
    	</div>
    </div>

</body>
</html>
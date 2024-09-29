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
    						<h1 class="auth-head">Login to Pixtream</h1>
    					 </div>
    				</div>
                   <form action="login_process.php" method="post">

                        <?php
                           session_start();
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
                        <div class="form-group">
                         <p class="form-toggle text-center">Don't have an account?</p>
                         <p class="form-toggle text-center" onclick="toggleForms()"><u><a href="#">Sign Up</a></u></p>
                        </div>
                    </form>             
    			</div>
    		</div>

    		<div id="register" style="display: none;">
    		    <div class="myform form">
                    <div class="logo mb-3">
                       <div class="col-md-12 text-center">
                          <h1 class="auth-head">Register to Pixtream</h1>
                       </div>
                    </div>
                    <form action="register_process.php" method="post">

                        <?php
                           if(isset($_SESSION['error'])): ?>
                              <div class="alert alert-danger">
                                 <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                              </div>
                           <?php endif;
                        ?>

                       <div class="form-group">
                          <label for="exampleInputName">Name</label>
                          <input type="text"  name="name" class="form-control" id="exampleInputName" placeholder="Enter name">
                       </div>
                       <div class="form-group">
                          <label for="exampleInputUsername">Username</label>
                          <input type="text" name="username" class="form-control" id="exampleInputUsername" placeholder="Enter username">
                       </div>
                       <div class="form-group">
                          <label for="exampleInputEmail1">Email address</label>
                          <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                       </div>
                       <div class="form-group">
                          <label for="exampleInputPassword">Password</label>
                          <input type="password" name="password" id="exampleInputPassword"  class="form-control" placeholder="Enter Password">
                       </div>
                       <div class="col-md-12 text-center mb-3">
                          <button type="submit" class="btn btn-block mybtn btn-primary tx-tfm">Sign Up</button>
                       </div>
                       <div class="col-md-12 ">
                          <div class="form-group">
                             <p class="form-toggle text-center">Already have an account?</p>
                             <p class="form-toggle text-center" onclick="toggleForms()"><u><a href="#">Sign In</a></u></p>
                          </div>
                       </div>
                        </div>
                    </form>
                </div>
    		</div>
    	</div>
    </div>

    <script src="assets/js/auth.js"></script>

</body>
</html>
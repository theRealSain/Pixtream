<?php
#error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>s-pixtream</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 400px;
            margin-top: 50px;
        }
        .form-toggle {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php
if($_SESSION['username_error'])
{
  echo $_SESSION['username_error'];
}
unset($_SESSION['username_error']);

if($_SESSION['reg_true'])
{
  echo $_SESSION['reg_true'];
}
unset($_SESSION['reg_true']);

if($_SESSION['login_eror'])
{
  echo $_SESSION['login_eror'];
}
unset($_SESSION['login_eror']);
?>

    <div class="container">
        <h2 class="text-center">s-pixtream</h2>
        <div id="login-form">
            <h4>Login</h4>
            <form action="login_process.php" method="post">
                <div class="form-group">
                    <label for="login-username">Username:</label>
                    <input type="text" class="form-control" id="login-username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="login-password">Password:</label>
                    <input type="password" class="form-control" id="login-password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Log in</button>
            </form>
            <p class="text-center mt-3">Don't have an account? <span class="form-toggle" onclick="toggleForms()">Sign up</span></p>
        </div>
        <div id="register-form" style="display: none;">
            <h4>Register</h4>
            <form action="register_process.php" method="post">
                <div class="form-group">
                    <label for="register-username">Username:</label>
                    <input type="text" class="form-control" id="register-username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="register-email">Email:</label>
                    <input type="email" class="form-control" id="register-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="register-password">Password:</label>
                    <input type="password" class="form-control" id="register-password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Sign up</button>
            </form>
            <p class="text-center mt-3">Already have an account? <span class="form-toggle" onclick="toggleForms()">Log in</span></p>
        </div>
    </div>

    

    <script>
        function toggleForms() {
            var loginForm = document.getElementById('login-form');
            var registerForm = document.getElementById('register-form');
            if (loginForm.style.display === 'none') {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
            } else {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
            }
        }
    </script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

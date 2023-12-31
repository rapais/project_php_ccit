<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Register</title>
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  
  <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>


  <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container">
      <div class="card login-card">
        <div class="row no-gutters">
          <div class="col-md-5">
            <img src="assets/images/login.jpg" alt="login" class="login-card-img">
          </div>
          <div class="col-md-7">
            <div class="card-body">
              <div class="brand-wrapper">
                <img src="assets/images/logo.png" alt="logo" class="logo">
              </div>
              <p class="login-card-description">Register your account</p>
              <form method="post">
                  <div class="form-group">
                    <label for="name">Username</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Username">
                  </div>
                  <div class="form-group">
                  <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email address">
                  </div>
                  <div class="form-group mb-4">
                  <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="password">
                  </div>
                  <div class="form-group">
                  <label for="user_type">Select User Type</label>
                    <select name="user_type" class="form-control">
                        <option value="user">user</option>
                        <option value="admin">admin</option>
                    </select>   
                  </div>       
                  <button name="register" class="btn btn-block login-btn mb-4" type="submit">Register</button>
                </form>
                <!-- <a href="#!" class="forgot-password-link">Forgot password?</a> -->
                <p class="login-card-footer-text">Already have an account? <a href="login.php" class="text-reset">Log in here</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>

<?php

include 'config.php';

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $user_type = $_POST['user_type'];

    if (empty($name) || empty($email) || empty($password) || empty($user_type)) {
        echo '
        ?>
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Please fill in all required fields!",
            }).then(() => {
                window.location.href = "register.php";
            });
        </script>
        <?php
        ';
        exit; 
    }

    $check_existing_query = mysqli_query($conn, "SELECT * FROM `users` WHERE `email`='$email'");
    if (mysqli_num_rows($check_existing_query) > 0) {
        echo '
        ?>
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Account already registered!",
            }).then(() => {
                window.location.href = "register.php";
            });
        </script>
        <?php
        ';
        exit; 
    }

    $insert_user_query = mysqli_query($conn, "INSERT INTO `users` (`name`, `email`, `password`, `user_type`) 
        VALUES ('$name', '$email', '$password', '$user_type')") or die('query failed');

    header('location: login.php');
}

?>

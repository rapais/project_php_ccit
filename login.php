<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Sign in</title>
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
              <p class="login-card-description">Sign into your account</p>
              <form method="post">
                  <div class="form-group">
                  <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email address">
                  </div>
                  <div class="form-group mb-4">
                  <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="password">
                  </div>                 
                  <button name="signin" class="btn btn-block login-btn mb-4" type="submit">Sign in</button>
                </form>
                <!-- <a href="#!" class="forgot-password-link">Forgot password?</a> -->
                <p class="login-card-footer-text">Don't have an account? <a href="register.php" class="text-reset">Register here</a></p>
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
session_start();


if (isset($_POST['signin'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($email) || empty($password)) {
        echo '
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Please fill in both email and password!",
            }).then(() => {
                window.location.href = "login.php";
            });
        </script>
        ';
        exit; 
    }

    $check_user_query = mysqli_query($conn, "SELECT * FROM `users` WHERE `email`='$email' AND `password`='$password'");
    if (mysqli_num_rows($check_user_query) > 0) {

      $user_row = mysqli_fetch_assoc($check_user_query);

      if($user_row['user_type'] == 'admin'){

        $_SESSION['admin_name'] = $user_row['name'];
        $_SESSION['admin_email'] = $user_row['email'];
        $_SESSION['admin_id'] = $user_row['user_id'];
        header('location: admin_page.php');

      } else if($user_row['user_type'] == 'user'){

        $_SESSION['user_name'] = $user_row['name'];
        $_SESSION['user_email'] = $user_row['email'];
        $_SESSION['user_id'] = $user_row['user_id'];
        header('location: home_page.php');

      }
        
    } else {
      echo '
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Incorrect email or password!",
            }).then(() => {
                window.location.href = "login.php";
            });
        </script>
        ';
        exit; 
    }

}

?>


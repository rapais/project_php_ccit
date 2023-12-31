<?php

include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location: login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin page</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">  
  
  <link rel="stylesheet" href="assets\css\admin_style.css">
</head>
<body>

<?php include 'admin_header.php';?>

<section class="dashboard">

  <h1 class="title">dashboard</h1>

  <div class="box-container">
    <div class="box">
      <?php
          $total_pendings = 0;
          $select_pending = mysqli_query($conn, "SELECT total_price FROM 
          `orders` WHERE payment = 'pending'") or die('query failed');
          if(mysqli_num_rows($select_pending) > 0) {
            while($fetch_pendings = mysqli_fetch_assoc($select_pending)){
              $total_price = $fetch_pendings['total_price'];
              $total_pendings += $total_price;
            };
          };
        ?>
        <h3>Rp <?php echo number_format($total_pendings, 0, ',', '.'); ?></h3>
        <p>total pendings</p>

    </div>

    <div class="box">
      <?php
          $total_completed = 0;
          $select_completed = mysqli_query($conn, "SELECT total_price FROM 
          `orders` WHERE payment = 'completed'") or die('query failed');
          if(mysqli_num_rows($select_completed) > 0) {
            while($fetch_completed = mysqli_fetch_assoc($select_completed)){
              $total_price = $fetch_completed['total_price'];
              $total_completed += $total_price;
            };
          };
        ?>
        <h3>Rp <?php echo number_format($total_completed, 0, ',', '.'); ?></h3>
        <p>total completed</p>

    </div>

  <div class="box">
    <?php

          $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
          $number_of_orders = mysqli_num_rows($select_orders);
    ?>
    <h3><?php echo $number_of_orders;?></h3>
    <p>order placed</p>
  </div>

  <div class="box">
    <?php

          $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
          $number_of_products = mysqli_num_rows($select_products);
    ?>
    <h3><?php echo $number_of_products;?></h3>
    <p>products</p>
  </div>

  <div class="box">
    <?php

          $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
          $number_of_users = mysqli_num_rows($select_users);
    ?>
    <h3><?php echo $number_of_users;?></h3>
    <p>user</p>
  </div>

  <div class="box">
    <?php

          $select_admin = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');
          $number_of_admin = mysqli_num_rows($select_admin);
    ?>
    <h3><?php echo $number_of_admin;?></h3>
    <p>admin</p>
  </div>

  <div class="box">
    <?php

          $select_account = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
          $number_of_account = mysqli_num_rows($select_account);
    ?>
    <h3><?php echo $number_of_account;?></h3>
    <p>total account</p>
  </div>

  <div class="box">
    <?php

          $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
          $number_of_message = mysqli_num_rows($select_message);
    ?>
    <h3><?php echo $number_of_message;?></h3>
    <p>new message</p>
  </div>

  </div>

</section>

<script src="assets\js\admin_script.js"></script>

</body>
</html>
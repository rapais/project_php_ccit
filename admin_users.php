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
  <title>users</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">  
  
  <link rel="stylesheet" href="assets\css\admin_style.css">
</head>
<body>

<?php include 'admin_header.php';?>

<section class="users">

  <h1 class="title"> user accounts </h1>

  <div class="box-container">

    <?php

      $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
      while($fetch_users = mysqli_fetch_assoc($select_users)){

    ?>

    <div class="box">

      <p> username : <span><?php echo $fetch_users['name'];?></span></p>
      <p> email : <span><?php echo $fetch_users['email'];?></span></p>
      <p> user type : <span style="color:<?php if($fetch_users['user_type'] == 'admin')
      {echo 'var(--orange)';}?>"><?php echo $fetch_users['user_type'];?></span></p>
      <a href="admin_users.php?delete=<?php echo $fetch_users['user_id'];?>" onclick="return confirm('delete this user?');"
      class="delete-btn">delete</a>
    </div>

    <?php
        };
      

    ?>

  </div>

</section>

<script src="assets\js\admin_script.js"></script>

</body>
</html>

<?php

if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];

  mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$delete_id'") or die('cart query failed');

  mysqli_query($conn, "DELETE FROM `users` WHERE user_id = '$delete_id'") or die('user query failed');

  header('Location: admin_users.php');
  exit();
}

?>
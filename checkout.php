<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
    header('location: login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>cart</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">  
  
  <link rel="stylesheet" href="assets\css\style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>checkout</h3>
   <p> <a href="home.php">home</a> / checkout </p>
</div>

<section class="display-order">

<?php
    $grand_total = 0;
    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($select_cart) > 0) {
        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
?>
        <p> <?php echo $fetch_cart['name']; ?> <span>(<?php echo 'Rp ' . number_format($fetch_cart['price'], 0, ',', '.') . '/-' . ' x ' . $fetch_cart['quantity']; ?>)</span> </p>
<?php
        }
    } else {
        echo '<p class="empty">your cart is empty</p>';
    }
?>
<div class="grand-total"> grand total : <span>Rp <?php echo number_format($grand_total, 0, ',', '.'); ?>,-</span> </div>

</section>

<section class="checkout">

   <form action="" method="post">
      <h3>place your order</h3>
      <div class="flex">
         <div class="inputBox">
            <span>your name :</span>
            <input type="text" name="name" required placeholder="enter your name">
         </div>
         <div class="inputBox">
            <span>your number :</span>
            <input type="number" name="number" required placeholder="enter your number">
         </div>
         <div class="inputBox">
            <span>your email :</span>
            <input type="email" name="email" required placeholder="enter your email">
         </div>
         <div class="inputBox">
            <span>payment method :</span>
            <select name="method">
               <option value="cash on delivery">cash on delivery</option>
               <option value="credit card">credit card</option>
               <option value="paypal">paypal</option>
               <option value="paytm">atm</option>
            </select>
         </div>
         <div class="inputBox">
            <span>address line :</span>
            <input type="text" name="street" required placeholder="e.g. street name">
         </div>
         <div class="inputBox">
            <span>city :</span>
            <input type="text" name="city" required placeholder="e.g. mumbai">
         </div>
         <div class="inputBox">
            <span>country :</span>
            <input type="text" name="country" required placeholder="e.g. india">
         </div>
         <div class="inputBox">
            <span>pin code :</span>
            <input type="number" min="0" name="pin_code" required placeholder="e.g. 123456">
         </div>
      </div>
      <input type="submit" value="order now" class="btn" name="order_btn">
   </form>

</section>

<?php include 'footer.php';?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/script.js"></script>

</body>
</html>

<?php

if(isset($_POST['order_btn'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = $_POST['number'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, $_POST['street'].', '. $_POST['city'].', '. $_POST['country'].' - '. $_POST['pin_code']);
    $placed_on = date('d-M-Y');
 
    $cart_total = 0;
    $cart_products[] = '';
 
    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if(mysqli_num_rows($cart_query) > 0){
       while($cart_item = mysqli_fetch_assoc($cart_query)){
          $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
          $sub_total = ($cart_item['price'] * $cart_item['quantity']);
          $cart_total += $sub_total;
       }
    }
 
    $total_products = implode(', ',$cart_products);
 
    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' AND name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');
 
    if($cart_total == 0){
       echo '
        <script>
            Swal.fire({
                icon: "warning",
                title: "Oops",
                text: "Your cart is empty!"
            }). then(function() {
                window.location.href = "checkout.php"; 
            });
        </script> 
        '; 
    }else{
       if(mysqli_num_rows($order_query) > 0){
          echo '
        <script>
            Swal.fire({
                icon: "warning",
                title: "Oops",
                text: "Order already placed!"
            }). then(function() {
                window.location.href = "checkout.php"; 
            });
        </script> 
        ';  
       }else{
          mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
          echo '
        <script>
            Swal.fire({
                icon: "success",
                title: "Success",
                text: "Order placed successfully!"
            }). then(function() {
                window.location.href = "checkout.php"; 
            });
        </script> 
        '; 
          mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
       }
    }
    
 }

?>
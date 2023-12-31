<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
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
    <h3>shopping cart</h3>
    <p> <a href="home.php">home</a> / cart </p>
</div>

<section class="shopping-cart">

    <h1 class="title">products added</h1>

    <div class="box-container">
        <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        if (mysqli_num_rows($select_cart) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                $sub_total = $fetch_cart['quantity'] * $fetch_cart['price'];
                ?>
                <div class="box">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" name="delete_id" value="<?php echo $fetch_cart['cart_id']; ?>">
                        <button type="submit" name="delete" onclick="return confirm('Delete this from cart?');">
                            <span class="fas fa-times"></span>
                        </button>
                    </form>
                    <img src="assets/uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
                    <div class="name"><?php echo $fetch_cart['name']; ?></div>
                    <div class="price">Rp <?php echo number_format($fetch_cart['price'], 0, ',', '.'); ?></div>
                    <form action="" method="post">
                        <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['cart_id']; ?>">
                        <input type="number" min="1" name="cart_quantity"
                               value="<?php echo $fetch_cart['quantity']; ?>">
                        <input type="submit" name="update_cart" value="update" class="option-btn">
                    </form>
                    <div class="sub-total"> sub total : <span>Rp <?php echo number_format($sub_total, 0, ',', '.'); ?>,-</span>
                    </div>
                </div>
                <?php
                $grand_total += $sub_total;
            }
        } else {
            echo '<p class="empty">your cart is empty</p>';
        }
        ?>
    </div>

    <div style="margin-top: 2rem; text-align:center;">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"
              onsubmit="return confirm('Delete all from cart?');">
            <button type="submit" name="delete_all"
                    class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">delete all
            </button>
        </form>
    </div>

    <div class="cart-total">
        <p>grand total : <span>Rp <?php echo number_format($grand_total, 0, ',', '.'); ?>,-</span></p>
        <div class="flex">
            <a href="shop.php" class="option-btn">continue shopping</a>
            <a href="checkout.php"
               class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">proceed to checkout</a>
        </div>
    </div>

</section>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/script.js"></script>

</body>
</html>

<?php

if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];
    mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE cart_id = '$cart_id'") or die('query failed');
    echo '
        <script>
            Swal.fire({
                icon: "success",
                title: "Success",
                text: "Added to the cart!"
            }). then(function() {
                window.location.href = "cart.php"; 
            });
        </script> 
        ';
}

if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE cart_id = '$delete_id'") or die('query failed');
    echo '
        <script>
            Swal.fire({
                icon: "success",
                title: "Success",
                text: "Product deleted from cart!"
            }).then(function() {
                window.location.href = "cart.php"; 
            });
        </script> 
        ';
}

if (isset($_POST['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    echo '
        <script>
            Swal.fire({
                icon: "success",
                title: "Success",
                text: "All products deleted from cart!"
            }).then(function() {
                window.location.href = "cart.php"; 
            });
        </script> 
        ';
}

?>

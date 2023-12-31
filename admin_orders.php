<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin_style.css">
</head>
<body>

<?php include 'admin_header.php';?>

<section class="orders">
    <h1 class="title">placed orders</h1>
    <div class="box-container">
        <?php
        $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
        if (mysqli_num_rows($select_orders) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
                ?>
                <div class="box">
                <p> user id : <span><?php echo $fetch_orders['user_id']?></span></p>
                    <p> placed on : <span><?php echo $fetch_orders['placed_on']?></span></p>
                    <p> name : <span><?php echo $fetch_orders['name']?></span></p>
                    <p> number : <span><?php echo $fetch_orders['number']?></span></p>
                    <p> email : <span><?php echo $fetch_orders['email']?></span></p>
                    <p> address : <span><?php echo $fetch_orders['address']?></span></p>
                    <p> total products : <span><?php echo $fetch_orders['total_products']?></span></p>
                    <p> total_price : <span>Rp <?php echo number_format($fetch_orders['total_price'], 0, ',', '.'); ?></span></p>
                    <p> placed_on : <span><?php echo $fetch_orders['placed_on']?></span></p>
                    <p> payment method : <span><?php echo $fetch_orders['method']?></span></p>

                    <form action="admin_orders.php" method="post">
                        <input type="hidden" name="order_id" value="<?php echo $fetch_orders['orders_id'];?>">
                        <select name="update_payment">
                            <option value="" selected disabled><?php echo $fetch_orders['payment'];?></option>
                            <option value="pending">pending</option>
                            <option value="completed">completed</option>
                        </select>
                        <input type="submit" value="Update" name="update_order" class="option-btn">
                        <a href="admin_orders.php?delete=<?php echo $fetch_orders['orders_id'];?>" onclick="return confirm('Are you sure you want to delete this order? This action cannot be undone.');" class="delete-btn">Delete</a>
                    </form>
                    
                </div>
                <?php
            }
        } else {
            echo '<p class="empty">No orders placed yet!</p>';
        }
        ?>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/admin_script.js"></script>

</body>
</html>

<?php
if (isset($_POST['update_order'])) {
    $order_update_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];
    mysqli_query($conn, "UPDATE `orders` SET payment = '$update_payment' WHERE orders_id = '$order_update_id'") or die('query failed');
    echo '
    <script>
        Swal.fire({
            icon: "success",
            title: "Success",
            text: "Payment status has been updated!"
        }).then(function() {
            window.location.replace("admin_orders.php");
        });
    </script>
    ';
    exit();
}


if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `orders` WHERE orders_id = '$delete_id'") or die('query failed');
    echo '
    <script>
        Swal.fire({
            icon: "success",
            title: "Success",
            text: "Item Deleted!"
        }).then(function() {
            window.location.replace("admin_orders.php");
        });
    </script>
    ';
    exit();
}
?>

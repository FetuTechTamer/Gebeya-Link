<?php
include 'components/connect.php';
session_start();

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';
$get_id = isset($_GET['get_id']) ? $_GET['get_id'] : header('location:order.php') && exit();

$success_msg = [];
$warning_msg = [];

if (isset($_POST['cancel'])) { 
    $status = 'canceled';
    $update_order = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?"); 
    $update_order->bind_param("si", $status, $get_id);

    if ($update_order->execute()) {
        $success_msg[] = "Order has been successfully canceled.";
    } else {
        $warning_msg[] = "Failed to cancel the order. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Detail Page</title>
    <link rel="icon" href="image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="banner"> 
    <div class="detail" style="padding: 400px;"> 
        <h1>Order Detail</h1> 
        <p>Gebeya Link is dedicated to providing high-quality agricultural products, focusing on sustainable practices and supporting local farmers.</p> 
        <span><a href="home.php">Home</a> <i class="fa-solid fa-arrow-right"></i> Order Detail Now</span> 
    </div> 
</div>

<div class="order-detail"> 
    <div class="heading"> 
        <h1>My Order Details</h1> 
        <p>View the details of your product orders below.</p> 
        <img src="image/separator.webp"> 
    </div>
    
    <?php 
    if (!empty($success_msg)) {
        echo '<div class="success">' . implode('<br>', $success_msg) . '</div>';
    }
    if (!empty($warning_msg)) {
        echo '<div class="warning">' . implode('<br>', $warning_msg) . '</div>';
    }
    ?>

    <div class="box-container"> 
    <?php 
    $grand_total = 0; 
    $select_order = $conn->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1"); 
    $select_order->bind_param("i", $get_id); 
    $select_order->execute(); 
    $order_result = $select_order->get_result(); 

    if ($order_result->num_rows > 0) { 
        while ($fetch_order = $order_result->fetch_assoc()) { 
            $select_product = $conn->prepare("SELECT * FROM product WHERE id = ? LIMIT 1"); 
            $select_product->bind_param("i", $fetch_order['product_id']); 
            $select_product->execute(); 
            $product_result = $select_product->get_result(); 

            if ($product_result->num_rows > 0) { 
                while ($fetch_product = $product_result->fetch_assoc()) { 
                    $sub_total = $fetch_order['price'] * $fetch_order['quantity'];
                    $grand_total += $sub_total;
    ?>
<div class="box"> 
    <div class="col"> 
        <p class="title">
            <i class="fas fa-calendar-alt"></i> <?= htmlspecialchars($fetch_order['date']); ?>
        </p> 

        <img src="uploaded_files/<?= htmlspecialchars($fetch_product['image']); ?>" class="image"> 
        <p class="price">$<?= number_format($fetch_product['price'], 2); ?>/-</p> 
        <h3 class="name"><?= htmlspecialchars($fetch_product['name']); ?></h3> 
        <p class="grand-total">
            Total amount payable <span><?= number_format($grand_total, 2); ?>/-</span>
        </p> 
    </div> 

    <div class="col"> 
        <p class="title">Billing Address</p> 
        <p class="user"><i class="fas fa-user"></i> <?= htmlspecialchars($fetch_order['name']); ?></p> 
        <p class="user"><i class="fas fa-phone"></i> <?= htmlspecialchars($fetch_order['number']); ?></p> 
        <p class="user"><i class="fas fa-envelope"></i> <?= htmlspecialchars($fetch_order['email']); ?></p> 
        <p class="user"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($fetch_order['address']); ?></p> 

        <p class="status" style="color: <?= ($fetch_order['status'] == 'delivered') ? 'green' : (($fetch_order['status'] == 'canceled') ? 'red' : 'orange'); ?>">
            <?= htmlspecialchars($fetch_order['status']); ?>
        </p> 

        <?php if ($fetch_order['status'] == 'canceled') { ?> 
            <a href="checkout.php?get_id=<?= $fetch_product['id']; ?>" class="btn" style="line-height:2;">Order Again</a> 
        <?php } else { ?>
            <form action="" method="POST">
                <button type="submit" name="cancel" class="btn" onclick="return confirm('Do you want to cancel this product?');">Cancel</button>
            </form>
        <?php } ?>
    </div> 
</div>

<?php
                } 
            } 
        } 
    } else {
        echo '<p class="empty">No orders have taken place yet.</p>'; 
    }
    ?> 
    </div> 
</div>

<?php include 'components/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js"></script>
<?php include 'components/alert.php'; ?>
</body>
</html>
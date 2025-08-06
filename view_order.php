<?php
include 'components/connect.php';
session_start();

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';
$get_id = isset($_GET['get_id']) ? $_GET['get_id'] : header('location:order.php') && exit();

$success_msg = [];
$warning_msg = [];

// Handle order cancellation
if (isset($_POST['cancel'])) { 
    $select_order = $conn->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1"); 
    $select_order->bind_param("s", $get_id);
    $select_order->execute();
    $order_result = $select_order->get_result();

    if ($order_result->num_rows > 0) {
        $fetch_order = $order_result->fetch_assoc();
        $quantity = $fetch_order['quantity'];
        $product_id = $fetch_order['product_id'];

        $status = 'canceled';
        $update_order = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?"); 
        $update_order->bind_param("ss", $status, $get_id);

        if ($update_order->execute()) {
            $update_stock = $conn->prepare("UPDATE product SET stock = stock + ? WHERE id = ?");
            $update_stock->bind_param("is", $quantity, $product_id);
            $update_stock->execute();

            $success_msg[] = "Order has been successfully canceled and stock updated.";
        } else {
            $warning_msg[] = "Failed to cancel the order. Please try again.";
        }
    }
}

// Handle order deletion
if (isset($_POST['delete'])) {
    $delete_order = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $delete_order->bind_param("s", $get_id);

    if ($delete_order->execute()) {
        $_SESSION['success_msg'] = "Order has been successfully deleted.";
        header('Location: order.php'); 
        exit;
    } else {
        $_SESSION['warning_msg'] = "Failed to delete the order. Please try again.";
    }
}

// Handle order reordering
if (isset($_POST['order_again'])) {
    $select_order = $conn->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
    $select_order->bind_param("s", $get_id);
    $select_order->execute();
    $order_result = $select_order->get_result();

    if ($order_result->num_rows > 0) {
        $fetch_order = $order_result->fetch_assoc();

        if ($fetch_order['status'] == 'canceled') {
            $new_status = 'in progress';
            $update_order = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $update_order->bind_param("ss", $new_status, $get_id);

            $quantity = $fetch_order['quantity'];
            $product_id = $fetch_order['product_id'];
            $update_stock = $conn->prepare("UPDATE product SET stock = stock - ? WHERE id = ?");
            $update_stock->bind_param("is", $quantity, $product_id);

            if ($update_order->execute() && $update_stock->execute()) {
                $_SESSION['success_msg'] = "Order has been successfully restored and stock updated.";
                header('Location: order.php'); 
                exit;
            } else {
                $warning_msg[] = "Failed to restore the order or update stock.";
            }
        } else {
            $warning_msg[] = "Order is already active or completed.";
        }
    } else {
        $warning_msg[] = "Order not found.";
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
        <p>Gebeya Link is dedicated to providing high-quality agricultural products...</p> 
        <span><a href="home.php">Home</a> <i class="fa-solid fa-arrow-right"></i> Order Detail Now</span> 
    </div> 
</div>

<div class="order-detail"> 
    <div class="heading"> 
        <h1>My Order Details</h1> 
        <p>View the details of your product orders below.</p> 
        <img src="image/separator.webp"> 
    </div>
  
    <div class="box-container"> 
    <?php 
    $grand_total = 0; 
    $select_order = $conn->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1"); 
    $select_order->bind_param("s", $get_id); 
    $select_order->execute(); 
    $order_result = $select_order->get_result(); 

    if ($order_result->num_rows > 0) { 
        while ($fetch_order = $order_result->fetch_assoc()) { 
            $select_product = $conn->prepare("SELECT * FROM product WHERE id = ? LIMIT 1"); 
            $select_product->bind_param("s", $fetch_order['product_id']); 
            $select_product->execute(); 
            $product_result = $select_product->get_result(); 

            if ($product_result->num_rows > 0) { 
                while ($fetch_product = $product_result->fetch_assoc()) { 
                    $sub_total = $fetch_order['price'] * $fetch_order['quantity'];
                    $grand_total += $sub_total;

                    // Expiry check
                    $today = date('Y-m-d');
                    $expire_raw = $fetch_product['expire_date'];
                    $expire_date = date('Y-m-d', strtotime($expire_raw));
    ?>
    <div class="box"> 
        <div class="col"> 
            <div class="title-group">
                <p class="title" style="background-color: #fff; ">
                <span style="color: green;">
                    <i class="fas fa-calendar-alt"></i> Order Date: <?= htmlspecialchars($fetch_order['date']); ?>
                </span>
                </p>

                 <?php if ($expire_date == date('Y-m-d', strtotime('+1 day'))) { ?>
                  <p class="date ">
                    <span style="color: red;">
                     <i class="fas fa-hourglass-end"></i> Hurry, expires tomorrow!
                    </span>
                  </p>
               <?php } elseif ($expire_date > $today) { ?>
                  <p class="date ">
                    <span style="color: green;">
                     <i class="fas fa-hourglass-end"></i> Expires on <?= htmlspecialchars($expire_date); ?>
                     </span>
                  </p>
               <?php } else { ?>
                  <p class="date ">
                    <span style="color: grey;">
                     <i class="fas fa-hourglass-end"></i> This product is expired
                     </span>
                  </p>
               <?php } ?>
            </div>

            <img src="uploaded_files/<?= htmlspecialchars($fetch_product['image']); ?>" class="image"> 
            <p class="price"><?= number_format($fetch_product['price'], 2); ?> birr x <?= htmlspecialchars($fetch_order['quantity']); ?></p>

            <h3 class="name"><?= htmlspecialchars($fetch_product['name']); ?></h3> 
            <p class="grand-total">
                Total amount payable ---<span><?= number_format($grand_total, 2); ?> birr</span>
            </p> 
        </div> 

        <div class="col"> 
            <p class="title">Billing Address</p> 
            <p class="user"><i class="fas fa-user"></i> <?= htmlspecialchars($fetch_order['name']); ?></p> 
            <p class="user"><i class="fas fa-phone"></i> <?= htmlspecialchars($fetch_order['number']); ?></p> 
            <p class="user"><i class="fas fa-envelope"></i> <?= htmlspecialchars($fetch_order['email']); ?></p> 
            <p class="user"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($fetch_order['address']); ?></p> 
            <p class="status" style="color: <?= ($fetch_order['status'] == 'canceled') ? 'red' : 'green'; ?>">
                <?= htmlspecialchars($fetch_order['status']); ?>
            </p>

            <?php if ($fetch_order['status'] == 'canceled') { ?> 
                <form action="" method="POST">
                    <button type="submit" name="order_again" class="btn" onclick="return confirm('Do you want to order this item again?');">Order Again</button>
                </form>
                <form action="" method="POST">
                    <button type="submit" name="delete" class="btn" onclick="return confirm('Do you want to delete this order?');">Delete Order</button>
                </form> 
            <?php } else { ?>
                <form action="" method="POST" style="display:inline;">
                    <button type="submit" name="cancel" class="btn" onclick="return confirm('Do you want to cancel this product?');">Cancel</button>
                </form>
                <form action="" method="POST" style="display:inline;">
                    <button type="submit" name="delete" class="btn" onclick="return confirm('Do you want to delete this order?');">Delete Order</button>
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

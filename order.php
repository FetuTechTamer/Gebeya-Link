<?php
include 'components/connect.php';
session_start();

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    header('location: llogin.php');
    exit();
}

$success_msg = [];
$warning_msg = [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Order Page</title>
    <link rel="icon" href="image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="banner">
    <div class="detail" style="padding: 400px;">
        <h1>My Orders</h1>
        <p>Gebeya Link is dedicated to providing high-quality agricultural products. We focus on sustainable practices <br>and supporting local farmers to ensure fresh and nutritious offerings.<br>
        Our mission is to connect consumers with the best produce while promoting responsible farming.</p>
        <span>
            <a href="home.php">Home</a> <i class="fa-solid fa-arrow-right"></i> My Orders
        </span>
    </div>
</div>

<div class="orders">
    <div class="heading">
        <h1>My Orders</h1>
        <img src="image/separator.webp" alt="Separator">
    </div>
    <div class="box-container">
        <?php
        $select_orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY date DESC");
        $select_orders->bind_param("i", $user_id);
        $select_orders->execute();
        $orders_result = $select_orders->get_result();

        if ($orders_result->num_rows > 0) {
            while ($fetch_order = $orders_result->fetch_assoc()) {
                $product_id = $fetch_order['product_id'];
                
                $select_product = $conn->prepare("SELECT * FROM product WHERE id = ?");
                $select_product->bind_param("i", $product_id);
                $select_product->execute();
                $product_result = $select_product->get_result();
                $fetch_product = $product_result->fetch_assoc();
                
                if ($product_result->num_rows > 0) {
        ?>
                <div class="box" <?php if($fetch_order['status'] == 'canceled') echo 'style="border:2px solid red;"'; ?>>
                    <a href="view_order.php?get_id=<?= $fetch_order['id']; ?>" class="order-link">
                        <img src="uploaded_files/<?= $fetch_product['image'] ?>" class="image">
                        <div class="content">
                            <p class="date"><i class="fas fa-calendar-alt"></i> <?= $fetch_order["date"]; ?></p>
                            <div class="row">
                                <h3 class="name"><?= $fetch_product['name'] ?></h3>
                                <p class="price">Price: $<?= $fetch_product['price'] ?>/-</p>
                                <p class="status" style="color: <?php 
                                    switch($fetch_order['status']) {
                                        case 'delivered': echo 'green'; break;
                                        case 'canceled': echo 'red'; break;
                                        default: echo 'orange';
                                    }
                                ?>">
                                    <?= ucfirst($fetch_order['status']) ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
        <?php
                }
            }
        } else {
            echo '<p class="empty">No orders placed yet!</p>';
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

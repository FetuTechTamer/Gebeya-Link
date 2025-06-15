<?php
include '../components/connect.php';

if (isset($_COOKIE['seller_id'])) {
    $seller_id = $_COOKIE['seller_id'];

    $fetch_profile_query = $conn->prepare("SELECT * FROM `seller` WHERE id = ?");
    $fetch_profile_query->bind_param("s", $seller_id);
    $fetch_profile_query->execute();
    $fetch_profile = $fetch_profile_query->get_result()->fetch_assoc();
} else {
    header('location:login.php');
    exit();
}

// Fetch all orders for the seller
$select_orders = $conn->prepare("
    SELECT orders.*, product.name AS product_name 
    FROM orders 
    JOIN product ON orders.product_id = product.id 
    WHERE orders.seller_id = ?
");
$select_orders->bind_param("s", $seller_id); 
$select_orders->execute(); 
$result_orders = $select_orders->get_result(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Orders</title>
    <link rel="icon" href="../image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
       <style>
        /* Add this CSS for layout */
        .box-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between; /* Distribute space evenly */
        }
        .box {
            flex: 0 0 30%; /* Each box takes up 30% of the width */
            margin: 1%; /* Margin for spacing */
            border: 1px solid #ccc; /* Optional: border for boxes */
            padding: 10px; /* Optional: padding for boxes */
            text-align: center; /* Center align text */
        }
        @media (max-width: 768px) {
            .box {
                flex: 0 0 45%; /* Adjust for smaller screens */
            }
        }
        @media (max-width: 480px) {
            .box {
                flex: 0 0 100%; /* Stack boxes on small screens */
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="order-container">
            <div class="heading">
                <h1>Total Orders</h1>
                <img src="../image/separator.webp">
            </div>
            <div class="box-container">
                <?php 
                if ($result_orders->num_rows > 0) { 
                    while ($fetch_order = $result_orders->fetch_assoc()) { 
                ?> 
<div class="box"> 
 <div class="status" style="color: <?= ($fetch_order['status'] == 'in progress') ? 'limegreen' : (($fetch_order['status'] == 'canceled') ? 'red' : 'black'); ?>">
    <?= htmlspecialchars($fetch_order['status']); ?>
</div>
    <div class="details">
        <p>User Name: <span><?= htmlspecialchars($fetch_order['name']); ?></span></p> 
        <p>User ID: <span><?= htmlspecialchars($fetch_order['user_id']); ?></span></p> 
        <p>Product Name: <span><?= htmlspecialchars($fetch_order['product_name']); ?></span></p>
        <p>Quantity: <span><?= htmlspecialchars($fetch_order['quantity']); ?></span></p>
        <p>Placed on: <span><?= htmlspecialchars($fetch_order['date']); ?></span></p> 
        <p>User Number: <span><?= htmlspecialchars($fetch_order['number']); ?></span></p> 
        <p>User Email: <span><?= htmlspecialchars($fetch_order['email']); ?></span></p> 
        <p>Total Price: <span><?= htmlspecialchars($fetch_order['price']); ?></span></p> 
        <p>Payment Method: <span><?= htmlspecialchars($fetch_order['method']); ?></span></p> 
        <p>User Address: <span><?= htmlspecialchars($fetch_order['address']); ?></span></p> 
    </div>
</div> 
                <?php 
                    } 
                } else { 
                    echo '
                    <div class="empty">
                        <p>No orders placed yet!</p>
                    </div>
                    '; 
                }
                ?>
            </div>
        </section>
    </div>   

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../components/alert.php'; ?>
</body>
</html>
<?php
include 'components/connect.php';
session_start();

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    header('Location: login.php'); // Corrected the typo here
    exit;
}

$success_msg = [];
$warning_msg = [];

// Handle account deletion
if (isset($_POST['delete_account'])) {
    // Delete all orders associated with the user
    $delete_orders = $conn->prepare("DELETE FROM orders WHERE user_id = ?");
    $delete_orders->bind_param("s", $user_id);
    $delete_orders->execute();

    // Delete the user account
    $delete_user = $conn->prepare("DELETE FROM user WHERE id = ?");
    $delete_user->bind_param("s", $user_id);
    if ($delete_user->execute()) {
        // Clear the user cookie
        setcookie('user_id', '', time() - 3600, '/'); 
        $success_msg[] = "Account deleted successfully.";
        header('Location: login.php'); // Redirect to login after deletion
        exit();
    } else {
        $warning_msg[] = "Failed to delete account. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebeya Link - Orders</title>
    <link rel="icon" href="image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="banner"> 
   <div class="detail" style="padding:400px;"> 
        <h1>My Orders</h1> 
        <p>
            Gebeya Link is dedicated to providing high-quality agricultural products. We focus on sustainable practices
            and supporting local farmers to ensure fresh and nutritious offerings.<br>
            Our mission is to connect consumers with the best produce while promoting responsible farming.
        </p> 
        <span><a href="home.php">Home</a> <i class="fas fa-arrow-right"></i> My Orders</span> 
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
        $select_orders->bind_param("s", $user_id);
        $select_orders->execute();
        $orders_result = $select_orders->get_result();

        if ($orders_result->num_rows > 0) {
            while ($fetch_order = $orders_result->fetch_assoc()) {
                $product_id = $fetch_order['product_id'];

                $select_products = $conn->prepare("SELECT * FROM product WHERE id = ?");
                $select_products->bind_param("s", $product_id);
                $select_products->execute();
                $product_result = $select_products->get_result();

                if ($product_result->num_rows > 0) {
                    $fetch_product = $product_result->fetch_assoc();
        ?>
        <div class="box" style="<?= $fetch_order['status'] == 'canceled' ? 'border:2px solid red;' : '' ?>"> 
            <a href="view_order.php?get_id=<?= $fetch_order['id']; ?>">
                <img src="uploaded_files/<?= $fetch_product['image'] ?>" class="image" alt="Product Image"> 
                <p class="date"> <i class="fas fa-calendar-alt"></i> <?= htmlspecialchars($fetch_order["date"]) ?></p> 
                <div class="content"> 
                    <div class="row"> 
                        <h3 class="name"><?= htmlspecialchars($fetch_product['name']) ?></h3> 
                        <p class="price">Price: $<?= htmlspecialchars($fetch_product['price']) ?>/-</p> 
                        <p class="status" style="color: 
                            <?php 
                            switch ($fetch_order['status']) {
                                case 'delivered': echo 'green'; break;
                                case 'canceled': echo 'red'; break;
                                case 'in progress': echo 'orange'; break; // Status for in-progress orders
                                default: echo 'orange'; break;
                            } 
                            ?>">
                            <?= htmlspecialchars($fetch_order['status']) ?>
                        </p> 
                    </div> 
                </div> 
            </a> 
        </div>
        <?php
                }
            }
        } else {
            echo '<p class="empty">No orders have been placed yet!</p>';
        }
        ?>
    </div> 
</div>

<?php include 'components/footer.php'; ?>

<!-- SweetAlert CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js"></script>
<?php include 'components/alert.php'; ?>
</body>
</html>
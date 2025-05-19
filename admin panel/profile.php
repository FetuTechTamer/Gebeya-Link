<?php
include '../components/connect.php';

if (isset($_COOKIE['seller_id'])) {
    $seller_id = $_COOKIE['seller_id'];

    // Fetch profile data
    $fetch_profile_query = $conn->prepare("SELECT * FROM `seller` WHERE id = ?");
    $fetch_profile_query->bind_param("s", $seller_id);
    $fetch_profile_query->execute();
    $fetch_profile = $fetch_profile_query->get_result()->fetch_assoc(); // Fetch the profile data
} else {
    $seller_id = '';
    header('location:login.php');
    exit(); // Make sure to exit after redirecting
}
// Prepare the SQL statement to select products
$select_products = $conn->prepare("SELECT * FROM product WHERE seller_id = ?"); 
$select_products->bind_param("s", $seller_id); // Bind the seller_id parameter
$select_products->execute(); 
$result_products = $select_products->get_result(); // Get the result set
$total_products = $result_products->num_rows; // Get the total number of products

// Prepare the SQL statement to select orders
$select_orders = $conn->prepare("SELECT * FROM orders WHERE seller_id = ?"); 
$select_orders->bind_param("s", $seller_id); // Bind the seller_id parameter
$select_orders->execute(); 
$result_orders = $select_orders->get_result(); // Get the result set
$total_orders = $result_orders->num_rows; // Get the total number of orders
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller profile page</title>
     <link rel="icon" href="../image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="seller-profile">
            <div class="heading">
                <h1 style="margin-left:300px;">profile details</h1>
                <img src="../image/separator.webp" style="margin-left:300px;">
            </div>

            <div class="details" style=" transform: translateX(200px);"> 
                <div class="seller"> 
                    <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="Profile Image"> 
                    <h3 class="name"><?= $fetch_profile['name']; ?></h3> 
                    <span>Seller</span> 
                    <a href="update.php" class="btn">Update Profile</a> 
                </div> 
                <div class="flex"> 
                    <div class="box"> 
                        <span><?= $total_products; ?></span> 
                        <p>Total Products</p> 
                        <a href="view_product.php" class="btn">View Products</a> 
                    </div> 
                    <div class="box"> 
                        <span><?= $total_orders; ?></span> 
                        <p>Total Orders placed</p> 
                        <a href="admin_orders.php" class="btn">View Orders</a> 
                    </div> 
                </div> 
            </div>
       
        </section>
    </div>   

    <!----- sweetalert cdn link ----->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>
</html>


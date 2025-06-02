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
    header('location:login.php');
    exit(); // Make sure to exit after redirecting
}

// Handle account deletion
if (isset($_POST['delete_account'])) {
    // Delete all products associated with the seller
    $delete_products = $conn->prepare("DELETE FROM product WHERE seller_id = ?");
    $delete_products->bind_param("s", $seller_id);
    $delete_products->execute();

    // Delete the seller account
    $delete_seller = $conn->prepare("DELETE FROM seller WHERE id = ?");
    $delete_seller->bind_param("s", $seller_id);
    if ($delete_seller->execute()) {
        // Clear the seller cookie
        setcookie('seller_id', '', time() - 3600, '/'); 
        $success_msg[] = "Account deleted successfully.";
        header('Location: login.php'); // Redirect to login after deletion
        exit();
    } else {
        $warning_msg[] = "Failed to delete account. Please try again.";
    }
}

// Prepare the SQL statement to select products
$select_products = $conn->prepare("SELECT * FROM product WHERE seller_id = ?"); 
$select_products->bind_param("s", $seller_id); 
$select_products->execute(); 
$result_products = $select_products->get_result(); 
$total_products = $result_products->num_rows; 

// Prepare the SQL statement to select orders
$select_orders = $conn->prepare("SELECT * FROM orders WHERE seller_id = ?"); 
$select_orders->bind_param("s", $seller_id); 
$select_orders->execute(); 
$result_orders = $select_orders->get_result(); 
$total_orders = $result_orders->num_rows; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Profile Page</title>
    <link rel="icon" href="../image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .btn-delete:hover {
            background-color: #d32f2f; /* Darker red on hover */
        }
    </style>
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="seller-profile">
            <div class="heading">
                <h1 style="margin-left:300px;">Profile Details</h1>
                <img src="../image/separator.webp" style="margin-left:300px;">
            </div>

            <div class="details" style="transform: translateX(200px);"> 
                <div class="seller"> 
                    <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="Profile Image"> 
                    <h3 class="name"><?= $fetch_profile['name']; ?></h3> 
                    <span>Seller</span> 
                    <a href="update.php" class="btn">Update Profile</a> 
                    <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                        <button type="submit" name="delete_account" class="btn btn-delete" style="margin-top:20px; margin-right:150px">Delete Account</button>
                    </form>
                </div> 
                <div class="flex"> 
                    <div class="box"> 
                        <span><?= $total_products; ?></span> 
                        <p>Total Products</p> 
                        <a href="view_products.php" class="btn">View Products</a> 
                    </div> 
                    <div class="box"> 
                        <span><?= $total_orders; ?></span> 
                        <p>Total Orders Placed</p> 
                        <a href="admin_order.php" class="btn">View Orders</a> 
                    </div> 
                </div> 
            </div>
        </section>
    </div>   

    <?php include '../components/alert.php'; ?>

    <!----- SweetAlert CDN link ----->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js"></script>
</body>
</html>


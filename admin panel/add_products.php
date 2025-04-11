<?php
include '../components/connect.php';

// Ensure this is at the beginning of your script
if (!isset($_COOKIE['seller_id'])) {
    header('location:login.php');
    exit(); // Stop further execution
}

$seller_id = $_COOKIE['seller_id']; // Get the seller ID from cookies

// Fetch the seller's profile and handle any potential errors
$select_seller = $conn->prepare("SELECT * FROM `seller` WHERE id = ?");
$select_seller->bind_param("s", $seller_id);
$select_seller->execute();
$result = $select_seller->get_result();

if ($result->num_rows > 0) {
    $fetch_profile = $result->fetch_assoc(); // Fetch the seller profile data
} else {
    // If the seller ID is not found, redirect to login
    header('location:login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="post-editor">
            <div class="heading">
                <h1>Dashboard</h1>
                <img src="../image/separator.webp">
            </div>
            

          
        </section>
    </div>   

    <!----- sweetalert cdn link ----->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="../js/admin_script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>
</html>


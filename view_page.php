<?php
include 'components/connect.php';
session_start(); // Start the session

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
} 

$success_msg = [];
$warning_msg = [];

$pid=$_GET['pid'];

include 'components/add_wishlist.php';
include 'components/add_cart.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>product detail Page</title>
    <link rel="icon" href="image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>
 <div class="banner"> 
        <div class="detail" style="padding:400px;"> 
            <h1>product detail</h1> 
            <p>Gebeya Link is dedicated to providing high-quality agricultural products. We focus on sustainable practices <br>and supporting local farmers to ensure fresh and nutritious offerings.<br>
            Our mission is to connect consumers with the best produce while promoting responsible farming.</p> 
            <span><a href="home.php">Home</a> <i class="fa-solid fa-arrow-right"></i> product detail Now</span> 
        </div> 
    </div>
    <section class="view_page"> 
        <div class="heading"> 
            <h1>Product Detail</h1> 
            <img src="image/separator.webp"> 
        </div> 
        <?php 
        if (isset($_GET['pid'])) { 
            $pid = $_GET['pid']; 
            $select_products = $conn->prepare("SELECT * FROM product WHERE id = ?"); 
            $select_products->bind_param("s", $pid); 
            $select_products->execute(); 
            $result = $select_products->get_result(); 

            if ($result->num_rows > 0) { 
                while ($fetch_products = $result->fetch_assoc()) { 
        ?> 
<form action="" method="post" class="box"> 
    <div class="img-box"> 
        <img src="uploaded_files/<?= htmlspecialchars($fetch_products['image']); ?>"> 
    </div> 
    <div class="detail"> 
        <?php if($fetch_products['stock'] > 9) { ?> 
            <span class="stock" style="color:green;">In stock</span> 
        <?php } elseif ($fetch_products['stock'] == 0) { ?> 
            <span class="stock" style="color:red;">Out of stock</span> 
        <?php } else { ?> 
            <span class="stock" style="color: red;">Hurry, only <?= $fetch_products['stock']; ?> left</span> 
        <?php } ?> 
        <p class="price">$<?= htmlspecialchars($fetch_products['price']); ?>/-</p> 
        <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div> 
        <p class="content"><?= htmlspecialchars($fetch_products['product_detail']); ?></p> 


        <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>"> 
        <div class="button"> 
            <button type="submit" name="add_to_wishlist" class="btn">
                Add to Wishlist <i class="fa-solid fa-heart"></i>
            </button> 
            <input type="hidden" name="qty" value="1" min="0" class="quantity"> 
            <button type="submit" name="add_to_cart" class="btn">
                Add to Cart <i class="fa-solid fa-cart-shopping"></i>
            </button> 
        </div>
    </div> 
</form>
        <?php 
                } 
            } 
        } 
        ?> 
    </section>
    <div class="products"> 
        <div class="heading"> 
            <h1>Similar Agricultural Products</h1> 
            <p>Explore our range of high-quality agricultural products, sourced sustainably to support local farmers and ensure freshness.</p> 
            <img src="image/separator.webp" alt="Separator"> 
        </div> 
        <?php include 'components/shop.php'; ?> 
    </div>




<?php include 'components/footer.php'; ?>

<!----- SweetAlert CDN link ----->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js"></script>
 <?php include 'components/alert.php'; ?>
</body>
</html>
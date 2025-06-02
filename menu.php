<?php
include 'components/connect.php';
session_start(); // Start the session

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    header('Location: login.php'); // Redirect if user is not logged in
    exit;
} 

$success_msg = [];
$warning_msg = [];

include 'components/add_wishlist.php';
include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebeya Link - Our Shop</title>
    <link rel="icon" href="image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="products"> 
    <div class="heading"> 
        <h1>Our Latest Products</h1> 
        <img src="image/separator.webp" alt="Separator Image"> 
    </div> 
    <div class="box-container"> 
        <?php 
        // Prepare and execute the product selection query
        $status = 'active';
        $select_product = $conn->prepare("SELECT * FROM product WHERE status = ?");
        $select_product->bind_param("s", $status);
        $select_product->execute();
        $result = $select_product->get_result();
        
        // Check if any products were found
        if ($result->num_rows > 0) { 
            while ($fetch_product = $result->fetch_assoc()) { 
                $seller_id = $fetch_product['seller_id']; // Fetch the seller ID
                ?>
                <form action="" method="post" class="box <?php if ($fetch_product['stock'] == 0) { echo 'disabled'; } ?>"> 
                    <img src="uploaded_files/<?= $fetch_product['image']; ?>" class="image"> 
                    <?php if ($fetch_product['stock'] > 9) { ?> 
                        <span class="stock" style="color: green;">In stock</span> 
                    <?php } elseif ($fetch_product['stock'] == 0) { ?> 
                        <span class="stock" style="color: red;">Out of stock</span> 
                    <?php } else { ?> 
                        <span class="stock" style="color: red;">Hurry, only <?= $fetch_product['stock']; ?> left</span> 
                    <?php } ?> 
                    <div class="content"> 
                        <div class="button"> 
                            <div> 
                                <h3 class="name"><?= htmlspecialchars($fetch_product['name']); ?></h3> 
                            </div> 
                            <div> 
                                <button type="submit" name="add_to_cart"><i class="fas fa-shopping-cart"></i></button> 
                                <button type="submit" name="add_to_wishlist"><i class="fas fa-heart"></i></button> 
                                <a href="view_page.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a> 
                            </div>
                            
                        </div> 
                        <div> 
                            <h3>Seller ID: <?= htmlspecialchars($seller_id); ?></h3> 
                        </div>
                        
                        <p class="price">Price: $<?= htmlspecialchars($fetch_product['price']); ?>/-</p>  
                        <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>"> 
                        <div class="flex-btn"> 
                            <a href="checkout.php?get_id=<?= $fetch_product['id']; ?>" class="btn">Buy Now</a> 
                            <input type="number" name="quantity" required min="1" value="1" max="99" class="qty box" maxlength="2"> 
                        </div>
                    </div> 
                </form>
                <?php 
            } 
        } else {
            echo '
                <div class="empty">
                    <p>No products added yet!</p>
                </div>'; 
        }
        ?> 
    </div> 
</div>

<?php include 'components/footer.php'; ?>

<!----- SweetAlert CDN link ----->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js"></script>
<?php include 'components/alert.php'; ?>
</body>
</html>
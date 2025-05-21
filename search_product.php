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

include 'components/add_wishlist.php';
include 'components/add_cart.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Products Page</title>
    <link rel="icon" href="image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="banner"> 
    <div class="detail" style="padding:400px;"> 
        <h1>Search Products</h1> 
        <p>Gebeya Link is dedicated to providing high-quality agricultural products. We focus on sustainable practices <br>and supporting local farmers to ensure fresh and nutritious offerings.<br>
        Our mission is to connect consumers with the best produce while promoting responsible farming.</p> 
        <span><a href="home.php">Home</a> <i class="fa-solid fa-arrow-right"></i> Search Products Now</span> 
    </div> 
</div>

<div class="products"> 
    <div class="heading"> 
        <h1>Search Result</h1> 
        <img src="image/separator.webp" alt="Separator"> 
    </div> 
    <div class="box-container"> 
        <?php 
        if (isset($_POST['search_product']) || isset($_POST['search_product_btn'])) { 
            $search_products = $_POST['search_product']; 
            $search_products = filter_var($search_products, FILTER_SANITIZE_STRING); 

            $select_products = $conn->prepare("SELECT * FROM product WHERE name LIKE ? AND status = ?"); 
            $search_term = "%{$search_products}%"; 
            $status = 'active';
            $select_products->bind_param("ss", $search_term, $status); 
            $select_products->execute(); 
            $result = $select_products->get_result(); // Get the result set

            if ($result->num_rows > 0) { 
                while ($fetch_products = $result->fetch_assoc()) { 
                    $product_id = $fetch_products['id']; 
                    ?> 
                    <form action="" method="post" class="box <?php if ($fetch_products['stock'] == 0) { echo 'disabled'; } ?>"> 
                        <img src="uploaded_files/<?= $fetch_products['image']; ?>" class="image"> 
                        <?php if ($fetch_products['stock'] > 9) { ?> 
                            <span class="stock" style="color: green;">In stock</span> 
                        <?php } elseif ($fetch_products['stock'] == 0) { ?> 
                            <span class="stock" style="color: red;">Out of stock</span> 
                        <?php } else { ?> 
                            <span class="stock" style="color: red;">Hurry, only <?= $fetch_products['stock']; ?> left</span> 
                        <?php } ?> 
                        <div class="content"> 
                            <div class="button"> 
                                <div> 
                                    <h3 class="name"><?= $fetch_products['name']; ?></h3> 
                                </div> 
                                <div> 
                                    <button type="submit" name="add_to_cart"><i class="fas fa-shopping-cart"></i></button> 
                                    <button type="submit" name="add_to_wishlist"><i class="fas fa-heart"></i></button> 
                                    <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a> 
                                </div>
                            </div> 
                            <p class="price">Price: $<?= $fetch_products['price']; ?></p>  
                            <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>"> 
                            <div class="flex-btn"> 
                                <a href="checkout.php?get_id=<?= $fetch_products['id']; ?>" class="btn">Buy Now</a> 
                                <input type="number" name="quantity" required min="1" value="1" max="99" class="qty box" maxlength="2"> 
                            </div>
                        </div> 
                    </form>
                    <?php 
                } 
            } else { 
                echo '
                <div class="empty"> 
                    <p>No products found</p> 
                </div>'; 
            } 
        } else { 
            echo '
            <div class="empty"> 
                <p>Please search something else</p> 
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
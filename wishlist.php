<?php
include 'components/connect.php';
session_start(); // Start the session

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    header('Location: login.php');
    exit; 
}

$success_msg = [];
$warning_msg = [];

include 'components/add_cart.php';



// Remove product from wishlist 
if (isset($_POST['delete_item'])) { 
    $wishlist_id = $_POST['wishlist_id']; 
    $wishlist_id = filter_var($wishlist_id, FILTER_SANITIZE_STRING); 

    $verify_delete = $conn->prepare("SELECT * FROM wishlist WHERE id = ?"); 
    $verify_delete->bind_param("s", $wishlist_id);
    $verify_delete->execute(); 
    $verify_delete->store_result(); // Ensure we store the result

    if ($verify_delete->num_rows > 0) { 
        $delete_wishlist_id = $conn->prepare("DELETE FROM wishlist WHERE id = ?"); 
        $delete_wishlist_id->bind_param("s", $wishlist_id);
        $delete_wishlist_id->execute(); 
        $success_msg[] = 'Item removed from wishlist'; 
    } else { 
        $warning_msg[] = 'Item already removed'; 
    }
    $verify_delete->close(); // Close the statement
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist</title>
    <link rel="icon" href="image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .banner .detail {
        padding: 50px;
    }

    @media (min-width: 1024px) { 
        .banner .detail {
            padding: 400px; 
        }
    }
    </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>
<div class="banner"> 
    <div class="detail" > 
        <h1>My Wishlist</h1> 
        <p>Gebeya Link is dedicated to providing high-quality agricultural products. We focus on sustainable practices <br>and supporting local farmers to ensure fresh and nutritious offerings.<br>
        Our mission is to connect consumers with the best produce while promoting responsible farming.</p>  
        <span><a href="home.php">Home</a> <i class="fa-solid fa-arrow-right"></i> My Wishlist</span> 
    </div> 
</div>
<div class="products"> 
    <div class="heading"> 
        <h1>My Wishlist</h1> 
        <img src="image/separator.webp" alt="Separator Image"> 
    </div> 
    <div class="box-container"> 
        <?php 
        $grand_total = 0; 

        // Select wishlist items
        $select_wishlist = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ?"); 
        $select_wishlist->bind_param("s", $user_id);
        $select_wishlist->execute(); 
        $select_wishlist->store_result(); // Ensure we store the result

        if ($select_wishlist->num_rows > 0) { 
            $select_wishlist->bind_result($wishlist_id, $user_id, $product_id, $price); // Adjust based on your table structure

            while ($select_wishlist->fetch()) {
                // Select product details
                $select_products = $conn->prepare("SELECT * FROM product WHERE id = ?"); 
                $select_products->bind_param("s", $product_id);
                $select_products->execute(); 
                $result = $select_products->get_result(); // Get the result set

                if ($result->num_rows > 0) { 
                    $fetch_products = $result->fetch_assoc(); // Fetch the product details
?>
<form action="" method="post" class="box <?php if ($fetch_products['stock'] == 0) { echo 'disabled'; } ?>"> 
    <input type="hidden" name="wishlist_id" value="<?= $wishlist_id; ?>"> 
    <img src="uploaded_files/<?= htmlspecialchars($fetch_products['image']); ?>" class="image"> 

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
                <h3><?= htmlspecialchars($fetch_products['name']); ?></h3>
            </div> 
            <div> 
                <button type="submit" name="add_to_cart"> 
                    <i class="bx bx-cart"></i>
                </button> 
                <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="bx bxs-show"></a> 
                <button type="submit" name="delete_item" onclick="return confirm('Remove from wishlist?');">
                  <i class="fas fa-times"></i>
                </button> 
            </div> 
        </div> 
        <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>"> 
        <div class="flex"> 
            <p class="price">Price: <?= htmlspecialchars($fetch_products['price']); ?> birr</p> 
        </div> 
        <div class="flex"> 
            <input type="hidden" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty"> 
            <a href="checkout.php?get_id=<?= $fetch_products['id']; ?>" class="btn">Buy now</a> 
        </div> 
    </div>
</form>
<?php 
                    $grand_total += $fetch_products['price']; 
                } 
            } 
        } else { 
            echo '<div class="empty"> 
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
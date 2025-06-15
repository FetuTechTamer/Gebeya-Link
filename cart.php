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

// Update quantity in cart 
if (isset($_POST['update_cart'])) { 
    $cart_id = $_POST['cart_id']; 
    $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING); 

    $qty = $_POST['quantity']; 
    $qty = filter_var($qty, FILTER_SANITIZE_STRING); 

    $update_qty = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?"); 
    $update_qty->bind_param("is", $qty, $cart_id); // Bind parameters
    if ($update_qty->execute()) {
        $success_msg[] = 'Cart quantity updated successfully';
    }
}

// Delete products from cart 
if (isset($_POST['delete_item'])) { 
    $cart_id = $_POST['cart_id']; 
    $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING); 

    $verify_delete_item = $conn->prepare("SELECT * FROM cart WHERE id = ?"); 
    $verify_delete_item->bind_param("s", $cart_id);
    $verify_delete_item->execute(); 

    if ($verify_delete_item->get_result()->num_rows > 0) { 
        $delete_cart_id = $conn->prepare("DELETE FROM cart WHERE id = ?"); 
        $delete_cart_id->bind_param("s", $cart_id);
        $delete_cart_id->execute(); 
        $success_msg[] = 'Cart item deleted successfully'; 
    } else { 
        $warning_msg[] = 'Cart item already deleted'; 
    }
}

// Empty cart 
if (isset($_POST['empty_cart'])) { 
    $verify_empty_item = $conn->prepare("SELECT * FROM cart WHERE user_id = ?"); 
    $verify_empty_item->bind_param("s", $user_id);
    $verify_empty_item->execute(); 

    if ($verify_empty_item->get_result()->num_rows > 0) { 
        $delete_cart_id = $conn->prepare("DELETE FROM cart WHERE user_id = ?"); 
        $delete_cart_id->bind_param("s", $user_id);
        $delete_cart_id->execute(); 
        $success_msg[] = 'Cart emptied successfully'; 
    } else { 
        $warning_msg[] = 'Your cart is already empty'; 
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
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
        <h1>Cart</h1> 
       <p>Gebeya Link is dedicated to providing high-quality agricultural products. We focus on sustainable practices <br>and supporting local farmers to ensure fresh and nutritious offerings.<br>
        Our mission is to connect consumers with the best produce while promoting responsible farming.</p> 
        <span><a href="home.php">Home</a> <i class="fa-solid fa-arrow-right"></i> Cart</span> 
    </div> 
</div>
<div class="products">
    <div class="heading"> 
        <h1>My Cart</h1> 
        <img src="image/separator.webp" alt="Separator"> 
    </div> 
    <div class="box-container"> 
    <?php 
    $grand_total = 0; 

    // Select cart items
    $select_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?"); 
    $select_cart->bind_param("s", $user_id); 
    $select_cart->execute(); 
    $result_cart = $select_cart->get_result(); 

    if ($result_cart->num_rows > 0) { 
        while ($fetch_cart = $result_cart->fetch_assoc()) { 
            // Select product details
            $select_products = $conn->prepare("SELECT * FROM product WHERE id = ?"); 
            $select_products->bind_param("s", $fetch_cart['product_id']); 
            $select_products->execute(); 
            $result_products = $select_products->get_result(); 

            if ($result_products->num_rows > 0) { 
                $fetch_products = $result_products->fetch_assoc(); 
                $sub_total = $fetch_cart['quantity'] * $fetch_products['price']; // Calculate subtotal
                $grand_total += $sub_total; // Add to grand total
                ?>
                <form action="" method="post" class="box <?php if ($fetch_products['stock'] == 0) { echo 'disabled'; } ?>"> 
                    <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>"> 
                    <img src="uploaded_files/<?= $fetch_products['image']; ?>" class="image"> 

                    <?php if ($fetch_products['stock'] > 9) { ?> 
                        <span class="stock" style="color: green;">In stock</span> 
                    <?php } elseif ($fetch_products['stock'] == 0) { ?> 
                        <span class="stock" style="color: red;">Out of stock</span> 
                    <?php } else { ?> 
                        <span class="stock" style="color: red;">Hurry, only <?= $fetch_products['stock']; ?> left</span> 
                    <?php } ?> 

                    <div class="content"> 
                        <h3 class="name"><?= htmlspecialchars($fetch_products['name']); ?></h3> 
                        <div class="flex-btn"> 
                            <p class="price">Price: <?= htmlspecialchars($fetch_products['price']); ?>birr</p> 
                            <input type="number" name="quantity" required min="1" value="<?= $fetch_cart['quantity']; ?>" max="99" maxlength="2" class="box qty"> 
                            <button type="submit" name="update_cart" class="fa fa-edit"></button>
                        </div> 
                        <div class="flex-btn"> 
                            <p class="sub-total">Subtotal: <span>$<?= htmlspecialchars($sub_total); ?></span></p> 
                            <p>(<?= $fetch_cart['quantity']; ?>)</p>
                            <button type="submit" name="delete_item" class="btn" onclick="return confirm('Remove from cart?');">Delete</button> 
                        </div>
                    </div> 
                </form>
                <?php 
            } 
        } 
    } else { 
        echo '<div class="empty"> 
                <p>No products added yet!</p> 
              </div>'; 
    } 
    ?> 
    </div> 

    <?php if ($grand_total != 0) { ?> 
    <div class="cart-total"> 
        <p>Total amount payable: <span><?= htmlspecialchars($grand_total); ?>birr</span></p> 
        <div class="button"> 
            <form action="" method="post"> 
                <button type="submit" name="empty_cart" class="btn" onclick="return confirm('Are you sure you want to empty your cart?');">Empty Cart</button> 
            </form> 
            <a href="checkout.php" class="btn">Proceed to Checkout</a> 
        </div> 
    </div>
    <?php } ?>
</div>
</div>
<?php include 'components/footer.php'; ?>

<!----- SweetAlert CDN link ----->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js"></script>

<?php include 'components/alert.php'; ?>

</body>
</html>
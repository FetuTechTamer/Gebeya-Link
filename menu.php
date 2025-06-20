<?php
include 'components/connect.php';
session_start();

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    header('Location: login.php');
    exit;
}

$success_msg = [];
$warning_msg = [];

// Handle buying logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['buy'])) {
        $product_id = $_POST['product_id'];
        $quantity = intval($_POST['quantity']);

        $product_query = $conn->prepare("SELECT * FROM product WHERE id = ?");
        $product_query->bind_param("s", $product_id);
        $product_query->execute();
        $product_result = $product_query->get_result();
        $product = $product_result->fetch_assoc();

        if ($product['stock'] >= $quantity) {
            $new_stock = $product['stock'] - $quantity;
            $update_stock = $conn->prepare("UPDATE product SET stock = ? WHERE id = ?");
            $update_stock->bind_param("is", $new_stock, $product_id);
            $update_stock->execute();

            if ($new_stock == 0) {
                $delete_product = $conn->prepare("DELETE FROM product WHERE id = ?");
                $delete_product->bind_param("s", $product_id);
                $delete_product->execute();
            }

            header("Location: checkout.php?get_id=$product_id&quantity=$quantity");
            exit();
        } else {
            $warning_msg[] = "Unavailable quantity for " . htmlspecialchars($product['name']) . ". Only " . $product['stock'] . " available.";
        }
    }
}

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
    $status = 'active';
    $select_product = $conn->prepare("SELECT * FROM product WHERE status = ?");
    $select_product->bind_param("s", $status);
    $select_product->execute();
    $result = $select_product->get_result();


    if ($result->num_rows > 0) { 
    while ($fetch_product = $result->fetch_assoc()) {
            $today = date('Y-m-d');
            $expire_date_raw = $fetch_product['expire_date'];
            $expire_date = date('Y-m-d', strtotime($expire_date_raw));

            if ($expire_date < $today) {
                // Auto-delete expired product
                $delete_expired = $conn->prepare("DELETE FROM product WHERE id = ?");
                $delete_expired->bind_param("s", $fetch_product['id']);
                $delete_expired->execute();
                continue; // Skip expired product
            }
            ?>
            <form action="" method="post" class="box <?php if ($fetch_product['stock'] == 0) echo 'disabled'; ?>"> 
                <img src="uploaded_files/<?= $fetch_product['image']; ?>" class="image"> 
                
                <div class="stock-info">
                    <?php if ($fetch_product['stock'] >= 10) { ?> 
                        <span style="color: green;">
                            In stock: <?= $fetch_product['stock']; ?> kilo
                        </span> 
                    <?php } elseif ($fetch_product['stock'] > 0) { ?> 
                        <span style="color: red;">
                            Hurry, only <?= $fetch_product['stock']; ?> kilo left!
                        </span> 
                    <?php } else { ?>
                        <span style="color: gray;">Out of Stock</span>
                    <?php } ?>

                    <?php 
                    $today = date('Y-m-d');
                    $expire_date_raw = $fetch_product['expire_date'];
                    $expire_date = date('Y-m-d', strtotime($expire_date_raw)); // Normalize to Y-m-d

                    if ($expire_date == date('Y-m-d', strtotime('+1 day'))) { ?> 
                        <span style="color: red;">
                            Hurry, expires tomorrow!
                        </span> 
                    <?php } elseif ($expire_date > $today) { ?> 
                        <span style="color: green;">
                            Expire Date: <?= htmlspecialchars($expire_date); ?> 
                        </span> 
                    <?php } else { ?>
                        <span style="color: gray;">The item has expired</span>
                    <?php } ?>

                </div>

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
                        <h3>Seller ID: <?= htmlspecialchars($fetch_product['seller_id']); ?></h3> 
                    </div>
                    
                    <p class="price">Price: <?= htmlspecialchars($fetch_product['price']); ?> birr</p>  
                    <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>"> 
                    <div class="flex-btn"> 
                        <button type="submit" name="buy" class="btn">Buy Now</button>
                        <input type="number" name="quantity" required min="1" value="1" max="<?= $fetch_product['stock']; ?>" class="qty box" maxlength="2"> 
                    </div>
                </div> 
            </form>
            <?php 
        } 
    } else {
        echo '<div class="empty"><p>No products added yet!</p></div>'; 
    }
    ?> 
    </div>
</div>

<?php include 'components/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js"></script>
<?php include 'components/alert.php'; ?>
</body>
</html>

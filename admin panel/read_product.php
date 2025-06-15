<?php
include '../components/connect.php';

if (isset($_COOKIE['seller_id'])) {
    $seller_id = $_COOKIE['seller_id'];
} else {
    header('location:login.php');
    exit(); 
}

// Get the product ID from the URL
$get_id = $_GET['post_id'];

// Delete product 
if (isset($_POST['delete'])) { 
    $p_id = $_POST['product_id']; 
    $p_id = filter_var($p_id, FILTER_SANITIZE_STRING); 

    // Prepare statement to get the product image
    $delete_image = $conn->prepare("SELECT * FROM `product` WHERE id = ? AND seller_id = ?"); 
    $delete_image->bind_param("ss", $p_id, $seller_id); 
    $delete_image->execute(); 
    $result = $delete_image->get_result(); 
    $fetch_delete_image = $result->fetch_assoc(); 
    
    // Check if an image exists and delete it
    if (!empty($fetch_delete_image['image'])) { 
        unlink('../uploaded_files/' . $fetch_delete_image['image']); 
    } 
    
    // Prepare statement to delete the product
    $delete_product = $conn->prepare("DELETE FROM `product` WHERE id = ? AND seller_id = ?"); 
    $delete_product->bind_param("ss", $p_id, $seller_id); 
    $delete_product->execute(); 
    
    header("location:view_products.php");
    exit(); 
}

// Fetch the selected product for the seller
$select_product = $conn->prepare("SELECT * FROM `product` WHERE id = ? AND seller_id = ?");
$select_product->bind_param("ss", $get_id, $seller_id);
$select_product->execute();
$result_product = $select_product->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Product</title>
     <link rel="icon" href="../image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="read-post">
            <div class="heading" style="text-align: center;">
                <h1 >Product Detail</h1>
                <img src="../image/separator.webp" >
            </div>
           
            <?php 
            if ($result_product->num_rows > 0) { 
                $fetch_product = $result_product->fetch_assoc();
            ?>
            <form action="" method="post" class="box" style=" transform: none;"> 
                <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>"> 

                <div class="status" style="color: <?= $fetch_product['status'] == 'active' ? 'limegreen' : 'coral'; ?>">
                    <?= $fetch_product['status']; ?>
                </div> 

                <?php if ($fetch_product['image'] != ''): ?> 
                    <img src="../uploaded_files/<?= $fetch_product['image']; ?>" class="image" alt="Product Image"> 
                <?php endif; ?> 

                <div class="price"><?= $fetch_product['price']; ?> birr</div> 
                <div class="title"><?= $fetch_product['name']; ?></div> 
                <div class="content"><?= $fetch_product['product_detail']; ?></div> 

                <div class="flex-btn"> 
                    <a href="edit_product.php?id=<?= $fetch_product['id']; ?>" class="btn">Edit</a> 
                    <button type="submit" name="delete" class="btn" onclick="return confirm('Delete this product?');">Delete</button> 
                    <a href="view_products.php" class="btn">Go Back</a> 
                </div> 
            </form>
            <?php
            } else {
                echo '
                <div class="empty">
                    <p>No product found!</p>
                </div>
                ';
            }
            ?>
        </section>
    </div>

    <!----- SweetAlert CDN link ----->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>
</html>
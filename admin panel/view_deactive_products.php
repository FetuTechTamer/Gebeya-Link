<?php
include '../components/connect.php';

if (isset($_COOKIE['seller_id'])) {
    $seller_id = $_COOKIE['seller_id'];
} else {
    header('location:login.php');
    exit(); // Ensure to exit after redirecting
}

// Delete product
if (isset($_POST['delete'])) {
    $p_id = $_POST['product_id'];
    $p_id = filter_var($p_id, FILTER_SANITIZE_STRING);
    
    $delete_product = $conn->prepare("DELETE FROM `product` WHERE id=?");
    $delete_product->bind_param("s", $p_id); 
    $delete_product->execute();

    $success_msg = 'Product deleted successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Deactive Products</title>
    <link rel="icon" href="../image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="show-post">
            <div class="heading">
                <h1>Your Deactive Products</h1>
                <img src="../image/separator.webp">
            </div>
            <div class="box-container">
                <?php
                // Prepare the SQL statement to select deactive products
                $select_products = $conn->prepare("SELECT * FROM `product` WHERE seller_id = ? AND status = 'deactive'");
                $select_products->bind_param("s", $seller_id);
                $select_products->execute();
                $result_products = $select_products->get_result();

                if ($result_products->num_rows > 0) {
                    while ($fetch_products = $result_products->fetch_assoc()) {
                ?>
                <form action="" method="post" class="box">
                    <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>">
                    <?php if ($fetch_products['image'] != '') { ?>
                        <img src="../uploaded_files/<?= $fetch_products['image']; ?>" class="image" alt="Product Image">
                    <?php } ?>
                    <div class="status" style="color: coral;">
                        <?= $fetch_products['status']; ?>
                    </div>
                    <div class="price">$<?= $fetch_products['price']; ?></div>
                    <div class="content">
                        <div class="title"><?= $fetch_products['name']; ?></div>
                        <div class="flex-btn">
                            <a href="edit_product.php?id=<?= $fetch_products['id']; ?>" class="btn">Edit</a>
                            <button type="submit" name="delete" class="btn" onclick="return confirm('Delete this product?');">Delete</button>
                            <a href="read_product.php?post_id=<?= $fetch_products['id']; ?>" class="btn">Read</a>
                        </div>
                    </div>
                </form>
                <?php
                    }
                } else {
                    echo '
                    <div class="empty">
                        <p>No deactive products yet!</p>
                        <a href="add_products.php" class="btn" style="margin-top:1.5rem;">Add Product</a>
                    </div>
                    ';
                }
                ?>
            </div>
        </section>
    </div>
    
    <!----- SweetAlert CDN link ----->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>
</html>
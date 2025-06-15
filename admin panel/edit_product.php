<?php
include '../components/connect.php';

if (isset($_COOKIE['seller_id'])) {
    $seller_id = $_COOKIE['seller_id'];
} else {
    header('location:login.php');
    exit();
}


$success_msg = [];
$warning_msg = [];

// Update product
if (isset($_POST['update'])) { 
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_STRING);
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $stock = filter_var($_POST['stock'], FILTER_SANITIZE_STRING);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
    
    $stmt = $conn->prepare("UPDATE product SET name=?, price=?, product_detail=?, stock=?, status=? WHERE id=? AND seller_id=?"); 
    $stmt->bind_param("sisisss", $name, $price, $description, $stock, $status, $product_id, $seller_id); 
    $stmt->execute(); 

    $success_msg[] = "Product updated successfully!"; 

    // Handle image upload
    $old_image = $_POST['old_image']; 
    $image = $_FILES['image']['name']; 
    $image_size = $_FILES['image']['size']; 
    $image_tmp_name = $_FILES['image']['tmp_name']; 
    $image_folder = '../uploaded_files/' . $image; 

    if (!empty($image)) { 
        if ($image_size > 2000000) { 
            $warning_msg[] = 'Image size is too large. Maximum size is 2MB.'; 
        } else { 
            $update_image = $conn->prepare("UPDATE product SET image=? WHERE id=? AND seller_id=?"); 
            $update_image->bind_param("ssi", $image, $product_id, $seller_id); 
            $update_image->execute(); 

            move_uploaded_file($image_tmp_name, $image_folder); 

            if ($old_image != '' && $old_image != $image) { 
                unlink('../uploaded_files/' . $old_image); 
            } 
            $success_msg[] = 'Image updated successfully!'; 
        } 
    }
}

// Fetch the selected product for the seller
$product_id = $_GET['id']; 
$select_product = $conn->prepare("SELECT * FROM product WHERE id = ? AND seller_id = ?"); 
$select_product->bind_param("ss", $product_id, $seller_id); 
$select_product->execute(); 
$result = $select_product->get_result(); 

if ($result->num_rows === 0) {
    echo '<div class="empty"> 
            <p>Product not found!</p> 
          </div>'; 
    exit();
}

$fetch_product = $result->fetch_assoc(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
     <link rel="icon" href="../image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .image {
            width: 100px;
            height: 75px;
            object-fit: cover;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="post-editor">
            <div class="heading" >
                <h1>Edit Product</h1>
                <img src="../image/separator.webp">
            </div>
            <div class="box-container"> 
                <div class="form-container"> 
                    <form action="" method="post" enctype="multipart/form-data" class="register"> 
                        <input type="hidden" name="old_image" value="<?= htmlspecialchars($fetch_product['image']); ?>"> 
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($fetch_product['id']); ?>"> 
                        <div class="input-field"> 
                            <p>Product Status <span>*</span></p> 
                            <div class="input-field"> 
                                <select name="status" class="box"> 
                                    <option value="active" <?= $fetch_product['status'] == 'active' ? 'selected' : '' ?>>active</option>
                                    <option value="deactive" <?= $fetch_product['status'] == 'deactive' ? 'selected' : '' ?>>deactive</option>
                                </select> 
                            </div>
                        </div> 
                        <br><br>
                        <div class="input-field"> 
                            <p>Product Name <span>*</span></p> 
                            <input type="text" name="name" value="<?= htmlspecialchars($fetch_product['name']); ?>" class="box"> 
                        </div> 
                        <div class="input-field"> 
                            <p>Product Price <span>*</span></p> 
                            <input type="number" name="price" value="<?= htmlspecialchars($fetch_product['price']); ?>" class="box"> 
                        </div>
                        <div class="input-field"> 
                            <p>Product Description <span>*</span></p> 
                            <textarea name="description" class="box"><?= htmlspecialchars($fetch_product['product_detail']); ?></textarea> 
                        </div> 
                        <div class="input-field"> 
                            <p>Product Stock <span>*</span></p> 
                            <input type="number" name="stock" value="<?= htmlspecialchars($fetch_product['stock']); ?>" class="box" min="0" max="9999999999" maxlength="10"> 
                        </div> 

                        <div class="input-field"> 
                            <p>Product Image <span>*</span></p> 
                            <input type="file" name="image" accept="image/*" class="box"> 
                            <?php if ($fetch_product['image'] != '') { ?> 
                                <img src="../uploaded_files/<?= htmlspecialchars($fetch_product['image']); ?>" class="image" alt="Current Product Image"> 
                            <?php } ?> 
                        </div>
                        <div class="flex-btn"> 
                            <input type="submit" name="update" value="Update Product" class="btn"> 
                            <a href="view_products.php" class="btn">Go Back</a> 
                        </div> 
                    </form> 
                </div>
           </div>     
       </section>
    </div>   

    <!----- SweetAlert CDN link ----->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>
</html>



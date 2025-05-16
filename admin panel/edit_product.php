<?php
include '../components/connect.php';

if (isset($_COOKIE['seller_id'])) {
    $seller_id = $_COOKIE['seller_id'];
} else {
    header('location:login.php');
    exit(); // Ensure to exit after redirection
}

// Initialize message variables
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
    
    $stmt = $conn->prepare("UPDATE product SET name=?, price=?, product_detail=?, stock=?, status=? WHERE id=?"); 
    $stmt->bind_param("sdsssi", $name, $price, $description, $stock, $status, $product_id); 
    $stmt->execute(); 

    $success_msg[] = "Product updated successfully!"; 

    // Handle image upload
    $old_image = $_POST['old_image']; 
    $image = $_FILES['image']['name']; 
    $image_size = $_FILES['image']['size']; 
    $image_tmp_name = $_FILES['image']['tmp_name']; 
    $image_folder = '../uploaded_files/' . $image; 

    $select_image = $conn->prepare("SELECT * FROM product WHERE image=? AND seller_id=?"); 
    $select_image->bind_param("si", $image, $seller_id); 
    $select_image->execute();  

    if (!empty($image)) { 
        if ($image_size > 2000000) { 
            $warning_msg[] = 'Image size is too large. Maximum size is 2MB.'; 
        } elseif ($select_image->num_rows > 0) { 
            $warning_msg[] = 'Please rename your image.'; 
        } else { 
            $update_image = $conn->prepare("UPDATE product SET image=? WHERE id=?"); 
            $update_image->bind_param("si", $image, $product_id); 
            $update_image->execute(); 

            move_uploaded_file($image_tmp_name, $image_folder); 

            if ($old_image != $image && $old_image != '') { 
                unlink('../uploaded_files/' . $old_image); 
            } 
            $success_msg[] = 'Image updated successfully!'; 
        } 
    }
}

// Delete image
if (isset($_POST['delete_image'])) { 
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_STRING); 

    $delete_image = $conn->prepare("SELECT image FROM product WHERE id=?"); 
    $delete_image->bind_param("i", $product_id); 
    $delete_image->execute(); 
    $result = $delete_image->get_result(); 
    $fetch_delete_image = $result->fetch_assoc(); 

    if ($fetch_delete_image['image'] != '') { 
        unlink('../uploaded_files/' . $fetch_delete_image['image']); 
    }

    $unset_image = $conn->prepare("UPDATE product SET image=NULL WHERE id=?"); 
    $unset_image->bind_param("i", $product_id); 
    $unset_image->execute(); 

    $success_msg[] = 'Image deleted successfully!';
}

// Delete product
if (isset($_POST['delete_product'])) { 
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_STRING); 

    $delete_image = $conn->prepare("SELECT image FROM product WHERE id=?"); 
    $delete_image->bind_param("i", $product_id); 
    $delete_image->execute(); 
    $result = $delete_image->get_result(); 
    $fetch_delete_image = $result->fetch_assoc(); 

    if ($fetch_delete_image['image'] != '') { 
        unlink('../uploaded_files/' . $fetch_delete_image["image"]); 
    } 

    $delete_product = $conn->prepare("DELETE FROM product WHERE id=?"); 
    $delete_product->bind_param("i", $product_id); 
    $delete_product->execute(); 

    $success_msg[] = 'Product deleted successfully!'; 
    header("Location: view_product.php"); 
    exit(); // Ensure no further code is executed
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="post-editor">
            <div class="heading" style="text-align: center;">
                <h1 style="padding-top: 100px;">Edit Product</h1>
                <img src="../image/separator.webp" style="width: 100%; max-width: 600px; margin: 0 auto;">
            </div>
            <div class="box-container"> 
<?php 
$product_id = $_GET['id']; 
$select_product = $conn->prepare("SELECT * FROM product WHERE id = ? AND seller_id = ?"); 
$select_product->bind_param("ss", $product_id, $seller_id); 
$select_product->execute(); 
$result = $select_product->get_result(); 

if ($result->num_rows > 0) { 
    while($fetch_product = $result->fetch_assoc()) { 
?>
<div class="form-container"> 
    <form action="" method="post" enctype="multipart/form-data" class="register" style="transform: translateX(80px);"> 
        <input type="hidden" name="old_image" value="<?= $fetch_product['image']; ?>"> 
        <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>"> 
        <div class="input-field"> 
            <p>Product Status <span>*</span></p> 
            <select name="status" class="box"> 
                <option value="<?= $fetch_product['status']; ?>" selected><?= $fetch_product['status']; ?></option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select> 
        </div> 
        <div class="input-field"> 
            <p>Product Name <span>*</span></p> 
            <input type="text" name="name" value="<?= $fetch_product['name']; ?>" class="box"> 
        </div> 
        <div class="input-field"> 
            <p>Product Price <span>*</span></p> 
            <input type="number" name="price" value="<?= $fetch_product['price']; ?>" class="box"> 
        </div>
        <div class="input-field"> 
            <p>Product Description <span>*</span></p> 
            <textarea name="description" class="box"><?= $fetch_product['product_detail']; ?></textarea> 
        </div> 
        <div class="input-field"> 
            <p>Product Stock <span>*</span></p> 
            <input type="number" name="stock" value="<?= $fetch_product['stock']; ?>" class="box" min="0" max="9999999999" maxlength="10"> 
        </div> 

        <div class="input-field"> 
            <p>Product Image <span>*</span></p> 
            <input type="file" name="image" accept="image/*" class="box"> 
            <?php if ($fetch_product['image'] != '') { ?> 
                <img src="../uploaded_files/<?= $fetch_product['image']; ?>" class="image" alt="Current Product Image" style="max-width: 100px; margin-top: 10px;"> 
                <div class="flex-btn"> 
                    <input type="submit" name="delete_image" class="btn" value="Delete Image"> 
                    <a href="view_products.php" class="btn" style="width: 49%; text-align: center; height: 3rem; margin-top:.7rem;">Go Back</a> 
                </div> 
            <?php } ?> 
        </div>
        <div class="flex-btn"> 
            <input type="submit" name="update" value="Update Product" class="btn"> 
            <input type="submit" name="delete_product" value="Delete Product" class="btn"> 
        </div> 
    </form> 
</div>
        <?php 
    } 
} else { 
    echo '<div class="empty"> 
            <p>No product added yet!</p> 
          </div>'; 
?> 
                <br><br>
                <div class="flex-btn"> 
                    <a href="view_products.php" class="btn">View Product</a> 
                    <a href="add_products.php" class="btn">Add Product</a> 
                </div>
        <?php } ?>

           </div>     
       </section>
    </div>   

    <!----- SweetAlert CDN link ----->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>
</html>


46:40 v3
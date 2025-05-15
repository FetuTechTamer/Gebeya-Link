<?php
include '../components/connect.php';

if (isset($_COOKIE['seller_id'])) {
    $seller_id = $_COOKIE['seller_id'];
} else {
    $seller_id = '';
    header('location:login.php');
    exit(); // Make sure to exit after redirection
}

// Initialize message variables
$success_msg = [];
$warning_msg = [];

// Add product in database
if (isset($_POST['publish'])) {
    $id = unique_id();
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $stock = filter_var($_POST['stock'], FILTER_SANITIZE_STRING);
    $status = 'active'; 
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_files/' . $image;

    // Check if image already exists
    $select_image = $conn->prepare("SELECT * FROM `product` WHERE image = ? AND seller_id = ?");
    $select_image->bind_param("ss", $image, $seller_id);
    $select_image->execute();
    $result_image = $select_image->get_result();

    // Validate image
    if (empty($image)) {
        $warning_msg[] = 'Please select an image.';
    } elseif ($result_image->num_rows > 0) {
        $warning_msg[] = 'Image name repeated.';
    } elseif ($image_size > 2000000) {
        $warning_msg[] = 'Image size is too large. Maximum size is 2MB.';
    } else {
        // Move uploaded file if all checks pass
        move_uploaded_file($image_tmp_name, $image_folder);

        // Insert product into database
        $insert_product = $conn->prepare("INSERT INTO `product` (id, seller_id, name, price, image, stock, product_detail, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_product->bind_param("ssssssss", $id, $seller_id, $name, $price, $image, $stock, $description, $status);
        $insert_product->execute();
        $success_msg[] = 'Product inserted successfully!';
    }
}

// Draft product in database
if (isset($_POST['draft'])) {
    $id = unique_id();
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $stock = filter_var($_POST['stock'], FILTER_SANITIZE_STRING);
    $status = 'deactive'; 
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_files/' . $image;

    // Check if image already exists
    $select_image = $conn->prepare("SELECT * FROM `product` WHERE image = ? AND seller_id = ?");
    $select_image->bind_param("ss", $image, $seller_id);
    $select_image->execute();
    $result_image = $select_image->get_result();

    // Validate image
    if (empty($image)) {
        $warning_msg[] = 'Please select an image.';
    } elseif ($result_image->num_rows > 0) {
        $warning_msg[] = 'Image name repeated.';
    } elseif ($image_size > 2000000) {
        $warning_msg[] = 'Image size is too large. Maximum size is 2MB.';
    } else {
        // Move uploaded file if all checks pass
        move_uploaded_file($image_tmp_name, $image_folder);

        // Insert product into database
        $insert_product = $conn->prepare("INSERT INTO `product` (id, seller_id, name, price, image, stock, product_detail, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_product->bind_param("ssssssss", $id, $seller_id, $name, $price, $image, $stock, $description, $status);
        $insert_product->execute();
        $success_msg[] = 'Product saved as draft successfully!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Products</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="post-editor">
            <div class="heading" style="text-align: center;">
                <h1 style="padding-top: 100px;">Add Products</h1>
                <img src="../image/separator.webp" style="width: 100%; max-width: 600px; margin: 0 auto;">
            </div>
            
            <div class="form-container">
                <form action="" method="POST" enctype="multipart/form-data" class="register" style="transform: translateX(80px);">
                    <div class="input-field">
                        <p>Product Name <span>*</span></p>
                        <input type="text" name="name" maxlength="100" placeholder="Add product name" required>
                    </div>

                    <div class="input-field">
                        <p>Product Price <span>*</span></p>
                        <input type="number" name="price" maxlength="100" placeholder="Add product price" required>
                    </div>

                    <div class="input-field">
                        <p>Product Detail <span>*</span></p>
                        <textarea name="description" required maxlength="1000" placeholder="Add product detail" class="box"></textarea>
                    </div>

                    <div class="input-field">
                        <p>Product Stock <span>*</span></p>
                        <input type="number" name="stock" maxlength="10" min="0" max="99999999" placeholder="Add product stock" required class="box">
                    </div>

                    <div class="input-field">
                        <p>Product Image <span>*</span></p>
                        <input type="file" name="image" accept="image/*" required class="box">
                    </div>

                    <div class="flex-btn">
                        <input type="submit" name="publish" value="Add Product" class="btn">
                        <input type="submit" name="draft" value="Save as Draft" class="btn">
                    </div>
                </form>
            </div>
        </section>
    </div>   

    <!----- SweetAlert CDN link ----->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>
</html>
<?php
include '../components/connect.php';

$success_msg = [];
$warning_msg = [];

if (isset($_POST['submit'])) {
    $id = unique_id();
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    $password = $_POST['password']; 
    $confirm_password = $_POST['confirm_password']; 

    // Hash passwords
    if ($password !== $confirm_password) {
        $warning_msg[] = 'Confirm password does not match!';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $image = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        $rename = unique_id() . '.' . $ext;
        $image_folder = '../uploaded_files/' . $rename;

        // Check if the email already exists
        $select_seller = $conn->prepare("SELECT * FROM `seller` WHERE email=?");
        $select_seller->bind_param("s", $email);
        $select_seller->execute();
        $result = $select_seller->get_result();

        if ($result->num_rows > 0) {
            $warning_msg[] = 'Email already exists';
        } else {
            // Insert new seller
            $insert_seller = $conn->prepare("INSERT INTO `seller` (id, name, email, password, image) VALUES (?, ?, ?, ?, ?)");
            $insert_seller->bind_param("sssss", $id, $name, $email, $hashed_password, $rename);

            if ($insert_seller->execute()) {
                move_uploaded_file($image_tmp_name, $image_folder);
                $success_msg[] = 'New seller registered! Please log in now.';
            } else {
                $warning_msg[] = 'Registration failed: ' . $insert_seller->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Registration Page</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="form-container">
    <form action="" method="POST" enctype="multipart/form-data" class="register">
        <h3>Register Now</h3>
        <div class="flex">
            <div class="col">
                <div class="input-field">
                    <p>Your Name<span>*</span></p>
                    <input type="text" name="name" placeholder="Enter your name" maxlength="50" required class="box">
                </div>
                <div class="input-field">
                    <p>Your Email<span>*</span></p>
                    <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
                </div>
            </div>
            <div class="col">
                <div class="input-field">
                    <p>Your Password<span>*</span></p>
                    <input type="password" name="password" placeholder="Enter your password" maxlength="50" required class="box">
                </div>
                <div class="input-field">
                    <p>Confirm Your Password<span>*</span></p>
                    <input type="password" name="confirm_password" placeholder="Confirm your password" maxlength="50" required class="box">
                </div>
            </div>
        </div>
    <p class="link">Do not have an account?<a href="register.php"> register now</a></p>
        <input type="submit" name="submit" value="Register Now" class="btn">
    </form>
</div>

<!-----sweetalert cdn link---->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<scrip src='../js/script.js'></script>
<?php include '../components/alert.php'; ?>
</body>
</html>
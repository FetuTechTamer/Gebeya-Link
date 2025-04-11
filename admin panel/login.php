<?php
include '../components/connect.php';


if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; 
    
  

    $select_seller = $conn->prepare("SELECT * FROM `seller` WHERE email=?");
    $select_seller->bind_param("s", $email);
    $select_seller->execute();

    $result = $select_seller->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
            setcookie('seller_id', $row['id'], time() + (60 * 60 * 24 * 30), '/'); 
            header('Location: dashboard.php');
            exit(); 
        } else {
            $warning_msg[] = 'Incorrect email or password';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Login Page</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="form-container">
    <form action="" method="POST" enctype="multipart/form-data" class="register">
        <h3>Login Now</h3>
        <div class="input-field">
            <p>Your Email<span>*</span></p>
            <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
        </div>
        
        <div class="input-field">
            <p>Your Password<span>*</span></p>
            <input type="password" name="password" placeholder="Enter your password" maxlength="50" required class="box">
        </div>
        
        <p class="link">Do not have an account?<a href="register.php"> Register now</a></p>
        <input type="submit" name="submit" value="Login Now" class="btn">
    </form>
</div>

<!----- sweetalert cdn link ----->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script src='../js/script.js'></script>
<?php include '../components/alert.php'; ?>

</body>
</html>
<?php
include 'components/connect.php';
session_start();

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
} 

$success_msg = [];
$warning_msg = [];

if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = sha1($_POST['password']); 
    $password = filter_var($password, FILTER_SANITIZE_STRING);
  
    $select_user = $conn->prepare("SELECT * FROM `user` WHERE email = ? AND password = ?");
    $select_user->bind_param("ss", $email, $password);
    $select_user->execute();

    $result = $select_user->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        setcookie('user_id', $row['id'], time() + (60 * 60 * 24 * 30), '/'); 
        $success_msg[] = 'Login successful! Redirecting...'; // Add success message
        header('Location: home.php');
        exit(); 
    } else { 
        $warning_msg[] = 'Incorrect email or password';
    }
}

// Pass messages to alert component
if (!empty($warning_msg)) {
    $_SESSION['warning_msg'] = $warning_msg;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login Page</title>
    <link rel="icon" href="image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>
 <div class="banner"> 
        <div class="detail" style="padding:400px;"> 
            <h1>User Login Page</h1> 
            <p>Gebeya Link is dedicated to providing high-quality agricultural products. We focus on sustainable practices <br>and supporting local farmers to ensure fresh and nutritious offerings.<br>
            Our mission is to connect consumers with the best produce while promoting responsible farming.</p> 
            <span><a href="home.php">Home</a> <i class="fa-solid fa-arrow-right"></i> Login Now</span> 
        </div> 
    </div>
<div class="form-container">
    <form action="" method="POST" class="register" style="transform: none;">
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
        <p class="link">Forget Password?<a href="password_reset.php"> Reset now</a></p>
        <input type="submit" name="submit" value="Login Now" class="btn">
    </form>
</div>

<?php include 'components/footer.php'; ?>

<!----- SweetAlert CDN link ----->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js"></script>
 <?php include 'components/alert.php'; ?>
</body>
</html>
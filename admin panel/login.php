<?php
session_start(); // Start the session
include '../components/connect.php';

// Initialize message variables
$success_msg = [];
$warning_msg = [];

if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    $password = sha1($_POST['password']); 
    $password = filter_var($password, FILTER_SANITIZE_STRING);
  
    $select_seller = $conn->prepare("SELECT * FROM `seller` WHERE email = ? AND password = ?");
    $select_seller->bind_param("ss", $email, $password);
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

// Store messages in session for display
if (!empty($warning_msg)) {
    $_SESSION['warning_msg'] = $warning_msg;
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
    <form action="" method="POST" class="register">
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

<!----- SweetAlert CDN link ----->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/script.js"></script>

<?php
// Display alerts if there are any messages
if (isset($_SESSION['warning_msg'])) {
    foreach ($_SESSION['warning_msg'] as $message) {
        echo "<script>swal('Warning', '$message', 'warning');</script>";
    }
    unset($_SESSION['warning_msg']); // Clear message after displaying
}
?>

</body>
</html>
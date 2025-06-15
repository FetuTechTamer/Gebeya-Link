<?php
session_start(); 
include '../components/connect.php';

$success_msg = [];
$warning_msg = [];

if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password_input = sha1($_POST['password']); // Hash input password
    $password_input = filter_var($password_input, FILTER_SANITIZE_STRING);

    // First: check if email exists
    $check_email = $conn->prepare("SELECT * FROM seller WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows === 0) {
        $warning_msg[] = "Seller not registered.";
    } else {
        $seller = $result->fetch_assoc();
        if ($seller['password'] !== $password_input) {
            $warning_msg[] = "Incorrect password.";
        } else {
            // Login success
            setcookie('seller_id', $seller['id'], time() + (60 * 60 * 24 * 30), '/');
            header('Location: dashboard.php');
            exit();
        }
    }

    // Store warning for UI
    $_SESSION['warning_msg'] = $warning_msg;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Login Page</title>
    <link rel="icon" href="../image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

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

<!----- SweetAlert CDN link ----->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/script.js"></script>
 <?php include '../components/alert.php'; ?>

</body>
</html>
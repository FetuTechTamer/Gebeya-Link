<?php
session_start(); 
include 'components/connect.php';

$success_msg = [];
$warning_msg = [];

// Handle the password reset logic
if (isset($_POST['reset'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $new_password = filter_var($_POST['new_password'], FILTER_SANITIZE_STRING); 
    $hashed_new_password = sha1($new_password); 

    // Check if the email exists
    $select_user = $conn->prepare("SELECT * FROM `user` WHERE email = ?");
    $select_user->bind_param("s", $email);
    $select_user->execute();
    $result = $select_user->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $old_password = $row['password'];

        // Check if the new password is the same as the old password
        if ($old_password === $hashed_new_password) {
            $warning_msg[] = "No change made.";
        } else {
            // Update the password in the database
            $update_password = $conn->prepare("UPDATE `user` SET password = ? WHERE email = ?");
            $update_password->bind_param("ss", $hashed_new_password, $email);
            if ($update_password->execute()) {
                $success_msg[] = "Your password has been reset successfully.";
            } else {
                $warning_msg[] = "There was an error updating your password. Please try again.";
            }
        }
    } else {
        $warning_msg[] = "Email does not exist.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="icon" href="image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<div class="form-container">
    <form action="" method="POST" class="register">
        <h3>Reset Password</h3>
        <div class="input-field">
            <p>Your Email<span>*</span></p>
            <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
        </div>

        <div class="input-field">
            <p>New Password<span>*</span></p>
            <input type="password" name="new_password" placeholder="Enter your new password" maxlength="50" required class="box">
        </div>
        
        <input type="submit" name="reset" value="Reset Password" class="btn">
        <p class="link">Remembered your password? <a href="login.php">Login now</a></p>
    </form>
</div>

<!----- SweetAlert CDN link ----->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js"></script>
<?php include 'components/alert.php'; ?>

</body>
</html>
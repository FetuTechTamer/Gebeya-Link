<?php
session_start();
include '../components/connect.php';

$success_msg = [];
$warning_msg = [];

if (isset($_POST['submit'])) {
    $id = unique_id();

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $raw_password = $_POST['password'];
    $confirm_raw_password = $_POST['confirm_password'];

    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = unique_id() . '.' . $ext;
    $image_folder = '../uploaded_files/' . $rename;

    // === VALIDATIONS ===

    // Name validation
    if (!preg_match("/^[a-zA-Z\s\-]{2,50}$/", $name)) {
        $warning_msg[] = 'Name must contain only letters, spaces, or hyphens.';
    }

    // Email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $warning_msg[] = 'Invalid email format.';
    }

    // Check if email already exists
    $check_email = $conn->prepare("SELECT id FROM seller WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();
    if ($check_email->num_rows > 0) {
        $warning_msg[] = 'Email already exists.';
    }

    // Password validations
    if (strlen($raw_password) < 6) {
        $warning_msg[] = 'Password must be at least 6 characters.';
    } elseif (!preg_match("/[A-Z]/", $raw_password)) {
        $warning_msg[] = 'Password must contain at least one uppercase letter.';
    } elseif (!preg_match("/[0-9]/", $raw_password)) {
        $warning_msg[] = 'Password must contain at least one number.';
    } elseif (!preg_match("/[\W_]/", $raw_password)) {
        $warning_msg[] = 'Password must contain at least one special character.';
    }

    // Confirm password
    if ($raw_password !== $confirm_raw_password) {
        $warning_msg[] = 'Passwords do not match.';
    }

    // If no errors, insert into DB
    if (empty($warning_msg)) {
        $hashed_password = sha1($raw_password); // Note: use password_hash() in production

        $insert = $conn->prepare("INSERT INTO seller (id, name, email, password, image) VALUES (?, ?, ?, ?, ?)");
        $insert->bind_param("sssss", $id, $name, $email, $hashed_password, $rename);

        if ($insert->execute()) {
            move_uploaded_file($image_tmp_name, $image_folder);
            $success_msg[] = 'Registration successful. Please log in.';
        } else {
            $warning_msg[] = 'Registration failed. Please try again.';
        }
    }

    $_SESSION['success_msg'] = $success_msg;
    $_SESSION['warning_msg'] = $warning_msg;
    header('Location: register.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Registration Page</title>
    <link rel="icon" href="../image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>



<div class="form-container">
    <form action="" method="POST" enctype="multipart/form-data" class="register" style="transform: none;">
        <h3>Seller Registration Page</h3>
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
        <div class="input-field">
            <p>Your Profile Image<span>*</span></p>
            <input type="file" name="image" accept="image/*" required class="box">
        </div>
        <p class="link">Already have an account?<a href="login.php"> login now</a></p>
        <input type="submit" name="submit" value="Register Now" class="btn">
    </form>
</div>



<!----- SweetAlert CDN link ----->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
    <?php if (!empty($_SESSION['success_msg'])): ?>
        swal("Success!", "<?php echo $_SESSION['success_msg'][0]; ?>", "success");
        <?php unset($_SESSION['success_msg']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['warning_msg'])): ?>
        swal("Warning!", "<?php echo $_SESSION['warning_msg'][0]; ?>", "warning");
        <?php unset($_SESSION['warning_msg']); ?>
    <?php endif; ?>
</script>

</body>
</html>
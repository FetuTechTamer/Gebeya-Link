<?php
include 'components/connect.php';
session_start(); // Start the session

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}

$success_msg = [];
$warning_msg = [];

if (isset($_POST['submit'])) {
    $id = unique_id();
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    $password = sha1($_POST['password']); 
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    $confirm_password = sha1($_POST['confirm_password']); 
    $confirm_password = filter_var($confirm_password, FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);

    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = unique_id() . '.' . $ext;
    $image_folder = 'uploaded_files/' . $rename;

    // Check if the email already exists
    $select_user = $conn->prepare("SELECT * FROM `user` WHERE email = ?");
    $select_user->bind_param("s", $email);
    $select_user->execute();
    $result = $select_user->get_result();

    if ($result->num_rows > 0) {
        $warning_msg[] = 'Email already exists';
    } else {
        if ($password != $confirm_password) {
            $warning_msg[] = 'Confirm password not matched';
        } else {
            $insert_user = $conn->prepare("INSERT INTO `user` (id, name, email, password, image) VALUES (?, ?, ?, ?, ?)");
            $insert_user->bind_param("sssss", $id, $name, $email, $password, $rename);
            if ($insert_user->execute()) {
                move_uploaded_file($image_tmp_name, $image_folder);
                $success_msg[] = 'New user registered! Please log in now.';
            } else {
                $warning_msg[] = 'Failed to register the user.';
            }
        }
    }

    // Store messages in session
    $_SESSION['success_msg'] = $success_msg;
    $_SESSION['warning_msg'] = $warning_msg;

    // Redirect to avoid resubmission
    header('Location: register.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebeya Link - User Registration Page</title>
    <link rel="icon" href="image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'components/user_header.php'; ?>
    <div class="banner"> 
        <div class="detail" style="padding:400px;"> 
            <h1>register</h1> 
            <p>Gebeya Link is dedicated to providing high-quality agricultural products. We focus on sustainable practices <br>and supporting local farmers to ensure fresh and nutritious offerings.<br>
            Our mission is to connect consumers with the best produce while promoting responsible farming.</p> 
            <span><a href="home.php">Home</a> <i class="fa-solid fa-arrow-right"></i> Register</span> 
        </div> 
    </div>
    <div class="form-container">
        <form action="" method="POST" enctype="multipart/form-data" class="register" style="transform: none;">
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
            <div class="input-field">
                <p>Your Profile Image<span>*</span></p>
                <input type="file" name="image" accept="image/*" required class="box">
            </div>
            <p class="link">Already have an account?<a href="login.php"> login now</a></p>
            <input type="submit" name="submit" value="Register Now" class="btn">
        </form>
    </div>

    <?php include 'components/footer.php'; ?>
    
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
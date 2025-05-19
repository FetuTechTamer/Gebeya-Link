<?php
include '../components/connect.php';

if (isset($_COOKIE['seller_id'])) {
    $seller_id = $_COOKIE['seller_id'];

    // Fetch profile data
    $fetch_profile_query = $conn->prepare("SELECT * FROM `seller` WHERE id = ?");
    $fetch_profile_query->bind_param("s", $seller_id);
    $fetch_profile_query->execute();
    $fetch_profile = $fetch_profile_query->get_result()->fetch_assoc(); // Fetch the profile data
} else {
    $seller_id = '';
    header('location:login.php');
    exit(); // Make sure to exit after redirecting
}
if (isset($_POST['submit'])) { 
    $select_seller = $conn->prepare("SELECT * FROM seller WHERE id = ? LIMIT 1"); 
    $select_seller->bind_param("s", $seller_id); // Assuming $seller_id is a string
    $select_seller->execute(); 
    $result = $select_seller->get_result(); 
    $fetch_seller = $result->fetch_assoc(); 

    $prev_pass = $fetch_seller['password']; 
    $prev_image = $fetch_seller['image']; 
    $name = $_POST['name']; 
    $name = filter_var($name, FILTER_SANITIZE_STRING); 

    $email = $_POST['email']; 
    $email = filter_var($email, FILTER_SANITIZE_STRING); 

// Update name
if (!empty($name)) { 
    $update_name = $conn->prepare("UPDATE seller SET name = ? WHERE id = ?"); 
    $update_name->bind_param("ss", $name, $seller_id); // Assuming both are strings
    $update_name->execute(); 
    $success_msg[] = 'Username updated successfully'; 
} 

// Update email 
if (!empty($email)) { 
    $select_email = $conn->prepare("SELECT * FROM seller WHERE id = ? AND email = ?"); 
    $select_email->bind_param("ss", $seller_id, $email); // Assuming both are strings
    $select_email->execute(); 
    $result = $select_email->get_result(); 

    if ($result->num_rows > 0) { 
        $warning_msg[] = 'Email already exists'; 
    } else { 
        $update_email = $conn->prepare("UPDATE seller SET email = ? WHERE id = ?"); 
        $update_email->bind_param("ss", $email, $seller_id); // Assuming both are strings
        $update_email->execute(); 
        $success_msg[] = 'Email updated successfully'; 
    } 
}

// Update image 
$image = $_FILES['image']['name']; 
$image = filter_var($image, FILTER_SANITIZE_STRING); 
$ext = pathinfo($image, PATHINFO_EXTENSION); 
$rename = uniqid() . '.' . $ext; 
$image_size = $_FILES['image']['size']; 
$image_tmp_name = $_FILES['image']['tmp_name']; 
$image_folder = "../uploaded_files/" . $rename; 

if (!empty($image)) { 
    if ($image_size > 2000000) { 
        $warning_msg[] = 'Image size is too large'; 
    } else { 
        $update_image = $conn->prepare("UPDATE seller SET image = ? WHERE id = ?"); 
        $update_image->bind_param("ss", $rename, $seller_id); // Assuming both are strings
        $update_image->execute(); 
        move_uploaded_file($image_tmp_name, $image_folder);

        if ($prev_image != "" AND $prev_image != $rename) { 
            unlink("../uploaded_files/" . $prev_image); 
        } 
        $success_msg[] = 'Image updated successfully';
    } 
}
// Update password 
$empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709'; 

$old_pass = filter_var($_POST['old_pass'], FILTER_SANITIZE_STRING); 
$new_pass = filter_var($_POST['new_pass'], FILTER_SANITIZE_STRING); 
$cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING); 

if (!empty($old_pass) && !empty($new_pass) && !empty($cpass)) {
    // Hash the old password input
    $hashed_old_pass = sha1($old_pass);

    // Verify old password
    if ($hashed_old_pass !== $prev_pass) {
        $warning_msg[] = 'Old password does not match'; 
    } elseif ($new_pass !== $cpass) { 
        $warning_msg[] = 'New password does not match confirmation'; 
    } else { 
        // Hash the new password before updating
        $hashed_new_pass = sha1($new_pass);
        $update_pass = $conn->prepare("UPDATE seller SET password = ? WHERE id = ?"); 
        $update_pass->bind_param("ss", $hashed_new_pass, $seller_id);
        $update_pass->execute(); 
        $success_msg[] = 'Password updated successfully!'; 
    }
} else {
    $warning_msg[] = 'Please fill in all password fields!';
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update profile page</title>
     <link rel="icon" href="../image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="form-container" >
            <div class="heading">
                 <h1 >update profile details</h1>
                <img src="../image/separator.webp">
            </div>
            <form action="" method="post" enctype="multipart/form-data" class="register" > 
                <div class="img-box"> 
                <img src="../uploaded_files/<?= htmlspecialchars($fetch_profile["image"]); ?>" alt="Profile Image"> 
                </div> 
                <h3>Update Profile</h3>
                <div class="flex"> 
                    <div class="col"> 
                        <div class="input-field"> 
                            <p>Your Name <span>*</span></p> 
                            <input type="text" name="name" placeholder="<?=$fetch_profile['name']; ?>" class="box"> 
                        </div> 

                        <div class="input-field"> 
                            <p>Your Email <span>*</span></p> 
                            <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" class="box" > 
                        </div> 

                        <div class="input-field"> 
                            <p>Select Picture <span>*</span></p> 
                            <input type="file" name="image" accept="image/*" class="box"> 
                        </div>
                    </div>
                    <div class="col"> 
                        <div class="input-field"> 
                            <p>Old Password <span>*</span></p> 
                            <input type="password" name="old_pass" placeholder="Enter your old password" class="box" > 
                        </div> 

                        <div class="input-field"> 
                            <p>New Password <span>*</span></p> 
                            <input type="password" name="new_pass" placeholder="Enter your new password" class="box" > 
                        </div> 

                        <div class="input-field"> 
                            <p>Confirm Password <span>*</span></p> 
                            <input type="password" name="cpass" placeholder="Confirm your password" class="box" > 
                        </div> 
                    </div>
                </div>
                  <div class="flex-btn">
                        <input type="submit" name="publish" value="update profile" class="btn">
                        <a href="profile.php" class="btn">Go Back</a> 
                 </div>
            </form>
         

        </section>
    </div>   

    <!----- sweetalert cdn link ----->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>
</html>






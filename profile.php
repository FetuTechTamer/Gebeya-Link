<?php
include 'components/connect.php';
session_start(); // Start the session

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = 'location:login.php';
} 

$success_msg = [];
$warning_msg = [];
 
// Prepare statement for selecting orders
$select_orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
$select_orders->bind_param("s", $user_id); // Bind the parameter
$select_orders->execute(); 
$result_orders = $select_orders->get_result(); // Get the result set
$total_orders = $result_orders->num_rows; // Count the number of rows

// Prepare statement for selecting messages
$select_message = $conn->prepare("SELECT * FROM message WHERE user_id = ?");
$select_message->bind_param("s", $user_id); // Bind the parameter
$select_message->execute(); 
$result_message = $select_message->get_result(); // Get the result set
$total_message = $result_message->num_rows; // Count the number of rows

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>user profile Page</title>
     <link rel="icon" href="image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
 
 <?php include 'components/user_header.php'; ?>
    <div class="banner"> 
        <div class="detail" style="padding:400px;"> 
            <h1>profile</h1> 
            <p>Gebeya Link is dedicated to providing high-quality agricultural products. We focus on sustainable practices <br>and supporting local farmers to ensure fresh and nutritious offerings.<br>
            Our mission is to connect consumers with the best produce while promoting responsible farming.</p> 
            <span><a href="home.php">Home</a> <i class="fa-solid fa-arrow-right"></i> profile </span> 
        </div> 
    </div>
<section class="profile"> 
    <div class="heading"> 
        <h1>Profile Detail</h1> 
        <img src="image/separator.webp" alt="Separator"> 
    </div> 
    <div class="detail"> 
        <div class="user"> 
            <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="Profile Image"> 
            <h3><?= $fetch_profile['name']; ?></h3> 
            <p>User</p> 
            <a href="update.php" class="btn">Update Profile</a> 
        </div> 
        <div class="box-container"> 
            <div class="box"> 
                <div class="flex"> 
                    <i class="fas fa-folder-minus"></i> 
                    <h3><?= $total_orders; ?></h3> 
                </div> 
                <a href="order.php" class="btn">View Orders</a> 
            </div> 
             <div class="box"> 
                <div class="flex"> 
                    <i class="fas fa-folder-chat"></i> 
                    <h3><?= $total_message; ?></h3> 
                </div> 
                <a href="order.php" class="btn">View message</a> 
            </div> 
        </div> 
    </div> 
</section>




 <?php include 'components/footer.php'; ?>
    
    <!----- SweetAlert CDN link ----->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js"></script>
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
<?php
include 'components/connect.php';
session_start(); 

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];

    // Fetch profile data
    $fetch_profile_query = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
    $fetch_profile_query->bind_param("s", $user_id);
    $fetch_profile_query->execute();
    $fetch_profile = $fetch_profile_query->get_result()->fetch_assoc(); // Fetch the profile data
} else {
    header('location:login.php');
    exit(); 
}

$success_msg = [];
$warning_msg = [];

// Handle account deletion
if (isset($_POST['delete_account'])) {
    // Delete all orders associated with the user
    $delete_orders = $conn->prepare("DELETE FROM orders WHERE user_id = ?");
    $delete_orders->bind_param("s", $user_id);
    $delete_orders->execute();

    // Delete the user account
    $delete_user = $conn->prepare("DELETE FROM user WHERE id = ?");
    $delete_user->bind_param("s", $user_id);
    if ($delete_user->execute()) {
        // Clear the user cookie
        setcookie('user_id', '', time() - 3600, '/'); 
        $success_msg[] = "Account deleted successfully.";
        header('Location: login.php'); // Redirect to login after deletion
        exit();
    } else {
        $warning_msg[] = "Failed to delete account. Please try again.";
    }
}

// Prepare statement for selecting orders
$select_orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
$select_orders->bind_param("s", $user_id);
$select_orders->execute(); 
$result_orders = $select_orders->get_result(); 
$total_orders = $result_orders->num_rows; 

// Prepare statement for selecting messages
$select_message = $conn->prepare("SELECT * FROM message WHERE user_id = ?");
$select_message->bind_param("s", $user_id);
$select_message->execute(); 
$result_message = $select_message->get_result(); 
$total_message = $result_message->num_rows; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Page</title>
    <link rel="icon" href="image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .btn-delete:hover {
            background-color: #d32f2f; /* Darker red on hover */
        }
    </style>
</head>
<body>
    <div class="main-container">
        <?php include 'components/user_header.php'; ?>
        <section class="user-profile">
            <div class="heading">
                <h1 style="margin-left:300px;">Profile Details</h1>
                <img src="image/separator.webp" style="margin-left:300px;">
            </div>

            <div class="details" style="transform: translateX(200px);"> 
                <div class="seller"> 
                    <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="Profile Image"> 
                    <h3><?= $fetch_profile['name']; ?></h3> 
                    <p>User</p> 
                    <a href="update.php" class="btn">Update Profile</a> 
                    <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                        <button type="submit" name="delete_account" class="btn btn-delete" style="margin-top:20px;">Delete Account</button>
                    </form>
                </div> 
                <div class="flex"> 
                    <div class="box"> 
                        <i class="fas fa-folder-minus"></i> 
                        <h3><?= $total_orders; ?></h3> 
                        <a href="order.php" class="btn">View Orders</a> 
                    </div> 
                    <div class="box"> 
                        <i class="fas fa-folder-chat"></i> 
                        <h3><?= $total_message; ?></h3> 
                        <a href="contact.php" class="btn">View Messages</a> 
                    </div> 
                </div> 
            </div>
        </section>
    </div> 

    <?php include 'components/footer.php'; ?>
    
    <!----- SweetAlert CDN link ----->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js"></script>
    
    <?php include 'components/alert.php'; // Include existing alert handling ?>
</body>
</html>
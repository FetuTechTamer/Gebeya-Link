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
        :root {
            --main-color: #4CAF50;       
            --white-alpha-25: rgba(255, 255, 255, 0.25);
            --box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.40);
        }

        .user-profile {
            display: flex;
            align-items: center;
            padding: 20px;
            box-shadow: var(--box-shadow);
            border-radius: 8px;
            background-color: white;
        }

        .user {
            flex: 1;
            text-align: center;
            margin-right: 20px;
        }

        .user img {
            width: 180px; /* Larger size */
            height: 180px; /* Consistent size */
            border-radius: 50%; /* Circular profile picture */
            border: 3px solid var(--main-color);
        }

        .user .name {
            font-size: 1.5em;
            font-weight: bold;
            margin-top: 10px;
        }

        .user span {
            display: block;
            margin: 5px 0;
            font-size: 1em;
            color: gray; /* Buyer label color */
        }

        .actions {
            flex: 2;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .btn {
            background-color: var(--main-color);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px; 
            transition: background-color 0.3s, transform 0.3s;
            width: 90%;
        }

        .btn:hover {
            background-color: darkgreen; /* Darker shade on hover */
            transform: scale(1.05);
        }

        .btn-delete {
            background-color: #f44336; /* Red background */
            width: 90%;
        }

        .flex {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .box {
            background-color: var(--white-alpha-25);
            padding: 20px;
            border-radius: 50px;
            box-shadow: var(--box-shadow);
            flex: 1;
            text-align: center;
        }

    </style>
</head>
<body>
    <div class="main-container">
        <?php include 'components/user_header.php'; ?>
        <section class="user-profile">
            <div class="user"> 
                <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="Profile Image"> 
                <h3 class="name"><?= $fetch_profile['name']; ?></h3> 
                <span>Buyer</span>
            </div> 
            <div class="actions"> 
                <a href="update.php" class="btn">Update Profile</a> 
                <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                    <button type="submit" name="delete_account" class="btn btn-delete">Delete Account</button>
                </form>
                <div class="box" style="background-color: var(--white-alpha-25); padding: 15px; border-radius: 10px; box-shadow: var(--box-shadow); width: 90%; text-align: center; margin-bottom: 15px;"> 
                    <span style="color: var(--main-color); display: block; margin-bottom: 0.5rem; font-size: 2rem; text-transform: capitalize;"><?= $total_orders; ?></span>
                    <p style="font-size: 1.5rem; color: #000; padding: .5rem 0; margin-bottom: 1rem;">Total Orders</p> 
                    <a href="order.php" style="background-color: var(--main-color); color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='darkgreen';" onmouseout="this.style.backgroundColor='var(--main-color)';">View Orders</a> 
                </div>
            </div>
        </section>
    </div> 

    <?php include 'components/footer.php'; ?>
    
    <!----- SweetAlert CDN link ----->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js"></script>
    
    <?php include 'components/alert.php'; ?>
</body>
</html>
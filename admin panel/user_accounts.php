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
    header('location:login.php');
    exit(); // Make sure to exit after redirecting
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Accounts</title>
    <link rel="icon" href="../image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="user-container">
            <div class="heading">
                <h1>Registered Users</h1>
                <img src="../image/separator.webp">
            </div>
            <div class="box-container">
                <?php 
                $select_users = $conn->prepare("SELECT * FROM user"); 
                $select_users->execute(); 
                $result_users = $select_users->get_result(); // Get the result set

                if ($result_users->num_rows > 0) { 
                    while ($fetch_users = $result_users->fetch_assoc()) { 
                        $user_id = $fetch_users['id']; 
                ?> 
                <div class="box"> 
                    <img src="../uploaded_files/<?= htmlspecialchars($fetch_users['image']); ?>" alt="User Image"> 
                    <p>User ID: <span><?= htmlspecialchars($user_id); ?></span></p> 
                    <p>User Name: <span><?= htmlspecialchars($fetch_users['name']); ?></span></p> 
                    <p>User Email: <span><?= htmlspecialchars($fetch_users['email']); ?></span></p> 
                </div> 
                <?php 
                    } 
                } else {
                     echo '
                        <div class="empty">
                            <p>No user registered yet!</p>
                        </div>
                         '; 
                }
                ?>
            </div>
        </section>
    </div>   

    <!----- SweetAlert CDN link ----->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>
</html>
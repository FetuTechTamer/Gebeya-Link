<?php
include '../components/connect.php';

if (isset($_COOKIE['seller_id'])) {
    $seller_id = $_COOKIE['seller_id'];

    // Fetch profile data
    $fetch_profile_query = $conn->prepare("SELECT * FROM `seller` WHERE id = ?");
    $fetch_profile_query->bind_param("s", $seller_id);
    $fetch_profile_query->execute();
    $fetch_profile = $fetch_profile_query->get_result()->fetch_assoc(); 
} else {
    header('location:login.php');
    exit(); 
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
    <style>
        .box-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .box {
            flex: 0 0 30%;
            margin: 1%;
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        @media (max-width: 768px) {
            .box {
                flex: 0 0 45%;
            }
        }
        @media (max-width: 480px) {
            .box {
                flex: 0 0 100%;
            }
        }
    </style>
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
                // Fetch users who ordered from the specific seller
                $select_users = $conn->prepare("
                    SELECT u.* 
                    FROM user u 
                    JOIN orders o ON u.id = o.user_id 
                    WHERE o.product_id IN (
                        SELECT id FROM product WHERE seller_id = ?
                    )
                ");
                $select_users->bind_param("s", $seller_id);
                $select_users->execute(); 
                $result_users = $select_users->get_result(); 

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
                            <p>No users have ordered from this seller yet!</p>
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
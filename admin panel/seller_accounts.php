<?php
include '../components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];

    // Fetch profile data
    $fetch_profile_query = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
    $fetch_profile_query->bind_param("s", $user_id);
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
    <title>Seller Accounts</title>
    <link rel="icon" href="../image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Add this CSS for layout */
        .box-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between; /* Distribute space evenly */
        }
        .box {
            flex: 0 0 30%; /* Each box takes up 30% of the width */
            margin: 1%; /* Margin for spacing */
            border: 1px solid #ccc; /* Optional: border for boxes */
            padding: 10px; /* Optional: padding for boxes */
            text-align: center; /* Center align text */
        }
        @media (max-width: 768px) {
            .box {
                flex: 0 0 45%; /* Adjust for smaller screens */
            }
        }
        @media (max-width: 480px) {
            .box {
                flex: 0 0 100%; /* Stack boxes on small screens */
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="user-container">
            <div class="heading">
                <h1>Registered Sellers</h1>
                <img src="../image/separator.webp">
            </div>
            <div class="box-container">
                <?php 
                $select_sellers = $conn->prepare("SELECT * FROM seller"); 
                $select_sellers->execute(); 
                $result_sellers = $select_sellers->get_result(); // Get the result set

                if ($result_sellers->num_rows > 0) { 
                    while ($fetch_sellers = $result_sellers->fetch_assoc()) { 
                        $user_id = $fetch_sellers['id']; 
                ?> 
                <div class="box"> 
                    <img src="../uploaded_files/<?= htmlspecialchars($fetch_sellers['image']); ?>" alt="User Image"> 
                    <p>User ID: <span><?= htmlspecialchars($user_id); ?></span></p> 
                    <p>User Name: <span><?= htmlspecialchars($fetch_sellers['name']); ?></span></p> 
                    <p>User Email: <span><?= htmlspecialchars($fetch_sellers['email']); ?></span></p> 
                </div> 
                <?php 
                    } 
                } else {
                     echo '
                        <div class="empty">
                            <p>No seller registered yet!</p>
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
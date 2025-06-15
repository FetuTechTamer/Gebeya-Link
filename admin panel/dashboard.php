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
    $seller_id = '';
    header('location:login.php');
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebeya Link-Seller Dashboard</title>
    <link rel="icon" href="../image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="dashboard">
            <div class="heading">
                <h1>Dashboard</h1>
                <img src="../image/separator.webp">
            </div>
            <div class="box-container">

                <div class="box">
                    <h3>Welcome!</h3>
                    <p><?= htmlspecialchars($fetch_profile['name']); ?></p>
                    <a href="update.php" class="btn">Update Profile</a>
                </div>
                
                <div class="box">
                    <?php
                    if (isset($_COOKIE['seller_id'])) {
                        $seller_id = filter_var($_COOKIE['seller_id'], FILTER_SANITIZE_STRING);
                        $select_message = $conn->prepare("SELECT * FROM `message` WHERE seller_id = ?");
                        $select_message->bind_param("s", $seller_id);
                        $select_message->execute();
                        $result_messages = $select_message->get_result();
                        $number_of_msg = $result_messages->num_rows;
                    } else {
                        $number_of_msg = 0;
                    }
                    ?>
                    <h3><?= $number_of_msg ?></h3>
                    <p>Your messages</p>
                    <a href="admin_message.php" class="btn">See messages</a>
                </div>

                <div class="box">
                    <?php
                    $select_products = $conn->prepare("SELECT * FROM `product` WHERE seller_id = ?");
                    $select_products->bind_param("s", $seller_id);
                    $select_products->execute();
                    $result_products = $select_products->get_result();
                    $number_of_products = $result_products->num_rows;
                    ?>
                    <h3><?= $number_of_products ?></h3>
                    <p>Products added</p>
                    <a href="add_products.php" class="btn">Add product</a>
                </div>

                <div class="box">
                    <?php
                    $status = 'active';
                    $select_active_products = $conn->prepare("SELECT * FROM `product` WHERE seller_id = ? AND status = ?");
                    $select_active_products->bind_param("ss", $seller_id, $status);
                    $select_active_products->execute();
                    $result_active_products = $select_active_products->get_result();
                    $number_of_active_products = $result_active_products->num_rows;
                    ?>
                    <h3><?= $number_of_active_products ?></h3>
                    <p>Total active products</p>
                    <a href="view_active_products.php" class="btn">View active products</a>
                </div>

                <div class="box">
                    <?php
                    $status = 'deactive';
                    $select_deactive_products = $conn->prepare("SELECT * FROM `product` WHERE seller_id = ? AND status = ?");
                    $select_deactive_products->bind_param("ss", $seller_id, $status);
                    $select_deactive_products->execute();
                    $result_deactive_products = $select_deactive_products->get_result();
                    $number_of_deactive_products = $result_deactive_products->num_rows;
                    ?>
                    <h3><?= $number_of_deactive_products ?></h3>
                    <p>Total deactive products</p>
                    <a href="view_deactive_products.php" class="btn">View deactive products</a>
                </div>

             <div class="box">
    <?php
    // Fetch users who ordered from the specific seller
    $select_users = $conn->prepare("
        SELECT COUNT(DISTINCT u.id) AS user_count
        FROM user u 
        JOIN orders o ON u.id = o.user_id 
        WHERE o.product_id IN (
            SELECT id FROM product WHERE seller_id = ?
        )
    ");
    $select_users->bind_param("s", $seller_id);
    $select_users->execute();
    $result_users = $select_users->get_result();
    $user_data = $result_users->fetch_assoc();
    $number_of_users = $user_data['user_count'];
    ?>
    <h3><?= $number_of_users ?></h3>
    <p>users account</p>
    <a href="user_accounts.php" class="btn">See users</a>
</div>

                <div class="box">
                    <?php
                    $select_sellers = $conn->prepare("SELECT * FROM `seller`");
                    $select_sellers->execute();
                    $result_sellers = $select_sellers->get_result();
                    $number_of_sellers = $result_sellers->num_rows;
                    ?>
                    <h3><?= $number_of_sellers ?></h3>
                    <p>sellers account</p>
                    <a href="seller_accounts.php" class="btn">See sellers</a>
                </div>

                <div class="box">
                    <?php
                    $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ?");
                    $select_orders->bind_param("s", $seller_id);
                    $select_orders->execute();
                    $result_orders = $select_orders->get_result();
                    $number_of_orders = $result_orders->num_rows;
                    ?>
                    <h3><?= $number_of_orders ?></h3>
                    <p>Total orders </p>
                    <a href="total_admin_order.php" class="btn">Total Orders</a>
                </div>

                <div class="box">
                    <?php
                    $status = 'in progress';
                    $select_confirm_orders = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ? AND status=?");
                    $select_confirm_orders->bind_param("ss", $seller_id, $status);
                    $select_confirm_orders->execute();
                    $result_confirm_orders = $select_confirm_orders->get_result();
                    $number_of_confirm_orders = $result_confirm_orders->num_rows;
                    ?>
                    <h3><?= $number_of_confirm_orders ?></h3>
                    <p>Total confirm orders </p>
                    <a href="admin_order.php" class="btn">confirm_orders</a>
                </div>

                <div class="box">
                    <?php
                    $status = 'canceled';
                    $select_canceled_orders = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ? AND status=?");
                    $select_canceled_orders->bind_param("ss", $seller_id, $status);
                    $select_canceled_orders->execute();
                    $result_canceled_orders = $select_canceled_orders->get_result();
                    $number_of_canceled_orders = $result_canceled_orders->num_rows;
                    ?>
                    <h3><?= $number_of_canceled_orders ?></h3>
                    <p>Total canceled orders </p>
                    <a href="canceled_admin_order.php" class="btn">canceled_orders</a>
                </div>

            </div>
        </section>
    </div>   

    <!----- sweetalert cdn link ----->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>
</html>
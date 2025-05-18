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

// Delete message from database 
if (isset($_POST['delete_msg'])) { 
    $delete_id = $_POST['delete_id']; 
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING); 

    // Verify if the message exists
    $verify_delete = $conn->prepare("SELECT * FROM message WHERE id = ?"); 
    $verify_delete->bind_param("s", $delete_id); // Bind the parameter
    $verify_delete->execute(); 
    $result = $verify_delete->get_result(); 

    if ($result->num_rows > 0) { 
        // Delete the message
        $delete_msg = $conn->prepare("DELETE FROM message WHERE id = ?"); 
        $delete_msg->bind_param("s", $delete_id); // Bind the parameter
        $delete_msg->execute(); 
        $success_msg[] = 'Message deleted successfully'; 
    } else { 
        $warning_msg[] = "Message already deleted"; 
    }
}

// Update order in the database
if (isset($_POST['update_order'])) { 
    $order_id = $_POST['order_id']; 
    $order_id = filter_var($order_id, FILTER_SANITIZE_STRING); 
    $update_payment = $_POST['update_payment']; 
    $update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING); 
    
    // Prepare the update statement
    $update_pay = $conn->prepare("UPDATE orders SET payment_status = ? WHERE id = ?"); 
    $update_pay->bind_param("ss", $update_payment, $order_id); // Bind parameters
    $update_pay->execute(); 

    if ($update_pay->affected_rows > 0) {
        $success_msg[] = 'Order payment status updated successfully';
    } else {
        $success_msg[] = 'No changes made to the order status';
    }
}

// Delete order
if (isset($_POST['delete_order'])) { 
    $delete_id = $_POST['order_id']; 
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING); 
    
    // Verify if the order exists
    $verify_delete = $conn->prepare("SELECT * FROM orders WHERE id = ?"); 
    $verify_delete->bind_param("s", $delete_id); // Bind the parameter
    $verify_delete->execute(); 
    $result = $verify_delete->get_result(); 

    if ($result->num_rows > 0) { 
        // Delete the order
        $delete_order = $conn->prepare("DELETE FROM orders WHERE id = ?"); 
        $delete_order->bind_param("s", $delete_id); // Bind the parameter
        $delete_order->execute(); 
        $success_msg[] = 'Order deleted successfully'; 
    } else { 
        $warning_msg[] = 'Order already deleted'; 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
     <link rel="icon" href="../image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="order-container">
            <div class="heading">
                <h1>Total Order Placed</h1>
                <img src="../image/separator.webp">
            </div>
            <div class="box-container">
                <?php 
$select_order = $conn->prepare("SELECT * FROM orders WHERE seller_id = ?"); 
$select_order->bind_param("s", $seller_id); // Bind the parameter
$select_order->execute(); 
$result = $select_order->get_result(); 

if ($result->num_rows > 0) { 
    while ($fetch_order = $result->fetch_assoc()) { 
?> 
<div class="box"> 
    <div class="status" style="color: <?php echo ($fetch_order['status'] == 'in progress') ? 'limegreen' : 'red'; ?>">
            <?= $fetch_order['status']; ?>
        </div> 
    <div class="details">
        <p>User Name: <span><?= $fetch_order['name']; ?></span></p> 
        <p>User ID: <span><?= $fetch_order['user_id']; ?></span></p> 
        <p>Placed on: <span><?= $fetch_order['date']; ?></span></p> 
        <p>User Number: <span><?= $fetch_order['number']; ?></span></p> 
        <p>User Email: <span><?= $fetch_order['email']; ?></span></p> 
        <p>Total Price: <span><?= $fetch_order['price']; ?></span></p> 
        <p>Payment Method: <span><?= $fetch_order['method']; ?></span></p> 
        <p>User Address: <span><?= $fetch_order['address']; ?></span></p> 
    </div>
    <form action="" method="post"> 
        <input type="hidden" name="order_id" value="<?= $fetch_order['id']; ?>"> 
        <select name="update_payment" class="box" style="width: 90%;"> 
            <option disabled selected><?= $fetch_order['payment_status']; ?></option> 
            <option value="pending">Pending</option> 
            <option value="order delivered">Order Delivered</option> 
        </select> 
        <div class="flex-btn"> 
            <input type="submit" name="update_order" value="Update Payment" class="btn"> 
            <input type="submit" name="delete_order" value="Delete Order" class="btn" onclick="return confirm(delete this order?)"> 
        </div> 
    </form>
    </div> 
    <?php 
        } 
    } else { 
        echo '
        <div class="empty">
            <p>No order placed yet!</p>
        </div>
            '; 
        }
    ?>
            </div>
        </section>
    </div>   

    <!----- sweetalert cdn link ----->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>
</html>

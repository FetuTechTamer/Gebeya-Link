<?php
session_start(); 
include '../components/connect.php';

// Retrieve messages from session
$success_msg = $_SESSION['success_msg'] ?? [];
$warning_msg = $_SESSION['warning_msg'] ?? [];
unset($_SESSION['success_msg'], $_SESSION['warning_msg']);

if (isset($_COOKIE['seller_id'])) {
    $seller_id = $_COOKIE['seller_id'];

    $fetch_profile_query = $conn->prepare("SELECT * FROM `seller` WHERE id = ?");
    $fetch_profile_query->bind_param("s", $seller_id);
    $fetch_profile_query->execute();
    $fetch_profile = $fetch_profile_query->get_result()->fetch_assoc();
} else {
    header('location:login.php');
    exit();
}

if (isset($_POST['delete_msg'])) { 
    $delete_id = filter_var($_POST['delete_id'], FILTER_SANITIZE_STRING); 

    // Verify message exists and belongs to seller
    $verify_delete = $conn->prepare("SELECT * FROM message WHERE id = ? AND seller_id = ?"); 
    $verify_delete->bind_param("ss", $delete_id, $seller_id);
    $verify_delete->execute(); 
    $result = $verify_delete->get_result();

    if ($result->num_rows > 0) { 
        $delete_msg = $conn->prepare("DELETE FROM message WHERE id = ?"); 
        $delete_msg->bind_param("s", $delete_id);
        if ($delete_msg->execute()) {
            $_SESSION['success_msg'][] = 'Message deleted successfully';
        } else {
            $_SESSION['warning_msg'][] = 'Failed to delete message';
        }
    } else { 
        $_SESSION['warning_msg'][] = "Message not found or unauthorized";
    }
    
    header("Location: ".$_SERVER['PHP_SELF']); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="icon" href="../image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
       <section class="message-container">
    <div class="heading">
        <h1>All Messages</h1>
        <img src="../image/separator.webp">
    </div>

    <div class="box-container">
    <?php 
    // Fetch only messages for the logged-in seller
    $select_message = $conn->prepare("SELECT * FROM message WHERE seller_id = ? ORDER BY id DESC");
    $select_message->bind_param("s", $seller_id);
    $select_message->execute();
    $result = $select_message->get_result();
    $message_count = $result->num_rows;

    $i = 0;
    if ($message_count > 0) {
        while ($fetch_message = $result->fetch_assoc()) {
            if ($i % 2 == 0) echo '<div class="row">';
    ?>
        <div class="box" style="width: 500px; margin: 10px;"> 
            <h3 class="name"><?= htmlspecialchars($fetch_message['name']); ?></h3> 
            <h4>Email: <?= htmlspecialchars($fetch_message['email']); ?></h4> 
            <h4>Subject: <?= htmlspecialchars($fetch_message['subject']); ?></h4> 
            <p>Message: <?= htmlspecialchars($fetch_message['message']); ?></p> 
            <form action="" method="post"> 
                <input type="hidden" name="delete_id" value="<?= htmlspecialchars($fetch_message['id']); ?>"> 
                <input type="submit" name="delete_msg" value="Delete Message" class="btn" 
                onclick="return confirm('Are you sure you want to delete this message?');"> 
            </form> 
        </div>
    <?php 
            $i++;
            if ($i % 2 == 0) echo '</div>';
        }
        if ($i % 2 != 0) echo '</div>';
    } else { 
        echo '
        <div class="empty">
            <p>No messages found for your account.</p>
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
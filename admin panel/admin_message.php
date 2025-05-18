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
                <h1>Unread Message</h1>
                <img src="../image/separator.webp">
            </div>
            <div class="box-container">
                <?php 
                $select_message = $conn->prepare("SELECT * FROM message"); 
                $select_message->execute(); 
                $result = $select_message->get_result(); // Get the result set

                if ($result->num_rows > 0) { 
                    while ($fetch_message = $result->fetch_assoc()) { 
                ?> 
                <div class="box"> 
                    <h3 class="name"><?= $fetch_message['name']; ?></h3> 
                    <h4><?= $fetch_message['subject']; ?></h4> 
                    <p><?= $fetch_message['message']; ?></p> 
                    <form action="" method="post"> 
                        <input type="hidden" name="delete_id" value="<?= $fetch_message['id']; ?>"> 
                        <input type="submit" name="delete_msg" value="Delete Message" class="btn" 
                        onclick="return confirm('Are you sure you want to delete this message?');"> 
                    </form> 
                </div> 
                <?php 
                    } 
                    } else { 
                        echo '
                        <div class="empty">
                            <p>No unread messages yet!</p>
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


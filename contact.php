<?php
include 'components/connect.php';
session_start(); // Start the session

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    header('Location: login.php');
    exit;
} 

$success_msg = [];
$warning_msg = [];
  
// Sending message 
if (isset($_POST['send_message'])) { 
    if ($user_id != '') { 
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING); 
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); 
        $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING); 
        $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING); 
        $seller_id = filter_var($_POST['seller_id'], FILTER_SANITIZE_STRING); // Get the seller ID from the form

        // Verify if the message already exists
        $verify_message = $conn->prepare("SELECT * FROM message WHERE user_id = ? AND email = ? AND subject = ? AND message = ? AND seller_id = ?"); 
        $verify_message->bind_param("issss", $user_id, $email, $subject, $message, $seller_id); 
        $verify_message->execute(); 
        $verify_message->store_result(); // Store the result to get num_rows

        if ($verify_message->num_rows > 0) { 
            $warning_msg[] = 'Message already exists'; 
        } else { 
            // Insert the message into the database
            $insert_message = $conn->prepare("INSERT INTO message (user_id, name, email, subject, message, seller_id) VALUES (?, ?, ?, ?, ?, ?)"); 
            $insert_message->bind_param("isssss", $user_id, $name, $email, $subject, $message, $seller_id); 
            $insert_message->execute(); 
            $success_msg[] = 'Message sent successfully'; 
        } 
    } else { 
        $warning_msg[] = 'Please log in first'; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us Page</title>
    <link rel="icon" href="image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   <style>
        .banner .detail {
        padding: 50px;
    }

    @media (min-width: 1024px) { 
        .banner .detail {
            padding: 400px; 
        }
    }
    </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>
 <div class="banner"> 
        <div class="detail" > 
            <h1>contact us</h1> 
            <p>Gebeya Link is dedicated to providing high-quality agricultural products. We focus on sustainable practices <br>and supporting local farmers to ensure fresh and nutritious offerings.<br>
            Our mission is to connect consumers with the best produce while promoting responsible farming.</p> 
            <span><a href="home.php">Home</a> <i class="fa-solid fa-arrow-right"></i> contact us Now</span> 
        </div> 
    </div>
    <div class="services"> 
        <div class="heading"> 
            <h1>Our Services</h1> 
            <p>Just a few clicks to easily reserve high-quality agricultural products online, saving you time and ensuring fresh produce delivered right to your door.</p> 
            <img src="image/separator.webp" alt="Separator"> 
        </div> 
        <div class="box-container"> 
            <div class="box"> 
                <img src="image/free Shipping.png" alt="Free Shipping" class="img-small"> 
                <div> 
                    <h1>Free Shipping Fast</h1> 
                    <p>Enjoy quick and reliable shipping on all your agricultural product orders.</p> 
                </div> 
            </div> 
            <div class="box" style="min-height: 42vh; "> 
                <img src="image/Money Back Guarantee.png" alt="Money Back Guarantee" class="img-small"> 
                <div> 
                    <h1 >Money Back Guarantee</h1> 
                    <p>We ensure the quality of our agricultural products with a money-back guarantee for your peace of mind.</p> 
                </div> 
            </div> 

            <div class="box"> 
                <img src="image/Online Support.png" alt="Online Support" class="img-small"> 
                <div> 
                    <h1>Online Support 24/7</h1> 
                    <p>Our team is available around the clock to assist you with any inquiries about our agricultural products.</p> 
                </div> 
            </div>
        </div> 
    </div>

    <div class="form-container"> 
        <div class="heading">
            <h1>drop us a line </h1>
            <p>Just a few clicks to easily reserve high-quality agricultural products online, saving you time and ensuring fresh produce delivered right to your door.</p> 
            <img src="image/separator.webp" alt="Separator"> 
        </div>
    <form action="" method="post" class="register"> 
        <div class="input-field"> 
            <label>Name <sup>*</sup></label> 
            <input type="text" name="name" required placeholder="Enter your name" class="box"> 
        </div> 
        <div class="input-field"> 
            <label>Email <sup>*</sup></label> 
            <input type="email" name="email" required placeholder="Enter your email" class="box"> 
        </div> 
        <div class="input-field"> 
            <label>Subject <sup>*</sup></label> 
            <input type="text" name="subject" required placeholder="Reason..." class="box"> 
        </div> 
        <div class="input-field"> 
            <label>Seller id <sup>*</sup></label> 
            <input type="text" name="seller_id" required placeholder="Enter seller id" class="box"> 
        </div> 
        <div class="input-field"> 
            <label>Comment <sup>*</sup></label> 
            <textarea name="message" cols="38" rows="10" required placeholder="Enter your comments..." class="box"></textarea> 
        </div> 
        <button type="submit" name="send_message" class="btn">Send Message</button> 
    </form> 
</div>


<?php include 'components/footer.php'; ?>

<!----- SweetAlert CDN link ----->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js"></script>
 <?php include 'components/alert.php'; ?>
</body>
</html>
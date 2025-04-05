<?php
include '../components/connect.php';

if (isset($_COOKIE['seller_id'])) {
    $seller_id=$_COOKIE['seller_id'];
    }
    else{
        $seller_id='';
        header('location:login.php')
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Login Page</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="main-container">
      <?php include '../components/admin_header.php'; ?>
    </div>   


<!----- sweetalert cdn link ----->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script src='../js/script.js'></script>
<?php include '../components/alert.php'; ?>

</body>
</html>
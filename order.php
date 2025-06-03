<?php
include 'components/connect.php';
session_start();

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    header('Location: login.php');
    exit;
}

// Handle account deletion
if (isset($_POST['delete_account'])) {
    $delete_orders = $conn->prepare("DELETE FROM orders WHERE user_id = ?");
    $delete_orders->bind_param("s", $user_id);
    $delete_orders->execute();

    $delete_user = $conn->prepare("DELETE FROM user WHERE id = ?");
    $delete_user->bind_param("s", $user_id);
    if ($delete_user->execute()) {
        setcookie('user_id', '', time() - 3600, '/');
        $_SESSION['success_msg'] = "Account deleted successfully.";
        header('Location: login.php');
        exit;
    } else {
        $_SESSION['warning_msg'] = "Failed to delete account. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Gebeya Link - Orders</title>
   <link rel="icon" href="image/favicon.ico" type="image/png">
   <link rel="stylesheet" href="css/user_style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="banner"> 
   <div class="detail" style="padding:400px;"> 
      <h1>My Orders</h1> 
      <p>
         Gebeya Link is dedicated to providing high-quality agricultural products. We focus on sustainable practices
         and supporting local farmers to ensure fresh and nutritious offerings.<br>
         Our mission is to connect consumers with the best produce while promoting responsible farming.
      </p> 
      <span><a href="home.php">Home</a> <i class="fas fa-arrow-right"></i> My Orders</span> 
   </div> 
</div>

<div class="orders"> 
   <div class="heading"> 
      <h1>My Orders</h1> 
      <img src="image/separator.webp" alt="Separator"> 
   </div> 

   <div class="box-container"> 
      <?php 
      // Step 1: Fetch all orders
      $select_orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY date DESC");
      $select_orders->bind_param("s", $user_id);
      $select_orders->execute();
      $orders_result = $select_orders->get_result();

      // Step 2: Group by product_id
      $grouped_orders = [];

      while ($order = $orders_result->fetch_assoc()) {
          $product_id = $order['product_id'];

          // Ensure product exists
          $select_product = $conn->prepare("SELECT * FROM product WHERE id = ? LIMIT 1");
          $select_product->bind_param("s", $product_id);
          $select_product->execute();
          $product_result = $select_product->get_result();

          if ($product_result->num_rows === 0) {
              continue; // Skip deleted products
          }

          $product = $product_result->fetch_assoc();

          if (!isset($grouped_orders[$product_id])) {
              $grouped_orders[$product_id] = [
                  'product' => $product,
                  'total_quantity' => 0,
                  'latest_order' => $order,
              ];
          }

          $grouped_orders[$product_id]['total_quantity'] += $order['quantity'];
      }

      // Step 3: Display grouped orders
      if (count($grouped_orders) > 0):
          foreach ($grouped_orders as $product_id => $data):
              $product = $data['product'];
              $quantity = $data['total_quantity'];
              $order = $data['latest_order']; // use latest order info
      ?>
      <div class="box" style="<?= $order['status'] === 'canceled' ? 'border:2px solid red;' : '' ?>"> 
         <a href="view_order.php?get_id=<?= $order['id']; ?>">
            <img src="uploaded_files/<?= htmlspecialchars($product['image']) ?>" class="image" alt="Product Image"> 
            <p class="date"><i class="fas fa-calendar-alt"></i> <?= htmlspecialchars($order["date"]) ?></p> 
            <div class="content"> 
               <div class="row"> 
                  <h3 class="name"><?= htmlspecialchars($product['name']) ?></h3> 
                  <p class="price">Price: $<?= htmlspecialchars($product['price']) ?> × <?= $quantity ?> = $<?= number_format($product['price'] * $quantity, 2) ?>/-</p> 
                  <p class="status" style="color: 
                     <?php 
                     switch ($order['status']) {
                         case 'delivered': echo 'green'; break;
                         case 'canceled': echo 'red'; break;
                         case 'in progress': echo 'orange'; break;
                         default: echo 'orange'; break;
                     } 
                     ?>">
                     <?= htmlspecialchars($order['status']) ?>
                  </p> 
               </div> 
            </div> 
         </a> 
      </div>
      <?php endforeach; ?>
      <?php else: ?>
         <p class="empty">No orders have been placed yet!</p>
      <?php endif; ?>
   </div> 
</div>

<?php include 'components/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js"></script>

<?php if (isset($_SESSION['success_msg'])): ?>
<script>
    swal("Success", "<?= $_SESSION['success_msg']; ?>", "success");
</script>
<?php unset($_SESSION['success_msg']); endif; ?>

<?php if (isset($_SESSION['warning_msg'])): ?>
<script>
    swal("Warning", "<?= $_SESSION['warning_msg']; ?>", "warning");
</script>
<?php unset($_SESSION['warning_msg']); endif; ?>

<?php include 'components/alert.php'; ?>
</body>
</html>

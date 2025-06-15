<?php
include 'components/connect.php';
session_start();

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    header('Location: login.php');
    exit;
}

$success_msg = [];
$warning_msg = [];

if (isset($_POST['place_order'])) {
    // Sanitize input data
    $name   = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $email  = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    // Validate name
    if (!preg_match("/^[a-zA-Z\s\-]{2,50}$/", $name)) {
        $warning_msg[] = "Invalid name. Use only letters, spaces, or hyphens .";
    }

    // Validate phone number format (Ethiopian mobile)
    if (!preg_match('/^(\+2519[0-9]{8}|09[0-9]{8})$/', $number)) {
        $warning_msg[] = "Invalid phone number format. Use +2519xxxxxxxx or 09xxxxxxxx.";
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $warning_msg[] = "Invalid email address format.";
    }

    // Only proceed if no validation errors
    if (empty($warning_msg)) {
        $address = filter_var($_POST['flat'] . ", " . $_POST['street'] . ", " . $_POST['city'] . ", " . $_POST['country'] . ", " . $_POST['pin'], FILTER_SANITIZE_STRING);
        $address_type = filter_var($_POST['address_type'], FILTER_SANITIZE_STRING);
        $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
        $status = 'in progress';

        // Handle single product order
        if (isset($_GET['get_id']) && isset($_GET['quantity'])) {
            $get_id = filter_input(INPUT_GET, 'get_id', FILTER_SANITIZE_STRING);
            $quantity = intval($_GET['quantity']);

            $get_product = $conn->prepare("SELECT * FROM product WHERE id = ? LIMIT 1");
            $get_product->bind_param("s", $get_id);
            $get_product->execute();
            $result = $get_product->get_result();

            if ($result->num_rows > 0) {
                $fetch_p = $result->fetch_assoc();
                $seller_id = $fetch_p['seller_id'];
                $order_id = uniqid();
                $product_id = $fetch_p['id'];
                $price = (float)$fetch_p['price'];
                $total_amount = $price * $quantity;

                $insert_order = $conn->prepare("INSERT INTO orders (id, user_id, seller_id, name, number, email, address, address_type, method, product_id, price, quantity, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insert_order->bind_param("ssssssssssiis", $order_id, $user_id, $seller_id, $name, $number, $email, $address, $address_type, $method, $product_id, $total_amount, $quantity, $status);
                $insert_order->execute();
                $insert_order->close();

                $_SESSION['success_msg'] = 'Your order has been placed successfully.';
                header("Location: order.php");
                exit;
            } else {
                $warning_msg[] = 'Something went wrong. Product not found.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout Page</title>
    <link rel="icon" href="image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="banner">
    <div class="detail" style="padding:400px;">
        <h1>Checkout</h1>
        <p>Gebeya Link is dedicated to providing high-quality agricultural products...</p>
        <span><a href="home.php">Home</a> <i class="fa-solid fa-arrow-right"></i> Checkout Now</span>
    </div>
</div>

<div class="checkout">
    <div class="heading">
        <h1>Checkout Summary</h1>
        <img src="image/separator.webp" alt="Separator">
    </div>

    <!-- Display warnings -->
    <?php if (!empty($warning_msg)): ?>
        <div class="alert warning">
            <?php foreach ($warning_msg as $msg): ?>
                <p><?= htmlspecialchars($msg) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <form action="" method="post" class="register">
            <input type="hidden" name="p_id" value="<?= isset($get_id) ? $get_id : ''; ?>">
            <h3>Billing Details</h3>

            <div class="flex">
                <div class="box">
                    <div class="input-field">
                        <p>Your Name <span>*</span></p>
                        <input type="text" name="name" maxlength="50" required placeholder="e.g. Abebe Kebede" class="input">
                    </div>

                    <div class="input-field">
                        <p>Your Phone Number <span>*</span></p>
                        <input type="text" name="number" required maxlength="13" placeholder="e.g. +251911223344 or 0911223344" class="input">
                    </div>

                    <div class="input-field">
                        <p>Your Email <span>*</span></p>
                        <input type="email" name="email" required maxlength="50" placeholder="e.g. you@example.com" class="input">
                    </div>

                    <div class="input-field">
                        <p>Payment Method <span>*</span></p>
                        <select name="method" class="input">
                            <option value="cash on delivery">Cash on Delivery</option>
                            <option value="credit or debit card">Credit or Debit Card</option>
                            <option value="net banking">Mobile Banking</option>
                            <option value="UPI or RuPay">TeleBirr</option>
                        </select>
                    </div>

                    <div class="input-field">
                        <p>Address Type <span>*</span></p>
                        <select name="address_type" class="input">
                            <option value="home">Home</option>
                            <option value="office">Office</option>
                        </select>
                    </div>
                </div>

                <div class="input">
                    <div class="input-field">
                        <p>Address Line 01 <span>*</span></p>
                        <input type="text" name="flat" required maxlength="50" placeholder="e.g. flat or building name" class="input">
                    </div>
                    <div class="input-field">
                        <p>Address Line 02 <span>*</span></p>
                        <input type="text" name="street" required maxlength="50" placeholder="e.g. street name" class="input">
                    </div>
                    <div class="input-field">
                        <p>City Name <span>*</span></p>
                        <input type="text" name="city" required maxlength="50" placeholder="e.g. Addis Ababa" class="input">
                    </div>
                    <div class="input-field">
                        <p>Country Name <span>*</span></p>
                        <input type="text" name="country" required maxlength="50" placeholder="e.g. Ethiopia" class="input">
                    </div>
                    <div class="input-field">
                        <p>Pincode <span>*</span></p>
                        <input type="text" name="pin" required maxlength="10" placeholder="e.g. 1000" class="input">
                    </div>
                </div>
            </div>

            <button type="submit" name="place_order" class="btn">Place Order</button>
        </form>

        <div class="summary"> 
            <h3>My Bag</h3> 
            <div class="box-container"> 
                <?php 
                $grand_total = 0; 
                if (isset($_GET['get_id']) && isset($_GET['quantity'])) { 
                    $get_id = $_GET['get_id'];
                    $quantity = intval($_GET['quantity']); // Retrieve quantity from URL
                    $select_get = $conn->prepare("SELECT * FROM product WHERE id = ?"); 
                    $select_get->bind_param("s", $get_id); 
                    $select_get->execute(); 
                    $result = $select_get->get_result(); 

                    while ($fetch_get = $result->fetch_assoc()) { 
                        $sub_total = $fetch_get['price'] * $quantity; 
                        $grand_total += $sub_total; 
                        ?> 
                        <div class="flex"> 
                            <img src="uploaded_files/<?= htmlspecialchars($fetch_get['image']); ?>" class="image"> 
                            <div> 
                                <h3 class="name"><?= htmlspecialchars($fetch_get['name']); ?></h3> 
                                <p class="price"><?= number_format($fetch_get['price'], 2); ?> X <?= $quantity; ?> birr</p> 
                            </div> 
                        </div> 
                        <?php 
                    } 
                } else { 
                    $select_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?"); 
                    $select_cart->bind_param("s", $user_id); 
                    $select_cart->execute(); 
                    $cart_result = $select_cart->get_result(); 

                    if ($cart_result->num_rows > 0) { 
                        while ($fetch_cart = $cart_result->fetch_assoc()) { 
                            $select_products = $conn->prepare("SELECT * FROM product WHERE id = ?"); 
                            $select_products->bind_param("s", $fetch_cart['product_id']); 
                            $select_products->execute(); 
                            $product_result = $select_products->get_result(); 
                            $fetch_products = $product_result->fetch_assoc(); 

                            if ($fetch_products) { // Check if product fetched successfully
                                $sub_total = $fetch_cart['quantity'] * $fetch_products['price']; 
                                $grand_total += $sub_total; 
                                ?>
                                <div class="flex"> 
                                    <img src="uploaded_files/<?= htmlspecialchars($fetch_products['image']); ?>" class="image"> 
                                    <div> 
                                        <h3 class="name"><?= htmlspecialchars($fetch_products['name']); ?></h3> 
                                        <p class="price"><?= number_format($fetch_products['price'], 2); ?> X <?= $fetch_cart['quantity']; ?> birr</p> 
                                    </div> 
                                </div>
                                <?php
                            }
                        }
                    } else {
                        echo '<p class="empty">Your cart is empty!</p>';
                    }
                }
                ?>
            </div> 
            <div class="grand-total">
                <span>Total Amount Payable:</span>
                <p><?= number_format($grand_total, 2); ?> birr</p> 
            </div>
        </div>
    </div> 
</div>

<?php include 'components/footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js"></script>
<?php include 'components/alert.php'; ?>
</body>
</html>

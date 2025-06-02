<?php
include 'components/connect.php';
session_start();

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
    header('Location: login.php');
    exit;
}

$success_msg = [];
$warning_msg = [];

if (isset($_POST['place_order'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    $address = $_POST['flat'] . ", " . $_POST['street'] . ", " . $_POST['city'] . ", " . $_POST['country'] . ", " . $_POST['pin'];
    $address = filter_var($address, FILTER_SANITIZE_STRING);

    $address_type = filter_var($_POST['address_type'], FILTER_SANITIZE_STRING);
    $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);

    // Check single product order
    if (isset($_GET['get_id'])) {
        $get_id = $_GET['get_id'];
        $get_product = $conn->prepare("SELECT * FROM product WHERE id = ? LIMIT 1");
        $get_product->bind_param("i", $get_id);
        $get_product->execute();
        $result = $get_product->get_result();

        if ($result->num_rows > 0) {
            $fetch_p = $result->fetch_assoc();
            $seller_id = $fetch_p['seller_id'];
            $order_id = uniqid();

            $insert_order = $conn->prepare("INSERT INTO orders (id, user_id, seller_id, name, number, email, address, address_type, method, product_id, price, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_order->bind_param("siisssssssdi", $order_id, $user_id, $seller_id, $name, $number, $email, $address, $address_type, $method, $fetch_p['id'], $fetch_p['price'], $quantity);
            $quantity = 1;
            $insert_order->execute();
            
            $insert_order->close();
         

            header("Location: order.php");
            exit;
        } else {
            $warning_msg[] = 'Something went wrong. Product not found.';
        }
    } else {
        // Multi-product (cart) order
        $verify_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $verify_cart->bind_param("i", $user_id);
        $verify_cart->execute();
        $cart_result = $verify_cart->get_result();

        if ($cart_result->num_rows > 0) {
            while ($f_cart = $cart_result->fetch_assoc()) {
                $product_id = $f_cart['product_id'];
                $quantity = $f_cart['quantity'];
                $price = $f_cart['price'];

                $s_products = $conn->prepare("SELECT * FROM product WHERE id = ? LIMIT 1");
                $s_products->bind_param("i", $product_id);
                $s_products->execute();
                $product_result = $s_products->get_result();

                if ($product_result->num_rows > 0) {
                    $f_product = $product_result->fetch_assoc();
                    $seller_id = $f_product['seller_id'];
                    $order_id = uniqid();

                    $insert_order = $conn->prepare("INSERT INTO orders (id, user_id, seller_id, name, number, email, address, address_type, method, product_id, price, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $insert_order->bind_param("ssssssssssii", $order_id, $user_id, $seller_id, $name, $number, $email, $address, $address_type, $method, $product_id, $price, $quantity);
                    $insert_order->execute();
                    $insert_order->close();
                }
                $s_products->close();
            }

            // Clear the cart after order
            $delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $delete_cart->bind_param("i", $user_id);
            $delete_cart->execute();
            $delete_cart->close();

            header('Location: order.php');
            exit;
        } else {
            $warning_msg[] = 'Your cart is empty!';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <p>Gebeya Link is dedicated to providing high-quality agricultural products. We focus on sustainable practices <br>and supporting local farmers to ensure fresh and nutritious offerings.<br>
        Our mission is to connect consumers with the best produce while promoting responsible farming.</p> 
        <span><a href="home.php">Home</a> <i class="fa-solid fa-arrow-right"></i> Checkout Now</span> 
    </div> 
</div>

<div class="checkout"> 
    <div class="heading"> 
        <h1>Checkout Summary</h1> 
        <img src="image/separator.webp" alt="Separator"> 
    </div> 
    <div class="row"> 
        <form action="" method="post" class="register"> 
            <input type="hidden" name="p_id" value="<?= isset($get_id) ? $get_id : ''; ?>"> 
            <h3>Billing Details</h3> 
            <div class="flex"> 
                <div class="box"> 
                    <div class="input-field"> 
                        <p>Your Name <span>*</span></p> 
                        <input type="text" name="name" maxlength="50" required placeholder="Enter your name" class="input"> 
                    </div> 
                    <div class="input-field"> 
                        <p>Your Number <span>*</span></p> 
                        <input type="number" name="number" required maxlength="10" placeholder="Enter your number" class="input"> 
                    </div> 
                    <div class="input-field"> 
                        <p>Your Email <span>*</span></p> 
                        <input type="email" name="email" required maxlength="50" placeholder="Enter your email" class="input"> 
                    </div> 
                    <div class="input-field"> 
                        <p>Payment Method <span>*</span></p> 
                        <select name="method" class="input"> 
                            <option value="cash on delivery">Cash on Delivery</option> 
                            <option value="credit or debit card">Credit or Debit Card</option> 
                            <option value="net banking">Net Banking</option> 
                            <option value="UPI or RuPay">UPI or RuPay</option> 
                            <option value="Paytm">Paytm</option> 
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
                        <input type="text" name="city" required maxlength="50" placeholder="e.g. city name" class="input">
                    </div>
                    <div class="input-field">
                        <p>Country Name <span>*</span></p>
                        <input type="text" name="country" required maxlength="50" placeholder="e.g. country name" class="input">
                    </div>
                    <div class="input-field">
                        <p>Pincode <span>*</span></p>
                        <input type="text" name="pin" required maxlength="50" placeholder="e.g. 101011" class="input">
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
                if (isset($_GET['get_id'])) { 
                    $get_id = $_GET['get_id'];
                    $select_get = $conn->prepare("SELECT * FROM product WHERE id = ?"); 
                    $select_get->bind_param("i", $get_id); 
                    $select_get->execute(); 
                    $result = $select_get->get_result(); 

                    while ($fetch_get = $result->fetch_assoc()) { 
                        $sub_total = $fetch_get['price']; 
                        $grand_total += $sub_total; 
                        ?> 
                        <div class="flex"> 
                            <img src="uploaded_files/<?= htmlspecialchars($fetch_get['image']); ?>" class="image"> 
                            <div> 
                                <h3 class="name"><?= htmlspecialchars($fetch_get['name']); ?></h3> 
                                <p class="price">$<?= number_format($fetch_get['price'], 2); ?>/-</p> 
                            </div> 
                        </div> 
                        <?php 
                    } 
                } else { 
                    $select_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?"); 
                    $select_cart->bind_param("i", $user_id); 
                    $select_cart->execute(); 
                    $cart_result = $select_cart->get_result(); 

                    if ($cart_result->num_rows > 0) { 
                        while ($fetch_cart = $cart_result->fetch_assoc()) { 
                            $select_products = $conn->prepare("SELECT * FROM product WHERE id = ?"); 
                            $select_products->bind_param("i", $fetch_cart['product_id']); 
                            $select_products->execute(); 
                            $product_result = $select_products->get_result(); 
                            $fetch_products = $product_result->fetch_assoc(); 

                            $sub_total = $fetch_cart['quantity'] * $fetch_products['price']; 
                            $grand_total += $sub_total; 
                            ?>
                            <div class="flex"> 
                                <img src="uploaded_files/<?= htmlspecialchars($fetch_products['image']); ?>" class="image"> 
                                <div> 
                                    <h3 class="name"><?= htmlspecialchars($fetch_products['name']); ?></h3> 
                                    <p class="price">$<?= number_format($fetch_products['price'], 2); ?> X <?= $fetch_cart['quantity']; ?></p> 
                                </div> 
                            </div>
                            <?php
                        }
                    } else {
                        echo '<p class="empty">Your cart is empty!</p>';
                    }
                }
                ?>
            </div> 
            <div class="grand-total">
                <span>Total Amount Payable:</span>
                <p>$<?= $grand_total; ?>/-</p> 
            </div>
        </div>
    </div> 
</div>

<?php include 'components/footer.php'; ?>

<!----- SweetAlert CDN link ----->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js"></script>
<?php include 'components/alert.php'; ?>
</body>
</html>
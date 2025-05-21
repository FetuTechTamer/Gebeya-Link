<?php 
// Add product to cart 
if (isset($_POST['add_to_cart'])) { 
    if ($user_id != '') { 
        $id = unique_id(); 
        $product_id = $_POST['product_id']; 
        $quantity = $_POST['quantity']; 
        $quantity = filter_var($quantity, FILTER_SANITIZE_STRING); 

        // Verify if the product is already in the cart
        $verify_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?"); 
        $verify_cart->bind_param("ss", $user_id, $product_id); 
        $verify_cart->execute(); 
        $verify_cart->store_result(); 

        // Check the number of items in the cart
        $max_cart_items = $conn->prepare("SELECT * FROM cart WHERE user_id = ?"); 
        $max_cart_items->bind_param("s", $user_id); 
        $max_cart_items->execute(); 
        $max_cart_items->store_result(); 

        if ($verify_cart->num_rows > 0) { 
            $warning_msg[] = 'Product already exists in your cart'; 
        } elseif ($max_cart_items->num_rows >= 20) { 
            $warning_msg[] = 'Your cart is full'; 
        } else { 
            $select_price = $conn->prepare("SELECT * FROM product WHERE id = ? LIMIT 1"); 
            $select_price->bind_param("s", $product_id); // Assuming product_id is a string
            $select_price->execute(); 
            $result = $select_price->get_result(); 
            $fetch_price = $result->fetch_assoc(); 

            $insert_cart = $conn->prepare("INSERT INTO cart (id, user_id, product_id, price, quantity) VALUES (?, ?, ?, ?, ?)"); 
            $insert_cart->bind_param("ssssi", $id, $user_id, $product_id, $fetch_price['price'], $quantity); 
            $insert_cart->execute(); 
            $success_msg[] = 'Product added to your cart successfully'; 
        } 
        }else { 
            $warning_msg[] = 'Please login first.'; 
        }
        }
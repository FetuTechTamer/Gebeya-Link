<?php 
// Adding product to the wishlist 
if (isset($_POST['add_to_wishlist'])) { 
    if ($user_id != '') { 
        $id = unique_id(); 
        $product_id = $_POST['product_id']; 

        // Check if the product is already in the wishlist
        $verify_wishlist = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?"); 
        $verify_wishlist->bind_param("ss", $user_id, $product_id);
        $verify_wishlist->execute(); 
        $verify_wishlist->store_result(); 

        // Check if the product is already in the cart
        $cart_num = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?"); 
        $cart_num->bind_param("ss", $user_id, $product_id);
        $cart_num->execute(); 
        $cart_num->store_result(); 

        if ($verify_wishlist->num_rows > 0) { 
            $warning_msg[] = 'Product already exists in your wishlist'; 
        } else { 
            // Check if the product exists in the cart
            $cart_check = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?"); 
            $cart_check->bind_param("ss", $user_id, $product_id);
            $cart_check->execute(); 
            $cart_check->store_result(); 

            if ($cart_check->num_rows > 0) { 
                $warning_msg[] = 'Product already exists in your cart'; 
            } else { 
                // Get product price
                $select_price = $conn->prepare("SELECT * FROM product WHERE id = ? LIMIT 1"); 
                $select_price->bind_param("s", $product_id);
                $select_price->execute(); 
                $result = $select_price->get_result(); 
                $fetch_price = $result->fetch_assoc(); 

                // Insert into wishlist
                $insert_wishlist = $conn->prepare("INSERT INTO wishlist (id, user_id, product_id, price) VALUES (?, ?, ?, ?)"); 
                $insert_wishlist->bind_param("sssd", $id, $user_id, $product_id, $fetch_price['price']); 
                $insert_wishlist->execute(); 
                $success_msg[] = 'Product added to your wishlist successfully'; 
            } 
        }
    } else {
        $warning_msg[] = 'Please log in first';
    }
}
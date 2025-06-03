<header class="header"> 
    <section class="flex"> 
        <a href="home.php" class="logo"> 
            <img src="image/logo.webp" width="130" alt="Logo">
        </a> 

        <nav class="navbar"> 
            <a href="home.php">Home</a> 
            <a href="about-us.php">About Us</a> 
            <a href="menu.php">Shop</a> 
            <a href="order.php">Order</a> 
            <a href="contact.php">Contact Us</a> 
        </nav> 

        <form action="search_product.php" method="post" class="search-form"> 
            <input type="text" name="search_product" placeholder="Search product..." required maxlength="100"> 
            <button type="submit" id="search_product_btn">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form> 

        <div class="icons">
            <div id="menu-btn"><i class="fa-solid fa-bars"></i></div>
            <div id="search-btn"><i class="fa-solid fa-magnifying-glass"></i></div>

            <?php 
            // Count wishlist items
            $count_wishlist_item = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ?"); 
            $count_wishlist_item->bind_param("s", $user_id);
            $count_wishlist_item->execute(); 
            $count_wishlist_item->store_result(); 
            $total_wishlist_items = $count_wishlist_item->num_rows; 
            $count_wishlist_item->free_result(); // Free the result set
            ?> 
            <a href="wishlist.php"><i class="fa-regular fa-heart"></i><sup><?= $total_wishlist_items; ?></sup></a> 

            <?php 
            // Count cart items
            $count_cart_item = $conn->prepare("SELECT * FROM cart WHERE user_id = ?"); 
            $count_cart_item->bind_param("s", $user_id); 
            $count_cart_item->execute(); 
            $count_cart_item->store_result(); 
            $total_cart_items = $count_cart_item->num_rows; 
            $count_cart_item->free_result(); // Free the result set
            ?> 
            <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i><sup><?= $total_cart_items; ?></sup></a>
           
            <div id="user-btn"><i class="fa-solid fa-user"></i></div>
        </div>

        <div class="profile-detail"> 
        <?php 
            $select_profile = $conn->prepare("SELECT * FROM user WHERE id = ?");
            $select_profile->bind_param("s", $user_id);
            $select_profile->execute();
            $result = $select_profile->get_result();

            if ($result && $result->num_rows > 0) {
                $fetch_profile = $result->fetch_assoc();
        ?> 
                <img src="uploaded_files/<?= htmlspecialchars($fetch_profile['image']); ?>" alt="Profile Image"> 
                <h3 style="margin-bottom: 1rem;"><?= htmlspecialchars($fetch_profile['name']); ?></h3> 
                <div class="flex-btn"> 
                    <a href="profile.php" class="btn">View Profile</a> 
                    <a href="components/user_logout.php" onclick="return confirm('Logout from this website?');" class="btn">Logout</a> 
                </div> 
        <?php 
            } else {
        ?>
                <h3 style="margin-bottom: 1rem;">Please login or register</h3> 
                <div class="flex-btn"> 
                    <a href="login.php" class="btn">Login</a> 
                    <a href="register.php" class="btn">Register</a> 
                </div> 
        <?php
            }
        ?>
        </div>
    </section> 
</header>
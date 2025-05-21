<div class="products"> 
    <div class="box-container"> 
        <?php 
        // Prepare and execute the product selection query
        $status = 'active';
        $select_product = $conn->prepare("SELECT * FROM product WHERE status = ? LIMIT 6");
        $select_product->bind_param("s", $status);
        $select_product->execute();
        $result = $select_product->get_result();
        
        // Check if any products were found
        if ($result->num_rows > 0) { 
            while ($fetch_product = $result->fetch_assoc()) { 
                ?>
                <form action="" method="post" class="box <?php if ($fetch_product['stock'] == 0) { echo 'disabled'; } ?>"> 
                    <img src="uploaded_files/<?= $fetch_product['image']; ?>" class="image"> 
                    <?php if ($fetch_product['stock'] > 9) { ?> 
                        <span class="stock" style="color: green;">In stock</span> 
                    <?php } elseif ($fetch_product['stock'] == 0) { ?> 
                        <span class="stock" style="color: red;">Out of stock</span> 
                    <?php } else { ?> 
                        <span class="stock" style="color: red;">Hurry, only <?= $fetch_product['stock']; ?> left</span> 
                    <?php } ?> 
                    <div class="content"> 
                        <div class="button"> 
                            <div> 
                                <h3 class="name"><?= $fetch_product['name']; ?></h3> 
                            </div> 
                            <div> 
                                <button type="submit" name="add_to_cart"><i class="fas fa-shopping-cart"></i></button> 
                                <button type="submit" name="add_to_wishlist"><i class="fas fa-heart"></i></button> 
                                <a href="view_page.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a> 
                            </div>
                        </div> 
                        <p class="price">Price: $<?= $fetch_product['price']; ?></p>  
                        <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>"> 
                        <div class="flex-btn"> 
                            <a href="checkout.php?get_id=<?= $fetch_product['id']; ?>" class="btn">Buy Now</a> 
                            <input type="number" name="quantity" required min="1" value="1" max="99" class="qty box" maxlength="2"> 
                        </div>
                    </div> 
                </form>
                <?php 
            } 
        } else {
            echo '
                <div class="empty">
                    <p>No products added yet!</p>
                </div>'; 
        }
        ?> 
    </div> 
</div>
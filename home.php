<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];

} else {
    $user_id ='';
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebeya Link-home page</title>
    <link rel="icon" href="image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'components/user_header.php'; ?>
        <!-- Slider Section Start -->
        <div class="slider-container">
            <div class="slider">
                <div class="slideBox active">
                    <div class="textBox">
                        <h1>Experience the taste of <br> fresh, locally grown produces</h1>
                        <a href="menu.php" class="btn">Shop Now</a>
                    </div>
                    <div class="imgBox">
                        <img src="image/slider-1.jpg">
                    </div>
                </div>
                <div class="slideBox ">
                    <div class="textBox">
                        <h1>Experience the taste of <br> fresh, locally grown produces</h1>
                        <a href="menu.php" class="btn">Shop Now</a>
                    </div>
                    <div class="imgBox">
                        <img src="image/slider-2.jpg">
                    </div>
                </div>
            </div>
            <ul class="controls">
                <li onclick="nextSlide();" class="next" ><i class="fa-solid fa-arrow-right"></i></li>
                <li onclick="prevSlide();" class="prev"><i class="fa-solid fa-arrow-left"></i></li>
            </ul>
        </div>
        <!-- Slider Section End -->
        <div class="service">
            <div class="box-container">
                <!-- Service Item Box: Delivery -->
                <div class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="image/delivery1.png" class="img1">
                            <img src="image/delivery2.png" class="img2">
                        </div>
                    </div>
                    <div class="detail">
                        <h4>Delivery Support</h4>
                        <span>Same-Day Delivery</span>
                    </div>
                </div>
                
                <!-- Service Item Box: Payment -->
                <div class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="image/payment.png" class="img1">
                            <img src="image/payment.png" class="img2">
                        </div>
                    </div>
                    <div class="detail">
                        <h4>Payment Processing</h4>
                        <span>100% Secure Transactions</span>
                    </div>
                </div>
                
                <!-- Service Item Box: Customer Support -->
                <div class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="image/support.png" class="img1">
                            <img src="image/support.png" class="img2">
                        </div>
                    </div>
                    <div class="detail">
                        <h4>Customer Support</h4>
                        <span>24/7 Assistance</span>
                    </div>
                </div>
                
                <!-- Service Item Box: Order Management -->
                <div class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="image/order-management.png" class="img1">
                            <img src="image/order-management.png" class="img2">
                        </div>
                    </div>
                    <div class="detail">
                        <h4>Order Management</h4>
                        <span>Easy Tracking</span>
                    </div>
                </div>
                
                <!-- Service Item Box: Analytics -->
                <div class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="image/analytics.png" class="img1">
                            <img src="image/analytics.png" class="img2">
                        </div>
                    </div>
                    <div class="detail">
                        <h4>Analytics</h4>
                        <span>Sales Insights</span>
                    </div>
                </div>
                
                <!-- Service Item Box: Security -->
                <div class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="image/security.png" class="img1">
                            <img src="image/security.png" class="img2">
                        </div>
                    </div>
                    <div class="detail">
                        <h4>Data Security</h4>
                        <span>Protected Information</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Service Section End -->
        <div class="categories">
            <div class="heading">
                <h1>Categories Features</h1>
                <img src="image/separator.webp" alt="Separator">
            </div>
            <div class="box-container">
                <div class="box">
                    <img src="image/catagory/vegetables.png" alt="Vegetables">
                    <a href="menu.php" class="btn">Vegetables</a>
                </div>
                <div class="box">
                    <img src="image/catagory/nuts.png" alt="Nuts">
                    <a href="menu.php" class="btn">Nuts</a>
                </div>
               <div class="box">
                    <img src="image/catagory/fruits.png" alt="Fruits">
                    <a href="menu.php" class="btn">Fruits</a>
                </div>
                <div class="box">
                    <img src="image/catagory/seasonal.png" alt="Seasonal Items">
                    <a href="menu.php" class="btn">Seasonal Items</a>
                </div>
                <div class="box">
                    <img src="image/catagory/lentils.png" alt="Lentils">
                    <a href="menu.php" class="btn">Lentils</a>
                </div>
                <div class="box">
                    <img src="image/catagory/cereals.png" alt="Cereals">
                    <a href="menu.php" class="btn">Cereals</a>
                </div>
            </div>
        </div>
        <!-- Categories Section End -->
        <img src="image/catagory/menu-banner.jpg" class="menu-banner" alt="menu-banner">
        <div class="taste"> 
            <div class="heading"> 
                <span>Fresh Produce</span> 
                <h1>Purchase any fresh produce and enjoy a free item!</h1>
                <img src="image/separator.webp" alt="Separator Image"> 
            </div> 
            <div class="box-container"> 
                <div class="box"> 
                    <img src="image/catagory/cucumber.png" alt="Fresh Cucumber"> 
                    <div class="detail"> 
                        <h2>Crisp and Refreshing</h2> 
                        <h1>Cucumber</h1> 
                    </div> 
                </div> 
                <div class="box"> 
                    <img src="image/catagory/onion.png" alt="Fresh Onion"> 
                    <div class="detail"> 
                        <h2>Pungent and Flavorful</h2> 
                        <h1>Onion</h1> 
                    </div> 
                </div> 
                <div class="box"> 
                    <img src="image/catagory/tomato.png" alt="Fresh Tomato"> 
                    <div class="detail"> 
                        <h2>Juicy and Delicious</h2> 
                        <h1>Tomato</h1> 
                    </div> 
                </div> 
            </div> 
        </div>
        <!-- Taste section end --> 
        <div class="produce-container"> 
            <div class="overlay"></div> 
            <div class="detail"> 
                <h1>Fresh produce is better than <br> fast food for your health</h1> 
                <p>Enjoy a variety of organic fruits and vegetables, <br> 
                packed with nutrients and flavor.</p> 
                <a href="menu.php" class="btn">Shop Now</a> 
            </div> 
        </div>
        <!-- Container section end --> 
        <div class="taste2"> 
            <div class="t-banner"> 
                <div class="overlay"></div> 
                <div class="detail">
                    <h1>Discover Your Taste of Fresh Fruits</h1> 
                    <p>Enjoy the sweetness of organic fruits.</p> 
                    <a href="menu.php" class="btn">Shop Now</a> 
                </div>
            </div> 
            <div class="box-container"> 
                <div class="box"> 
                    <div class="box-overlay"></div> 
                    <img src="image/catagory/apple.png" alt="Fresh Apple"> 
                    <div class="box-details fadeIn-bottom"> 
                        <h1>Apple</h1> 
                        <p>Crisp and refreshing</p> 
                        <a href="menu.php" class="btn">Explore More</a> 
                    </div> 
                </div>
                <div class="box"> 
                    <div class="box-overlay"></div> 
                    <img src="image/catagory/strawberry.png" alt="Fresh Strawberry"> 
                    <div class="box-details fadeIn-bottom"> 
                        <h1>Strawberry</h1> 
                        <p>Sweet and juicy</p> 
                        <a href="menu.php" class="btn">Explore More</a> 
                    </div> 
                </div>
                <div class="box"> 
                    <div class="box-overlay"></div> 
                    <img src="image/catagory/tangerine.png" alt="Fresh Tangerine"> 
                    <div class="box-details fadeIn-bottom"> 
                        <h1>Tangerine</h1> 
                        <p>Citrusy and sweet</p> 
                        <a href="menu.php" class="btn">Explore More</a> 
                    </div> 
                </div>
                <div class="box"> 
                    <div class="box-overlay"></div> 
                    <img src="image/catagory/mango.png" alt="Fresh Mango"> 
                    <div class="box-details fadeIn-bottom"> 
                        <h1>Mango</h1> 
                        <p>Juicy and tropical</p> 
                        <a href="menu.php" class="btn">Explore More</a> 
                    </div> 
                </div>
                <div class="box"> 
                    <div class="box-overlay"></div> 
                    <img src="image/catagory/grape.png" alt="Fresh Grape"> 
                    <div class="box-details fadeIn-bottom"> 
                        <h1>Grape</h1> 
                        <p>Sweet and succulent</p> 
                        <a href="menu.php" class="btn">Explore More</a> 
                    </div> 
                </div>
                 <div class="box"> 
                    <div class="box-overlay"></div> 
                    <img src="image/catagory/pomegranate.png" alt="Fresh Grape"> 
                    <div class="box-details fadeIn-bottom"> 
                        <h1>pomegranate</h1> 
                        <p>Rich in antioxidants</p> 
                        <a href="menu.php" class="btn">Explore More</a> 
                    </div> 
                </div>
            </div> 
        </div>
        <!-- Taste2 section end --> 
        <div class="flavor"> 
            <div class="box-container"> 
                <img src="image/catagory/left-banner2.jpg" alt="Left Banner"> 
                <div class="detail"> 
                    <h1>Hot Deal! Sale Up To <span>20% Off</span></h1> 
                    <p>Expired</p> 
                    <a href="menu.php" class="btn">Shop Now</a> 
                </div> 
            </div> 
        </div> 
        <!-- Flavour section end --> 
        <div class="usage"> 
            <div class="heading"> 
                <h1>How It Works</h1> 
                <img src="image/separator.webp" alt="Separator Image"> 
            </div> 
            <div class="row"> 
                <div class="box-container"> 
                    <div class="box"> 
                        <img src="image/catagory/icon1.jpeg" alt="Select Fresh Produce Icon"> 
                        <div class="detail"> 
                            <h3>Select Fresh Produce</h3> 
                            <p>Choose from a variety of organic fruits and vegetables to suit your taste.</p> 
                        </div> 
                    </div> 
                    <div class="box"> 
                        <img src="image/catagory/icon2.png" alt="Add to Cart Icon"> 
                        <div class="detail"> 
                            <h3>Add to Cart</h3> 
                            <p>Easily add your selected items to the cart for a quick checkout.</p> 
                        </div> 
                    </div> 
                    <div class="box"> 
                        <img src="image/catagory/icon3.png" alt="Choose Delivery Icon"> 
                        <div class="detail"> 
                            <h3>Choose Delivery Option</h3> 
                            <p>Select a convenient delivery time that works for you.</p> 
                        </div> 
                    </div> 
                    <div class="box"> 
                        <img src="image/catagory/icon4.jpeg" alt="Checkout Icon"> 
                        <div class="detail"> 
                            <h3>Checkout</h3> 
                            <p>Complete your purchase quickly and securely.</p> 
                        </div> 
                    </div> 
                    <div class="box"> 
                        <img src="image/catagory/icon5.png" alt="Receive Fresh Produce Icon"> 
                        <div class="detail"> 
                            <h3>Receive Fresh Produce</h3> 
                            <p>Get your fresh items delivered right to your doorstep.</p> 
                        </div> 
                    </div> 
                    <div class="box"> 
                        <img src="image/catagory/icon6.jpeg" alt="Enjoy Your Meals Icon"> 
                        <div class="detail"> 
                            <h3>Enjoy Your Meals</h3> 
                            <p>Cook and savor delicious, healthy meals with your fresh produce!</p> 
                        </div> 
                    </div> 
                </div> 
                <img src="image/catagory/sub-banner.jpg" class="divider" alt="">
            </div> 
        </div>
         <!-- Usage section end --> 
        <div class="pride"> 
            <div class="detail"> 
                <h1>We Pride Ourselves On <br> Fresh, Organic Produce.</h1> 
                <p>Experience the vibrant flavors of seasonal fruits and vegetables, <br> 
                sourced directly from local farms to ensure quality and freshness.</p> 
                <a href="menu.php" class="btn">Shop Now</a> 
            </div> 
        </div>
            <!-- Pride section end -->
    <?php include 'components/footer.php';?>
    <!----- sweetalert cdn link ----->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js"></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>
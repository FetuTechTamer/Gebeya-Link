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
                <!-- Service Item Box -->
                <div class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="image/services.png" class="img1" >
                            <img src="image/services.png" class="img2" >
                        </div>
                    </div>
                    <div class="detail">
                        <h4>Delivery</h4>
                        <span>100% Secure</span>
                    </div>
                </div>
                  <!-- Service Item Box -->
                <div class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="image/services.png" class="img1" >
                            <img src="image/services.png" class="img2" >
                        </div>
                    </div>
                    <div class="detail">
                        <h4>Payment</h4>
                        <span>100% Secure</span>
                    </div>
                </div>
                  <!-- Service Item Box -->
                <div class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="image/services.png" class="img1" >
                            <img src="image/services.png" class="img2" >
                        </div>
                    </div>
                    <div class="detail">
                        <h4>Support</h4>
                        <span>24*7 hours</span>
                    </div>
                </div>
                  <!-- Service Item Box -->
                <div class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="image/services.png" class="img1" >
                            <img src="image/services.png" class="img2" >
                        </div>
                    </div>
                    <div class="detail">
                        <h4>Delivery</h4>
                        <span>100% Secure</span>
                    </div>
                </div>
                  <!-- Service Item Box -->
                <div class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="image/services.png" class="img1" >
                            <img src="image/services.png" class="img2" >
                        </div>
                    </div>
                    <div class="detail">
                        <h4>Delivery</h4>
                        <span>100% Secure</span>
                    </div>
                </div>
                  <!-- Service Item Box -->
                <div class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="image/services.png" class="img1" >
                            <img src="image/services.png" class="img2" >
                        </div>
                    </div>
                    <div class="detail">
                        <h4>Delivery</h4>
                        <span>100% Secure</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Service Section End -->
        <div class="categories">
        <div class="heading">
            <h1>Categories Features</h1>
            <img src="image/separator-img.webp" alt="Separator">
        </div>
        <div class="box-container">
            <div class="box">
                <img src="image/categories.jpg" alt="Categories">
                <a href="menu.php" class="btn">Tomatoes</a>
            </div>
             <div class="box">
                <img src="image/categories.jpg" alt="Categories">
                <a href="menu.php" class="btn">Tomatoes</a>
            </div>
             <div class="box">
                <img src="image/categories.jpg" alt="Categories">
                <a href="menu.php" class="btn">Tomatoes</a>
            </div>
             <div class="box">
                <img src="image/categories.jpg" alt="Categories">
                <a href="menu.php" class="btn">Tomatoes</a>
            </div>
             <div class="box">
                <img src="image/categories.jpg" alt="Categories">
                <a href="menu.php" class="btn">Tomatoes</a>
            </div>
             <div class="box">
                <img src="image/categories.jpg" alt="Categories">
                <a href="menu.php" class="btn">Tomatoes</a>
            </div>
        </div>
        </div>
        <!-- Categories Section End -->
        <img src="image/menu-banner.jpg" class="menu-banner" alt="menu-banner">
        <div class="taste"> 
            <div class="heading"> 
                <span>Taste</span> 
                <h1>Buy any ice cream @ get one free</h1> 
                <img src="image/separator-img.png" alt="Separator Image"> 
            </div> 
            <div class="box-container"> 
                <div class="box"> 
                    <img src="image/taste.webp" alt="Vanilla Ice Cream"> 
                    <div class="detail"> 
                        <h2>Natural Sweetness</h2> 
                        <h1>Vanilla</h1> 
                    </div> 
                </div> 
                 <div class="box"> 
                    <img src="image/taste.webp" alt="Vanilla Ice Cream"> 
                    <div class="detail"> 
                        <h2>Natural Sweetness</h2> 
                        <h1>Vanilla</h1> 
                    </div> 
                </div> 
                 <div class="box"> 
                    <img src="image/taste.webp" alt="Vanilla Ice Cream"> 
                    <div class="detail"> 
                        <h2>Natural Sweetness</h2> 
                        <h1>Vanilla</h1> 
                    </div> 
                </div> 
                
            </div> 
        </div>
        <!-- Taste section end --> 
        <div class="ice-container"> 
            <div class="overlay"></div> 
            <div class="detail"> 
                <h1>Ice cream is cheaper than <br> therapy for stress</h1> 
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, <br> 
                sed do eiusmod tempor incididunt ut labore.</p> 
                <a href="menu.php" class="btn">Shop Now</a> 
            </div> 
        </div> 
        <!-- Container section end --> 
        <div class="taste2"> 
            <div class="t-banner"> 
                <div class="overlay"></div> 
                   <div class="detail">
                        <h1>Find Your Taste of Desserts</h1> 
                        <p>Treat them to a delicious treat and send them some Luck 'o the Irish too.</p> 
                        <a href="menu.php" class="btn">Shop Now</a> 
                   </div>
            </div> 
            <div class="box-container"> 
                <div class="box"> 
                    <div class="box-overlay"></div> 
                    <img src="image/type4.jpg" alt="Strawberry Dessert"> 
                    <div class="box-details fadeIn-bottom"> 
                        <h1>Strawberry</h1> 
                        <p>Find your taste of desserts</p> 
                        <a href="menu.php" class="btn">Explore More</a> 
                    </div> 
                </div>
                 <div class="box"> 
                    <div class="box-overlay"></div> 
                    <img src="image/type4.jpg" alt="Strawberry Dessert"> 
                    <div class="box-details fadeIn-bottom"> 
                        <h1>Strawberry</h1> 
                        <p>Find your taste of desserts</p> 
                        <a href="menu.php" class="btn">Explore More</a> 
                    </div> 
                </div>
                 <div class="box"> 
                    <div class="box-overlay"></div> 
                    <img src="image/type4.jpg" alt="Strawberry Dessert"> 
                    <div class="box-details fadeIn-bottom"> 
                        <h1>Strawberry</h1> 
                        <p>Find your taste of desserts</p> 
                        <a href="menu.php" class="btn">Explore More</a> 
                    </div> 
                </div>
                 <div class="box"> 
                    <div class="box-overlay"></div> 
                    <img src="image/type4.jpg" alt="Strawberry Dessert"> 
                    <div class="box-details fadeIn-bottom"> 
                        <h1>Strawberry</h1> 
                        <p>Find your taste of desserts</p> 
                        <a href="menu.php" class="btn">Explore More</a> 
                    </div> 
                </div>
                 <div class="box"> 
                    <div class="box-overlay"></div> 
                    <img src="image/type4.jpg" alt="Strawberry Dessert"> 
                    <div class="box-details fadeIn-bottom"> 
                        <h1>Strawberry</h1> 
                        <p>Find your taste of desserts</p> 
                        <a href="menu.php" class="btn">Explore More</a> 
                    </div> 
                </div>
            </div> 
        </div>
        <!-- Taste2 section end --> 
        <div class="flavor"> 
            <div class="box-container"> 
                <img src="image/left-banner2.webp" alt="Left Banner"> 
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
                <img src="image/separator-img.png" alt="Separator Image"> 
            </div> 
            <div class="row"> 
                <div class="box-container"> 
                    <div class="box"> 
                        <img src="image/icon.avif" alt="Scoop Ice Cream Icon"> 
                        <div class="detail"> 
                            <h3>Scoop Ice-Cream</h3> 
                            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Rem dolor nihil dicta eveniet quam nam explicabo, natus labore quia cupiditate.</p> 
                        </div> 
                    </div> 
                    <div class="box"> 
                        <img src="image/icon.avif" alt="Scoop Ice Cream Icon"> 
                        <div class="detail"> 
                            <h3>Scoop Ice-Cream</h3> 
                            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Rem dolor nihil dicta eveniet quam nam explicabo, natus labore quia cupiditate.</p> 
                        </div> 
                    </div> 
                    <div class="box"> 
                        <img src="image/icon.avif" alt="Scoop Ice Cream Icon"> 
                        <div class="detail"> 
                            <h3>Scoop Ice-Cream</h3> 
                            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Rem dolor nihil dicta eveniet quam nam explicabo, natus labore quia cupiditate.</p> 
                        </div> 
                    </div> 
                </div> 
                <img src="image/sub-banner.png" class="divider" alt="">
            </div> 
            <!-- Usage section end --> 
            <div class="pride"> 
                <div class="detail"> 
                    <h1>We Pride Ourselves On <br> Exceptional Flavors.</h1> 
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, <br> 
                    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p> 
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
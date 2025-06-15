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
    <title>Gebeya Link- About us</title>
    <link rel="icon" href="image/favicon.ico" type="image/png">
    <link rel="stylesheet" href="css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .banner .detail {
        padding: 50px;
    }

    @media (min-width: 1024px) { 
        .banner .detail {
            padding: 400px; 
        }
    }
    </style>
 
</head>
<body>
    <?php include 'components/user_header.php'; ?>
   <div class="banner"> 
    <div class="detail" > 
        <h1>About Gebeya Link</h1> 
        <p>Gebeya Link is dedicated to providing high-quality agricultural products. We focus on sustainable practices <br>and supporting local farmers to ensure fresh and nutritious offerings.<br>
        Our mission is to connect consumers with the best produce while promoting responsible farming.</p> 
        <span><a href="home.php">Home</a> <i class="fa-solid fa-arrow-right"></i> About Us</span> 
    </div> 
</div>
 <div class="developer"> 
    <div class="box-container"> 
        <div class="box"> 
             <div class="heading">
        <h1>Meet the Gebeya Link Team</h1>
        <img src="image/separator.webp" alt="Separator Image">
    </div>
    <p>We are a dedicated team of professionals passionate about creating exceptional digital experiences. Our diverse skills range from web development to design, ensuring that we deliver top-notch services to our users.</p>
        </div> 
        <div class="box"> 
            <img src="image/about/team.png" class="img" alt="Fethiya Abdurehim"> 
        </div> 
    </div> 
</div>
<div class="story" style="padding-right:120px;"> 
    <div class="heading"> 
        <h1>Our Story</h1> 
        <img src="image/separator.webp" alt="Separator Image"> 
    </div> 
    <p>At Gebeya Link, we are passionate about connecting people with high-quality agricultural products. <br> 
    Our journey began with a commitment to support local farmers and promote sustainable practices. <br> 
    We believe in delivering fresh, nutritious produce that enhances the well-being of our community. <br> 
    Join us as we continue to grow and innovate in the agricultural sector.</p> 
    <a href="services.php" class="btn">Our Services</a> 
</div>
<div class="container"> 
    <div class="box-container"> 
        <div class="img-box"> 
            <img src="image/about/about.png" alt="About Image"> 
        </div> 
        <div class="box"> 
            <div class="heading"> 
                <h1>Bringing Freshness to Your Table</h1> 
                <img src="image/separator.webp" alt="Separator Image"> 
            </div> 
            <p>We strive to provide the best quality products while ensuring sustainability and ethical sourcing. Our team works closely with farmers to ensure that every product is fresh and responsibly harvested. <br>
            Experience the difference of farm-to-table freshness with Gebeya Link.</p> 
            <a href="learn-more.php" class="btn">Learn More</a> 
        </div> 
    </div> 
</div>
<!-- Story section end -->
<div class="team"> 
    <div class="heading"> 
        <span>Our Team</span> 
        <h1>Quality & Passion with Our Services</h1> 
        <img src="image/separator.webp" alt="Separator Image"> 
    </div> 
   <div class="box-container"> 
        <div class="box"> 
            <img src="image/about/feti.jpg" class="img" alt="Fethiya Abdurehim"> 
            <div class="content"> 
                <h2>Fethiya Abdurehim</h2> 
                <p >Project Manager</p>
                <p>Hey, I'm Fethiya! I oversee project timelines and ensure everything runs smoothly. My passion is turning ideas into reality.</p>
                <a href="https://github.com/FetuTechTamer" class="btn">Github</a>
            </div> 
        </div> 
        <div class="box"> 
            <img src="image/about/yordi.jpg" class="img" alt="Yordanos Solomon"> 
            <div class="content"> 
                <h2>Yordanos Solomon</h2> 
                <p >DevOps Engineer</p> 
                <p>Hi, I’m Yordanos! I streamline our deployment processes and ensure our systems are robust and reliable.</p>
                <a href="https://github.com/yordanossole" class="btn">Github</a>
            </div> 
        </div> 
        <div class="box"> 
            <img src="image/about/nafi.jpg" class="img" alt="Nafargi Damena"> 
            <div class="content"> 
                <h2>Nafargi Damena</h2> 
                <p >UI/UX Designer</p> 
                <p>Hello! I’m Nafargi, and I design user-friendly interfaces that enhance the user experience.</p>
                <a href="https://github.com/nafargi" class="btn">Github</a>
            </div> 
        </div> 
        <div class="box"> 
            <img src="image/about/tile.jpg" class="img" alt="Tilahun Beza"> 
            <div class="content"> 
                <h2>Tilahun Beza</h2> 
                <p >Quality Assurance</p> 
                <p>Hi, I’m Tilahun! I ensure that our products meet the highest quality standards before they reach our customers.</p>
                <a href="https://github.com/TB-777/TB-777" class="btn">Github</a>
            </div> 
        </div> 
        <div class="box"> 
            <img src="image/about/lema.jpg" class="img" alt="Lema Tefera"> 
            <div class="content"> 
                <h2>Lema Tefera</h2> 
                <p >Content Strategist</p> 
                <p>Hey there! I’m Lema, focusing on creating engaging content that resonates with our audience.</p>
                <a href="https://github.com/Lema-TR" class="btn">Github</a>
            </div> 
        </div> 
        <div class="box"> 
            <img src="image/about/ab.jpg" class="img" alt="Abate Alemu"> 
            <div class="content"> 
                <h2>Abate Alemu</h2> 
                <p>Security Analyst</p> 
                <p>Hi there! I’m Abate, dedicated to ensuring the safety and integrity of our digital assets.</p>
                <a href="https://github.com/abateIS" class="btn">Github</a>
            </div> 
        </div>
    </div> 
</div>
<!-- Team section end -->
 <div class="standards"> 
    <div class="detail"> 
        <div class="heading"> 
            <h1>Our Standards</h1> 
            <img src="image/separator.webp" alt="Separator Image"> 
        </div> 
        <p>we prioritize quality and sustainability in all our agricultural products.</p> 
       <i class="fa-regular fa-heart"></i>
        <p>We support local farmers and ensure ethical sourcing practices.</p> 
       <i class="fa-regular fa-heart"></i>
        <p>Our products are fresh, nutritious, and environmentally friendly.</p> 
       <i class="fa-regular fa-heart"></i>
        <p>We are committed to providing exceptional customer service.</p> 
       <i class="fa-regular fa-heart"></i>
        <p>we eliminate the middleman, we connect consumers directly with farmers.</p> 
       <i class="fa-regular fa-heart"></i>
    </div> 
</div> 

<!-- Standards section end -->

<div class="mission"> 
    <div class="box-container"> 
        <div class="box"> 
            <div class="heading"> 
                <h1>Our Mission</h1> 
                <img src="image/separator.webp" alt="Separator Image"> 
            </div> 
            <div class="detail"> 
                <div> 
                    <h2>Connecting Communities</h2> 
                    <p>We aim to bridge the gap between local farmers and consumers, ensuring access to fresh produce.</p> 
                </div> 
            </div> 
            <div class="detail"> 
                <div> 
                    <h2>Promoting Sustainability</h2> 
                    <p>Our mission includes advocating for sustainable farming practices that benefit the environment.</p> 
                </div> 
            </div> 
            <div class="detail"> 
                <div> 
                    <h2>Enhancing Nutrition</h2> 
                    <p>We are dedicated to providing nutritious food options that contribute to healthier lifestyles.</p> 
                </div> 
            </div> 
            <div class="detail"> 
                <div> 
                    <h2>Supporting Local Economies</h2> 
                    <p>We strive to uplift local farmers and strengthen community ties through our practices.</p> 
                </div> 
            </div> 
            <div class="detail"> 
                <div> 
                    <h2>Delivering Quality</h2> 
                    <p>Our commitment is to provide only the highest quality agricultural products to our customers.</p> 
                </div> 
            </div> 
        </div> 
        <div class="box">
            <img src="image/about/mission.jpg" alt="Form Image" class="img">
        </div>
    </div> 
</div>

        
    <?php include 'components/footer.php';?>
    <!----- sweetalert cdn link ----->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js"></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>
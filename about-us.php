<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebeya Link - About Me</title>
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
        <div class="detail"> 
            <h1>About Gebeya Link</h1> 
            <p>Gebeya Link is my initiative to provide high-quality agricultural products while supporting local farmers. I focus on sustainable practices and direct access to fresh, nutritious offerings.<br>
            My goal is to connect consumers with ethically grown produce and create impact through innovation.</p> 
            <span><a href="home.php">Home</a> <i class="fa-solid fa-arrow-right"></i> About Me</span> 
        </div> 
    </div>

    <div class="developer"> 
        <div class="box-container"> 
            <div class="box"> 
                <div class="heading">
                    <h1>About Me</h1>
                    <img src="image/separator.webp" alt="Separator Image">
                </div>
                <p>Hi, I'm Fethiya Abdurehim — the founder and project manager of Gebeya Link. I created this platform with a vision to empower local farmers, promote sustainable agriculture, and make fresh food more accessible. I’m hands-on with everything, from idea to execution, and I’m proud of what I’ve built so far.</p>
            </div> 
            <div class="box"> 
                <img src="image/about/feti.jpg" class="img" alt="Fethiya Abdurehim"> 
            </div> 
        </div> 
    </div>

    <div class="story" style="padding-right:120px;"> 
        <div class="heading"> 
            <h1>My Vision</h1> 
            <img src="image/separator.webp" alt="Separator Image"> 
        </div> 
        <p style="padding-left:170px;">I started Gebeya Link to bridge the gap between farmers and consumers. I believe in sustainability, community-driven solutions, and ethical food sourcing. This platform is more than a business — it’s a mission to change how people access healthy food while uplifting smallholder farmers.</p> 
        <a href="menu.php" class="btn">My Services</a> 
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
                <p>I work closely with local producers to make sure every item on this platform is fresh, clean, and responsibly sourced. My promise is to keep quality and care at the core of everything I deliver. When you buy from Gebeya Link, you're getting farm-to-table goodness with a purpose.</p> 
                <a href="learn-more.php" class="btn">Learn More</a> 
            </div> 
        </div> 
    </div>

    <div class="standards"> 
        <div class="detail"> 
            <div class="heading"> 
                <h1>My Standards</h1> 
                <img src="image/separator.webp" alt="Separator Image"> 
            </div> 
            <p>I prioritize quality and sustainability in all agricultural products offered through Gebeya Link.</p> 
            <i class="fa-regular fa-heart"></i>
            <p>I support local farmers and ensure ethical sourcing practices.</p> 
            <i class="fa-regular fa-heart"></i>
            <p>Everything is fresh, nutritious, and environmentally friendly.</p> 
            <i class="fa-regular fa-heart"></i>
            <p>Exceptional service and trust are at the heart of my platform.</p> 
            <i class="fa-regular fa-heart"></i>
            <p>No middlemen — just direct access between farmers and you.</p> 
            <i class="fa-regular fa-heart"></i>
        </div> 
    </div> 

    <div class="mission"> 
        <div class="box-container"> 
            <div class="box"> 
                <div class="heading"> 
                    <h1>My Mission</h1> 
                    <img src="image/separator.webp" alt="Separator Image"> 
                </div> 
                <div class="detail"> 
                    <div> 
                        <h2>Connecting Communities</h2> 
                        <p>I aim to link local farmers with consumers to ensure access to fresh, high-quality produce.</p> 
                    </div> 
                </div> 
                <div class="detail"> 
                    <div> 
                        <h2>Promoting Sustainability</h2> 
                        <p>I actively promote eco-friendly farming methods that benefit our planet.</p> 
                    </div> 
                </div> 
                <div class="detail"> 
                    <div> 
                        <h2>Enhancing Nutrition</h2> 
                        <p>Through better food access, I hope to improve community health and well-being.</p> 
                    </div> 
                </div> 
                <div class="detail"> 
                    <div> 
                        <h2>Supporting Local Economies</h2> 
                        <p>Every sale helps empower small farmers and boost rural livelihoods.</p> 
                    </div> 
                </div> 
                <div class="detail"> 
                    <div> 
                        <h2>Delivering Quality</h2> 
                        <p>Only the freshest, highest quality products reach your table — that’s my promise.</p> 
                    </div> 
                </div> 
            </div> 
            <div class="box">
                <img src="image/about/mission.jpg" alt="Mission Image" class="img">
            </div>
        </div> 
    </div>

    <?php include 'components/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js"></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>

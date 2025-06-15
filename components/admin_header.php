<header>
    <div class="logo">
        <img src="../image/logo1.png" alt="Company Logo" width="150">
    </div>
    <div class="right">
        <div class="fa-solid fa-user" id="user-btn"></div>
        <div class="toggle-btn"><i class="fas fa-bars"></i></div>
    </div>
    <div class="profile-detail">
        <?php
        $select_profile = $conn->prepare("SELECT * FROM `seller` WHERE id=?");
        $select_profile->bind_param("s", $seller_id);
        $select_profile->execute();
        $result = $select_profile->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        ?>
            <div class="profile">
                <img src="../uploaded_files/<?= htmlspecialchars($row['image']); ?>" class="logo-img" width="100" alt="Profile Image">
                <p><?= htmlspecialchars($row['name']); ?></p>
                <div class="flex-btn">
                    <a href="profile.php" class="btn" style="margin: 0 10px;">Profile</a>
                    <a href="../components/admin_logout.php" onclick="return confirm('Logout from this website?');" class="btn">Logout</a>
                </div>
            </div>
        <?php } ?>
    </div>
</header>

<div class="sidebar-container">
    <div class="sidebar">
        <?php
        $select_profile = $conn->prepare("SELECT * FROM `seller` WHERE id=?");
        $select_profile->bind_param("s", $seller_id);
        $select_profile->execute();
        $result = $select_profile->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        ?>
            <div class="profile">
                <img src="../uploaded_files/<?= htmlspecialchars($row['image']); ?>" class="logo-img" width="100" alt="Profile Image">
                <p><?= htmlspecialchars($row['name']); ?></p>
            </div>
        <?php } ?>
        <h5>Menu</h5>
        <div class="navbar">
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="add_products.php"><i class="fas fa-shopping-bag"></i> Add Products</a></li>
                <li><a href="view_products.php"><i class="fas fa-utensils"></i> View Products</a></li>
                <li><a href="user_accounts.php"><i class="fas fa-user-circle"></i> Accounts</a></li>
                <li><a href="../components/admin_logout.php" onclick="return confirm('Logout from this website?');"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <h5>Find Us</h5>
        <div class="social-links">
            <i class="fab fa-instagram"></i>
            <i class="fab fa-telegram-plane"></i>
            <i class="fab fa-linkedin"></i>
            <i class="fab fa-youtube"></i>
        </div>
    </div>
</div>


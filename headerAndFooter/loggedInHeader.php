<?php 
include 'backend/checkSession.php';
$current_page = basename($_SERVER['PHP_SELF']);

$home_active = ($current_page == 'index.php') ? 'active' : '';
$about_active = ($current_page == 'AboutUs.php') ? 'active' : '';
$donate_active = ($current_page == 'donate.php') ? 'active' : '';
$legal_active = ($current_page == 'Legal.php') ? 'active' : '';
$contact_active = ($current_page == 'ContactUs.php') ? 'active' : '';
?>

<style>
.nav-link {
    text-decoration: none;
    color: var(--gray);
    font-weight: 500;
    transition: color 0.3s ease;
    position: relative;
}

.nav-link:hover,
.nav-link.active {
    color: var(--primary);
}

.nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 100%;
    height: 2px;
    background: var(--primary);
}


:root {
    --primary: #2ecc71; 
    --gray: #666;
}
</style>

<header class="header">
    <nav class="navbar">
        <div class="nav-brand">
            <span class="logo"><img src="images/logo.png" id="logo" alt="SustainWear Logo"></span>
            <h2>SustainWear</h2>
        </div>
        <ul class="nav-menu">
            <li><a href="index.php" class="nav-link <?php echo $home_active; ?>">HOME</a></li>
            <li><a href="donate.php" class="nav-link <?php echo $donate_active; ?>">DONATIONS</a></li>
            <li><a href="profile.php" class="nav-link <?php echo $contact_active; ?>">PROFILE</a></li>
            <?php if ($userType === 'donor'): ?>
            <li><a href="donationHistory.php" class="nav-link <?php echo $contact_active; ?>">DONATION HISTORY</a></li>
            <?php endif; ?>
            <li><a href="ContactUs.php" class="nav-link <?php echo $contact_active; ?>">CONTACT US</a></li>
        </ul>

        <div class="nav-actions">
            <a href="logOut.php" class="btn-primary">LOG OUT</a>
        </div>
    </nav>
</header>
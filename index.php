<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel ="icon" href="images/logo.png" sizes="16x16">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>SustainWear | Sustainable Fashion Platform</title>
</head>
<body>
   

    <header class="header">

        <?php 
        include 'backend/checkSession.php';

        if($isLoggedIn){
            include 'headerAndFooter/loggedInHeader.php';
        }
        else{
            include 'headerAndFooter/header.php';
        }
        ?>

    </header>


<?php

    ?>
<?php if($isLoggedIn): ?>
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">Welcome back,<br><?php echo $username; ?></h1>
                <p class="hero-description">
                    Transform your pre-loved clothing into sustainable impact. 
                    Join thousands making fashion circular and eco-friendly.
                </p>
                <div class="hero-actions">
                    <a href="SignUp.php" class="btn-primary large">Start Donating</a>
                    <a href="AboutUs.php" class="btn-secondary large">Learn More</a>
                </div>
                <div class="hero-stats">
                    <div class="stat">
                        <span class="stat-number">50K+</span>
                        <span class="stat-label">Items Donated</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">120T</span>
                        <span class="stat-label">CO₂ Saved</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">15K+</span>
                        <span class="stat-label">Lives Impacted</span>
                    </div>
                </div>
            </div>
            <div class="hero-visual">
                <div class="floating-card card-1">
                    <div class="card-icon">👕</div>
                    <p>Clothing Donation</p>
                </div>
                <div class="floating-card card-2">
                    <div class="card-icon">🌱</div>
                    <p>Eco Impact</p>
                </div>
                <div class="floating-card card-3">
                    <div class="card-icon">📱</div>
                    <p>Easy Tracking</p>
                </div>
            </div>
        </div>
    </section>
<?php else: ?>
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">Give Fashion<br>a Second Life</h1>
                <p class="hero-description">
                    Transform your pre-loved clothing into sustainable impact. 
                    Join thousands making fashion circular and eco-friendly.
                </p>
                <div class="hero-actions">
                    <a href="SignUp.php" class="btn-primary large">Start Donating</a>
                    <a href="AboutUs.php" class="btn-secondary large">Learn More</a>
                </div>
                <div class="hero-stats">
                    <div class="stat">
                        <span class="stat-number">50K+</span>
                        <span class="stat-label">Items Donated</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">120T</span>
                        <span class="stat-label">CO₂ Saved</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">15K+</span>
                        <span class="stat-label">Lives Impacted</span>
                    </div>
                </div>
            </div>
            <div class="hero-visual">
                <div class="floating-card card-1">
                    <div class="card-icon">👕</div>
                    <p>Clothing Donation</p>
                </div>
                <div class="floating-card card-2">
                    <div class="card-icon">🌱</div>
                    <p>Eco Impact</p>
                </div>
                <div class="floating-card card-3">
                    <div class="card-icon">📱</div>
                    <p>Easy Tracking</p>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

   



    <section class="features">
        <div class="container">
            <div class="section-header">
                <h2>How It Works</h2>
                <p>Simple steps to make a big difference</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">1</div>
                    <h3>List Your Items</h3>
                    <p>Upload photos and details of clothes you want to donate</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">2</div>
                    <h3>AI Categorization</h3>
                    <p>Our system automatically sorts and categorizes your donations</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">3</div>
                    <h3>Track Impact</h3>
                    <p>See your environmental contribution and who you've helped</p>
                </div>
            </div>
        </div>
    </section>

 
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Make a Difference?</h2>
                <p>Join the sustainable fashion movement today</p>
                <button class="btn-primary large">Start Donating Now</button>
            </div>
        </div>
    </section>

<?php include 'backend/footer.php'; ?>
</body>
</html>
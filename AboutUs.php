<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/aboutus.css">
      <link rel ="icon" href="images/logo.png" sizes="16x16">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>About Us | SustainWear</title>
</head>
<body>
   
   <?php 
        include 'backend/checkSession.php';

        if($isLoggedIn){
            include 'headerAndFooter/loggedInHeader.php';
        }
        else{
            include 'headerAndFooter/header.php';
        }
        ?>

    <section class="about-hero">
        <div class="container">
            <div class="about-hero-content">
                <h1 class="about-title">About SustainWear</h1>
                <p class="about-subtitle">Transforming Fashion Through Sustainable Innovation</p>
            </div>
        </div>
    </section>

    <section class="about-content">
        <div class="container">
            <div class="mission-section">
                <h2>Our Mission</h2>
                <p>SustainWear is dedicated to revolutionizing the fashion industry by creating a circular economy for clothing. We bridge the gap between conscious consumers, charitable organizations, and environmental sustainability through innovative technology.</p>
            </div>
        
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Join Our Sustainable Movement</h2>
                <p>Be part of the solution to fashion waste and help create a circular economy</p>
                <a href="SignUp.html" class="btn-primary large">Get Started Today</a>
            </div>
        </div>
    </section>

    <?php include 'headerAndFooter/footer.php'; ?>
    
</body>
</html>
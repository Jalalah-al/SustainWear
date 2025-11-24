<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/legal.css">
    <script src="js/smoothScroll.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Legal | SustainWear</title>
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

    <section class="legal-hero">
        <div class="container">
            <div class="legal-hero-content">
                <h1 class="legal-title">Legal Information</h1>
                <p class="legal-subtitle">Transparency and Trust in Sustainable Fashion</p>
            </div>
        </div>
    </section>

    <section class="legal-content">
        <div class="container">
            <div class="legal-sections">

                <div class="legal-section" id="terms">
                    <h2>Terms of Service</h2>
                    <div class="last-updated">Last Updated: January 2025</div>
                    
                    <h3>1. Acceptance of Terms</h3>
                    <p>By accessing and using SustainWear, you accept and agree to be bound by the terms and provision of this agreement.</p>
                    
                    <h3>2. Use License</h3>
                    <p>Permission is granted to temporarily use SustainWear for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title.</p>
                    
                    <h3>3. User Accounts</h3>
                    <p>When you create an account with us, you must provide accurate information. You are responsible for safeguarding your account and for all activities that occur under your account.</p>
                    
                    <h3>4. Donation Guidelines</h3>
                    <p>All clothing donations must be in good, wearable condition. We reserve the right to refuse items that are damaged, soiled, or inappropriate.</p>
                    
                    <h3>5. Limitation of Liability</h3>
                    <p>SustainWear shall not be held liable for any indirect, incidental, special, consequential or punitive damages resulting from your use of our platform.</p>
                </div>

                <div class="legal-section" id="privacy">
                    <h2>Privacy Policy</h2>
                    <div class="last-updated">Last Updated: January 2025</div>
                    
                    <h3>1. Information We Collect</h3>
                    <p>We collect personal information you provide when creating an account, making donations, or contacting us. This may include name, email address, and donation history.</p>
                    
                    <h3>2. How We Use Your Information</h3>
                    <p>We use your information to provide our services, process donations, communicate with you, and improve our platform. We never sell your personal data to third parties.</p>
                    
                    <h3>3. Data Security</h3>
                    <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, or destruction.</p>
                    
                    <h3>4. Your Rights</h3>
                    <p>You have the right to access, correct, or delete your personal information. You can manage your preferences through your account settings or by contacting us.</p>
                    
                    <h3>5. Cookies and Tracking</h3>
                    <p>We use cookies to enhance your experience and analyze platform usage. You can control cookie preferences through your browser settings.</p>
                </div>

                <div class="legal-section" id="cookies">
                    <h2>Cookie Policy</h2>
                    <div class="last-updated">Last Updated: January 2025</div>
                    
                    <h3>1. What Are Cookies</h3>
                    <p>Cookies are small text files stored on your device when you visit our website. They help us provide you with a better experience.</p>
                    
                    <h3>2. How We Use Cookies</h3>
                    <p>We use cookies for essential functions, analytics, and personalization. This helps us understand how users interact with our platform and improve our services.</p>
                    
                    <h3>3. Managing Cookies</h3>
                    <p>You can control and/or delete cookies as you wish. You can delete all cookies already on your computer and set most browsers to prevent them from being placed.</p>
                </div>

      
                <div class="legal-quick-links">
                    <h3>Quick Navigation</h3>
                    <div class="quick-links-grid">
                        <a href="#terms" class="quick-link">Terms of Service</a>
                        <a href="#privacy" class="quick-link">Privacy Policy</a>
                        <a href="#cookies" class="quick-link">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="legal-contact">
        <div class="container">
            <div class="contact-prompt">
                <h2>Questions About Our Policies?</h2>
                <p>If you have any questions about our legal terms or how we handle your data, please don't hesitate to contact us.</p>
                <a href="ContactUs.html" class="btn-primary">Contact Our Team</a>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="logo">♻️</div>
                    <h3>SustainWear</h3>
                    <p>Making fashion sustainable, one donation at a time</p>
                </div>
                <div class="footer-links">
                    <div class="link-group">
                        <h4>Platform</h4>
                        <a href="AboutUs.php">How it Works</a>
                        <a href="SignUp.php">For Donors</a>
                        <a href="SignUp.php">For Charities</a>
                    </div>
                    <div class="link-group">
                        <h4>Company</h4>
                        <a href="AboutUs.php">About Us</a>
                        <a href="AboutUs.php">Impact</a>
                    </div>
                    <div class="link-group">
                        <h4>Support</h4>
                        <a href="ContactUs.php">Contact</a>
                        <a href="Legal.php">Privacy</a>
                        <a href="Legal.php">Terms</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 SustainWear. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/signup.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Sign Up | SustainWear</title>
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="nav-brand">
                <span class="logo">♻️</span>
                <h2>SustainWear</h2>
            </div>
            <ul class="nav-menu">
                <li><a href="index.html" class="nav-link">Home</a></li>
                <li><a href="AboutUs.html" class="nav-link">About Us</a></li>
                <li><a href="Legal.html" class="nav-link">Legal</a></li>
                <li><a href="ContactUs.html" class="nav-link">Contact Us</a></li>
            </ul>
            <div class="nav-actions">
                <a href="SignIn.php" class="btn-login">Sign In</a>
                <a href="SignUp.php" class="btn-primary">Get Started</a>
            </div>
        </nav>
    </header>

    <main class="signup-main">
        <div class="signup-container">
            <div class="signup-card">
                <div class="signup-header">
                    <h1>Create Your Account</h1>
                    <p>Join SustainWear and start making a difference today</p>
                </div>

                <?php if($error): ?>
                <div class="error-message" style="color: red; padding: 10px; margin-bottom: 15px; border: 1px solid red; border-radius: 5px; background: #ffe6e6;">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>

                <form class="signup-form" method="POST" action="SignUp.php">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn-signup">Create Account</button>
                </form>

                <div class="signup-footer">
                    <p>Already have an account? <a href="SignIn.php">Sign in here</a></p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
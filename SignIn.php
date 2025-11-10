
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/signin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Sign In | SustainWear</title>
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="nav-brand">
                <span class="logo">♻️</span>
                <h2>SustainWear</h2>
            </div>
            <ul class="nav-menu">
                <li><a href="index.html" class="nav-link">HOME</a></li>
                <li><a href="AboutUs.html" class="nav-link">ABOUT US</a></li>
                <li><a href="Legal.html" class="nav-link">LEGAL</a></li>
                <li><a href="ContactUs.html" class="nav-link">CONTACT US</a></li>
            </ul>
            <div class="nav-actions">
                <a href="SignIn.php" class="btn-login">Sign In</a>
                <a href="SignUp.php" class="btn-primary">Get Started</a>
            </div>
        </nav>
    </header>

    <main class="signin-main">
        <div class="signin-container">
            <div class="signin-card">
                <div class="signin-header">
                    <h1>Welcome Back</h1>
                    <p>Sign in to your SustainWear account</p>
                </div>

                <?php if($error): ?>
                <div class="error-message" style="color: red; padding: 10px; margin-bottom: 15px; border: 1px solid red; border-radius: 5px; background: #ffe6e6;">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>

                <form class="signin-form" method="POST" action="SignIn.php">
                    <div class="form-group">
                        <label for="username">Username or Email</label>
                        <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn-signin">Sign In</button>
                </form>

                <div class="signin-footer">
                    <p>Don't have an account? <a href="SignUp.php">Create one here</a></p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>♻️</text></svg>">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/signin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Sign In | SustainWear</title>
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="nav-brand">
               <span class="logo"><img src="images/logo.png" id="logo" alt="SustainWear Logo"></span>
                <h2>SustainWear</h2>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link">HOME</a></li>
                <li><a href="AboutUs.php" class="nav-link">ABOUT US</a></li>
                <li><a href="Legal.php" class="nav-link">LEGAL</a></li>
                <li><a href="ContactUs.php" class="nav-link">CONTACT US</a></li>
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

              

             <form class="signInForm" method="POST" action="backend/register.php">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" name="login" class="btn-signin">Sign In</button>
            </form>

                <div class="signin-footer">
                    <p>Don't have an account? <a href="SignUp.php">Create one here</a></p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
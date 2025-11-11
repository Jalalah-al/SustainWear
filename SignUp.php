
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>♻️</text></svg>">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/signup.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Sign Up | SustainWear</title>
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

    <main class="signup-main">
        <div class="signup-container">
            <div class="signup-card">
                <div class="signup-header">
                    <h1>Create Your Account</h1>
                    <p>Join SustainWear and start making a difference today</p>
                </div>

             <form class="signupForm" method="POST" action="backend/register.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>

                   <div class="form-group">
                    <label for="userType">User Type</label>
                    <select name="userType" id="userType" required>
                        <option value="" disabled selected>Select User Type</option>
                        <option value="doner">Doner</option>
                        <option value="charityStaff">Charity Staff</option>
                    </select>   
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>

                <button type="submit" name="signUp" class="btn-signup">Create Account</button>
            </form>

                <div class="signup-footer">
                    <p>Already have an account? <a href="SignIn.php">Sign in here</a></p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel ="icon" href="images/logo.png" sizes="16x16">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/signup.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Sign Up | SustainWear</title>
</head>
<body>
    <header class="header">
        <?php include 'headerAndFooter/header.php'; ?>

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
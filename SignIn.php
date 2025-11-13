
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
        <?php include 'headerAndFooter/header.php'; ?>

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
<?php
// backend/register.php
session_start();
require_once 'connect.php';

if (isset($_POST['signUp'])) {
    // Handle Registration
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        die("Error: All fields are required. <a href='../SignUp.php'>Go back</a>");
    }
    
    // Check if passwords match
    if ($password !== $confirmPassword) {
        die("Error: Passwords do not match. <a href='../SignUp.php'>Go back</a>");
    }
    
    // Get database connection
    $conn = connectDB();
    
    if (!$conn) {
        die("Error: Database connection failed. <a href='../SignUp.php'>Go back</a>");
    }
    
    // Check if user already exists
    // Check username
    $check_user = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $check_user->bind_param("s", $username);
    $check_user->execute();
    $check_user->store_result();
    
    if ($check_user->num_rows > 0) {
        die("Error: Username already exists. <a href='../SignUp.php'>Go back</a>");
    }
    $check_user->close();
    
    // Check email
    $check_email = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();
    
    if ($check_email->num_rows > 0) {
        die("Error: Email already registered. <a href='../SignUp.php'>Go back</a>");
    }
    $check_email->close();
    
    // Insert new user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    
    if ($stmt->execute()) {
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Registration Successful</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                .success { color: green; font-size: 24px; margin-bottom: 20px; }
                .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
                .btn-container { margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class='success'>✅ Registration successful!</div>
            <p>Your account has been created successfully.</p>
            <div class='btn-container'>
                <a href='../SignIn.php' class='btn'>Sign In</a>
                <a href='../index.html' class='btn'>Go Home</a>
            </div>
        </body>
        </html>
        ";
    } else {
        echo "Error: Registration failed - " . $stmt->error . " <a href='../SignUp.php'>Try again</a>";
    }
    
    $stmt->close();
    $conn->close();
}

if (isset($_POST['login'])) {
    // Handle Login
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Basic validation
    if (empty($username) || empty($password)) {
        die("Error: Please enter both username/email and password. <a href='../SignIn.php'>Go back</a>");
    }
    
    $conn = connectDB();
    
    if (!$conn) {
        die("Error: Database connection failed. <a href='../SignIn.php'>Go back</a>");
    }
    
    // Check if user exists by username or email
    $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['loggedin'] = true;
            
            // Redirect to home page
            header('Location: ../donate.php');
            exit();
        } else {
            echo "Error: Invalid password. <a href='../SignIn.php'>Try again</a>";
        }
    } else {
        echo "Error: User not found. <a href='../SignIn.php'>Try again</a>";
    }
    
    $stmt->close();
    $conn->close();
}

// If not a POST request, redirect to signup page
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../SignUp.php');
    exit();
}
?>
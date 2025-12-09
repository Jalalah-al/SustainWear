<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: SignIn.php");
    exit();
}

$user_id = $_SESSION['user_id'];

require 'backend/connect.php';
$conn = connectDB();

$success = '';
$error = '';
$user_data = [];
$account_data = [];

// Fetch current user data
$user_sql = "SELECT username, email FROM users WHERE user_id = ?";
if ($stmt = $conn->prepare($user_sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $stmt->close();
}

// Fetch current account data
$account_sql = "SELECT account_id, profilePicture, bio FROM accounts WHERE user_id = ?";
if ($stmt = $conn->prepare($account_sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $account_data = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bio = trim($_POST['bio'] ?? '');
    
    // Handle profile picture upload
    $profilePicture = $account_data['profilePicture'] ?? null;
    
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = $_FILES['profilePicture']['type'];
        $max_file_size = 2 * 1024 * 1024; // 2MB
        
        // Check file type
        if (in_array($file_type, $allowed_types)) {
            // Check file size
            if ($_FILES['profilePicture']['size'] <= $max_file_size) {
                $upload_dir = 'uploads/profile_pictures/';
                
                // Create directory if it doesn't exist
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                // Generate unique filename
                $file_ext = strtolower(pathinfo($_FILES['profilePicture']['name'], PATHINFO_EXTENSION));
                $filename = 'profile_' . $user_id . '_' . time() . '.' . $file_ext;
                $filepath = $upload_dir . $filename;
                
                // Move uploaded file
                if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $filepath)) {
                    // Delete old profile picture if exists and is in uploads directory
                    if (!empty($account_data['profilePicture']) && 
                        file_exists($account_data['profilePicture']) &&
                        strpos($account_data['profilePicture'], 'uploads/profile_pictures/') !== false) {
                        unlink($account_data['profilePicture']);
                    }
                    
                    $profilePicture = $filepath;
                } else {
                    $error = "Failed to upload image. Please try again.";
                }
            } else {
                $error = "File size too large. Maximum size is 2MB.";
            }
        } else {
            $error = "Invalid file type. Only JPG, PNG, GIF and WebP are allowed.";
        }
    }
    
    // If no file was uploaded, keep existing picture
    if ($profilePicture === null && !empty($account_data['profilePicture'])) {
        $profilePicture = $account_data['profilePicture'];
    }
    
    // If profile picture is empty, set to null
    if (empty($profilePicture)) {
        $profilePicture = null;
    }
    
    // Validate bio length
    if (strlen($bio) > 500) {
        $error = "Bio is too long. Maximum 500 characters allowed.";
    } else {
        // Check if account exists
        if (empty($account_data['account_id'])) {
            // Create new account record
            $insert_sql = "INSERT INTO accounts (user_id, bio, profilePicture) VALUES (?, ?, ?)";
            if ($stmt = $conn->prepare($insert_sql)) {
                $stmt->bind_param("iss", $user_id, $bio, $profilePicture);
                if ($stmt->execute()) {
                    $success = 'Profile created successfully!';
                } else {
                    $error = "Failed to create profile: " . $stmt->error;
                }
                $stmt->close();
            }
        } else {
            // Update existing account
            $update_sql = "UPDATE accounts SET bio = ?, profilePicture = ? WHERE user_id = ?";
            if ($stmt = $conn->prepare($update_sql)) {
                $stmt->bind_param("ssi", $bio, $profilePicture, $user_id);
                if ($stmt->execute()) {
                    $success = 'Profile updated successfully!';
                } else {
                    $error = "Failed to update profile: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/editProfile.css">
    <link rel="icon" href="images/logo.png" sizes="16x16">
    <link rel="stylesheet" href="css/profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Edit Profile | SustainWear</title>
    
       
    
</head>
<body>

   <?php 
        include 'headerAndFooter/loggedInHeader.php';
   ?>

    <main class="profile-main">
        <div class="container">
            <div class="edit-profile-container">
                <h1 class="edit-profile-title">Edit Your Profile</h1>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data" class="edit-profile-form">
                    <div class="form-section">
                        <h3 class="section-title">Profile Picture</h3>
                        
                        <div class="current-photo-container">
                            <?php if (!empty($account_data['profilePicture'])): ?>
                                <img src="<?php echo htmlspecialchars($account_data['profilePicture']); ?>" alt="Current Profile Picture" class="current-photo" id="currentPhoto">
                            <?php else: ?>
                                <div class="photo-placeholder" id="photoPlaceholder">
                                    <?php 
                                        $initials = '';
                                        if (!empty($user_data['username'])) {
                                            $words = explode(' ', $user_data['username']);
                                            foreach ($words as $word) {
                                                $initials .= strtoupper(substr($word, 0, 1));
                                            }
                                            echo substr($initials, 0, 2);
                                        }
                                    ?>
                                </div>
                            <?php endif; ?>
                            <img id="photoPreview" class="photo-preview" alt="Preview">
                        </div>
                        
                        <div class="file-upload-wrapper">
                            <label for="profilePicture" class="file-label">
                                📁 Choose New Photo
                            </label>
                            <input type="file" id="profilePicture" name="profilePicture" accept="image/*" class="file-input" onchange="previewImage(event)">
                            <small class="file-hint">JPG, PNG, GIF or WebP. Max 2MB</small>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Account Information</h3>
                        
                        <div class="form-group">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" class="form-input" value="<?php echo htmlspecialchars($user_data['username'] ?? ''); ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" class="form-input" value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea id="bio" name="bio" class="form-textarea" rows="4" placeholder="Tell us a bit about yourself..." oninput="updateCharCount()"><?php echo htmlspecialchars($account_data['bio'] ?? ''); ?></textarea>
                            <div class="char-counter">
                                <span id="charCount"><?php echo strlen($account_data['bio'] ?? ''); ?></span>/500 characters
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Save Changes</button>
                        <a href="profile.php" class="btn-cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

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
                        <a href="#">How it Works</a>
                        <a href="#">For Donors</a>
                        <a href="#">For Charities</a>
                    </div>
                    <div class="link-group">
                        <h4>Company</h4>
                        <a href="AboutUs.html">About Us</a>
                        <a href="#">Impact</a>
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
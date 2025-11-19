<?php
// Process form submission first
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "sustainwear";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $clothing_type = $_POST['clothingType'];
    $item_condition = $_POST['condition'];
    $description = $_POST['description'];
    $account_id = 1; // Required by your table

    // Create uploads directory if it doesn't exist
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $image_path = '';
    if (!empty($_FILES['images']['name'][0])) {
        // Only use first image since your column is varchar(255)
        $file_name = $_FILES['images']['name'][0];
        $file_tmp = $_FILES['images']['tmp_name'][0];
        
        $new_file_name = uniqid() . "_" . $file_name;
        $destination = $upload_dir . $new_file_name;
  
        if (move_uploaded_file($file_tmp, $destination)) {
            $image_path = $destination;
        }
    }

    // CORRECT SQL - matches your table structure
    $sql = "INSERT INTO donations (account_id, clothing_type, item_condition, description, images) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $account_id, $clothing_type, $item_condition, $description, $image_path);

    if ($stmt->execute()) {
        $success_message = "Donation submitted successfully!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/donate.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Donate Clothes | SustainWear</title>
</head>
<body>

    <header class="header">
        <nav class="navbar">
            <div class="nav-brand">
                <span class="logo"><img src="images/logo.png" id="logo" alt="SustainWear Logo"></span>
                <h2>SustainWear</h2>
            </div>
            <ul class="nav-menu">
                <li><a href="donate.php" class="nav-link active">Start Donation</a></li>
                <li><a href="trackDonations.php" class="nav-link">Track Donations</a></li>
                <li><a href="profile.php" class="nav-link">Profile</a></li>
            </ul>

            <div class="nav-actions">
                <a href="SignIn.php" class="btn-login">Sign In</a>
                <a href="SignUp.php" class="btn-primary">Get Started</a>
            </div>
        </nav>
    </header>

    <main class="donate-main">
        <div class="container">
            <div class="donate-header">
                <h1>Donate Your Clothes</h1>
                <p>Give your pre-loved clothing a new life and make a sustainable impact</p>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success" style="background: #d4ffd4; padding: 15px; border-radius: 5px; margin: 20px 0; border: 1px solid #00ff00;">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error" style="background: #ffd4d4; padding: 15px; border-radius: 5px; margin: 20px 0; border: 1px solid #ff0000;">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="donate-content">
                <div class="donate-form-container">
                    <!-- FORM SUBMITS TO THIS SAME PAGE -->
                    <form class="donate-form" method="POST" enctype="multipart/form-data">
                       
                        <div class="form-section">
                            <h3>Clothing Details</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="clothingType">Clothing Type *</label>
                                    <select id="clothingType" name="clothingType" required>
                                        <option value="">Select type</option>
                                        <option value="shirt">Shirt/Top</option>
                                        <option value="pants">Pants/Trousers</option>
                                        <option value="dress">Dress</option>
                                        <option value="jacket">Jacket/Coat</option>
                                        <option value="sweater">Sweater/Hoodie</option>
                                        <option value="shoes">Shoes</option>
                                        <option value="accessories">Accessories</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="condition">Condition *</label>
                                    <select id="condition" name="condition" required>
                                        <option value="">Select condition</option>
                                        <option value="excellent">Excellent - Like new</option>
                                        <option value="good">Good - Lightly used</option>
                                        <option value="fair">Fair - Visible wear</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Description *</label>
                                <textarea id="description" name="description" rows="3" placeholder="Describe the clothing item, brand, color, and material..." required></textarea>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3>Upload Photos</h3>
                            <div class="image-upload-container">
                                <div class="upload-area" id="uploadArea">
                                    <div class="upload-icon"></div>
                                    <p>Click to upload photos</p>
                                    <span>PNG, JPG up to 5MB</span>
                                    <input type="file" id="imageUpload" name="images[]" accept="image/*" multiple style="display: none;">
                                </div>
                                <div class="image-preview" id="imagePreview"></div>
                            </div>
                        </div>

                        <button type="submit" class="btn-donate">Submit Donation</button>
                    </form>
                </div>

                <!-- Rest of your HTML remains the same -->
                <div class="donate-sidebar">
                    <div class="impact-calculator">
                        <h3>Your Impact</h3>
                        <div class="impact-item">
                            <div class="impact-icon"></div>
                            <div class="impact-text">
                                <h4>Environmental Savings</h4>
                                <p>Each item saves approximately 2.5kg of CO₂</p>
                            </div>
                        </div>
                        <div class="impact-item">
                            <div class="impact-icon"></div>
                            <div class="impact-text">
                                <h4>Landfill Reduction</h4>
                                <p>Keep clothing out of landfills</p>
                            </div>
                        </div>
                        <div class="impact-item">
                            <div class="impact-icon"></div>
                            <div class="impact-text">
                                <h4>Community Support</h4>
                                <p>Your donations help people in need</p>
                            </div>
                        </div>
                    </div>

                    <div class="donation-tips">
                        <h3>Donation Tips</h3>
                        <ul>
                            <li>Take clear, well-lit photos</li>
                            <li>Ensure clothes are clean and dry</li>
                            <li>Note any damages or stains</li>
                            <li>Include brand and size if known</li>
                        </ul>
                    </div>
                </div>
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

    <script src="js/donate.js"></script>
</body>
</html>
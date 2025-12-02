<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    include 'backend/checkSession.php';
}
else {
    header("Location: SignIn.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'backend/connect.php';
    $conn = connectDB();

    $clothing_type = $_POST['clothingType'];
    $item_condition = $_POST['condition'];
    $description = $_POST['description'];
    $account_id = $user_id;

    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $image_path = '';
    if (!empty($_FILES['images']['name'][0])) {
        $file_name = $_FILES['images']['name'][0];
        $file_tmp = $_FILES['images']['tmp_name'][0];
        $new_file_name = uniqid() . "_" . $file_name;
        $destination = $upload_dir . $new_file_name;
        
        if (move_uploaded_file($file_tmp, $destination)) {
            $image_path = $destination;
        }
    }

    $sql = "INSERT INTO donations (account_id, clothing_type, item_condition, description, images, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())";

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
    <script src="js/donate.js"></script>
    <link rel="icon" href="images/logo.png" sizes="16x16">
    <link rel="stylesheet" href="css/donate.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Donate Clothes | SustainWear</title>
</head>
<body>

   <?php 
        if(isset($user_id)){
            include 'headerAndFooter/loggedInHeader.php';
        }
        else{
            include 'headerAndFooter/header.php';
        }
   ?>

<?php if($userType === 'charityStaff'): ?>
<main class="donate-main">
    <div class="container">
        <div class="donate-header">
            <h1>Charity Staff Panel</h1>
            <p>Review donations, create listings, and manage activity</p>
        </div>

        <div class="donate-content">

            <div class="donate-form-container">
               
                <form class="donate-form" id="reviewDonationsForm">
                    <div class="form-section">
                        <h3>Review Incoming Donations</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="reviewItem">Item</label>
                                <select id="reviewItem" name="reviewItem">
                                    <option value="">Select pending item</option>
                                    <option value="shirt">Blue Shirt — Good</option>
                                    <option value="shoes">Running Shoes — Excellent</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="decision">Decision</label>
                                <select id="decision" name="decision">
                                    <option value="">Choose</option>
                                    <option value="approve">Approve</option>
                                    <option value="reject">Reject</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="staffNotes">Staff Notes</label>
                            <textarea id="staffNotes" rows="3" placeholder="Add notes about approval/rejection…"></textarea>
                        </div>

                        <button type="submit" class="btn-donate" id="charity-btn">Submit Review</button>
                    </div>
                </form>
            </div>

        
            <div class="donate-sidebar">

                <div class="impact-calculator">
                    <h3>Staff Dashboard</h3>

                    <div class="impact-item">
                        <div class="impact-icon"></div>
                        <div class="impact-text">
                            <h4>Total Donations</h4>
                            <p>142 items received</p>
                        </div>
                    </div>

                    <div class="impact-item">
                        <div class="impact-icon"></div>
                        <div class="impact-text">
                            <h4>Pending Reviews</h4>
                            <p>24 items awaiting approval</p>
                        </div>
                    </div>

                    <div class="impact-item">
                        <div class="impact-icon"></div>
                        <div class="impact-text">
                            <h4>Approved Listings</h4>
                            <p>118 items available</p>
                        </div>
                    </div>
                </div>

                <div class="donation-tips">
                    <h3>Staff Tips</h3>
                    <ul>
                        <li>Double-check item conditions</li>
                        <li>Add helpful notes for buyers</li>
                        <li>Ensure photos are clear</li>
                        <li>Reject items with hygiene issues</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
<?php else: ?>


    <main class="donate-main">
        <div class="container">
            <div class="donate-header">
                <h1>Donate Your Clothes</h1>
                <p>Give your pre-loved clothing a new life and make a sustainable impact</p>
            </div>

        
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
<?php endif; ?>

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
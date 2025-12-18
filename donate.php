<?php

include 'backend/checkSession.php';
// session_start();

// if (!isset($_SESSION['user_id'])) {
//     header("Location: SignIn.php");
//     exit();
// }

// $user_id = $_SESSION['user_id'];

// require 'backend/connect.php';
// $conn = connectDB();

$error_message = "";
$success_message = "";

if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}


// THIS PART HANDLES SUBMITTING DONATIONS TO THE DATABASE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $get_account_sql = "SELECT account_id FROM accounts WHERE user_id = ?";
    $get_stmt = $conn->prepare($get_account_sql);
    $get_stmt->bind_param("i", $user_id);
    $get_stmt->execute();
    $get_stmt->bind_result($account_id);
    $get_stmt->fetch();
    $get_stmt->close();
    
    if (!$account_id) {
        $create_account_sql = "INSERT INTO accounts (user_id) VALUES (?)";
        $create_stmt = $conn->prepare($create_account_sql);
        $create_stmt->bind_param("i", $user_id);
        if ($create_stmt->execute()) {
            $account_id = $create_stmt->insert_id;
            $success_message = "Account created successfully! Now processing your donation...";
        } else {
            $error_message = "Error creating account: " . $create_stmt->error;
        }
        $create_stmt->close();
    }
    
    if ($account_id) {
        $clothing_type = $_POST['clothingType'];
        $item_condition = $_POST['condition'];
        $description = $_POST['description'];
        
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

        // THIS IS THE IMPORTANT LINE THAT SAVES TO DATABASE
        // IT INSERTS THE DONATION WITH STATUS 'pending'
        $sql = "INSERT INTO donations (account_id, clothing_type, item_condition, description, images, created_at, status) 
                VALUES (?, ?, ?, ?, ?, NOW(), 'pending')";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $account_id, $clothing_type, $item_condition, $description, $image_path);

        if ($stmt->execute()) {
            $success_message = "Donation submitted successfully!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
    
}




// $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/donate.js"></script>
    <link rel="icon" href="images/logo.png" sizes="16x16">
    <link rel="stylesheet" href="css/donationHistory.css">
    <link rel="stylesheet" href="css/donate.css">
    <script src = "js/staffDonations.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Donate Clothes | SustainWear</title>
</head>
<body>

   <?php 
   if($isLoggedIn){
        include 'headerAndFooter/loggedInHeader.php';
   }
   else{
        header("Location: SignIn.php");
   }
   ?>


<?php if($userType === 'charityStaff'): ?>
<main class="donate-main">
    <div class="container">
        <div class="donate-header">
            <h1>Charity Staff Panel</h1>
            <p>Review donations, create listings, and manage activity</p>
             <button onclick ="viewDonationRequests()" id="viewDonationRequests">View Donation Requests <span><?php echo $pendingDonationsCount ?? 0; ?></span> </button>
        </div>

<?php if (!empty($success_message)): ?>
    <div class="alert alert-success" style="background: #d4ffd4; color: #006600; padding: 15px; border-radius: 5px; margin: 20px 0; border: 1px solid #00cc00;">
        ✅ <?php echo htmlspecialchars($success_message); ?>
    </div>
<?php endif; ?>

<?php if (!empty($error_message)): ?>
    <div class="alert alert-error" style="background: #ffd4d4; color: #660000; padding: 15px; border-radius: 5px; margin: 20px 0; border: 1px solid #cc0000;">
        ❌ <?php echo htmlspecialchars($error_message); ?>
    </div>
<?php endif; ?>
       

    <div class="donate-content">

<!-- DONATION REVIEW FORM SECTION -->
    <div id="donationsReviewForm">

        <div class="donate-form-container">
    <form class="donate-form" id="reviewDonationsForm" method="POST" action="backend/processReview.php">
        <div class="form-section">
            <h3>Review Incoming Donations</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="reviewItem">Item</label>
                    <select id="reviewItem" name="donations_ID" required>
                        <option value="" disabled selected>Select pending item</option>
                        <?php if (!empty($pendingDonations)): ?>
                            <?php foreach ($pendingDonations as $donation): ?>
                                <?php 
                                $displayText = htmlspecialchars($donation['clothing_type']) . " — " . 
                                            htmlspecialchars(ucfirst($donation['item_condition']));
                                ?>
                                <option value="<?php echo $donation['donations_ID']; ?>">
                                    <?php echo $displayText; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">No pending donations</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="decision">Decision</label>
                    <select id="decision" name="decision" required>
                        <option value="" disabled selected>Choose</option>
                        <option value="approve">Approve</option>
                        <option value="reject">Reject</option>
                    </select>
                </div>
            </div>
           
            <div class="form-group">
                <label for="staffNotes">Staff Notes</label>
                <textarea id="staffNotes" name="staff_notes" rows="3" 
                          placeholder="Add notes about approval/rejection… (optional)"></textarea>
            </div>
            <button type="submit" class="btn-donate" id="charity-btn">Submit Review</button>
        </div>
    </form>
</div>
</div>


            <!-- staff dashboard -->
        
            <div class="donate-sidebar" id="staffDashboard">
                <div class="impact-calculator">
                    <h3>Staff Dashboard</h3>
                    <div class="impact-item">
                        <div class="impact-icon"></div>
                        <div class="impact-text">
                            <h4>Total Donations</h4>
                            <p> <span><?php echo $totalDonationsCount; ?></span> items received</p>
                        </div>
                    </div>
                    <div class="impact-item">
                        <div class="impact-icon"></div>
                        <div class="impact-text">
                            <h4>Pending Reviews</h4>
                            <p> <span><?php echo $pendingDonationsCount; ?></span> items awaiting approval</p>
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


<!-- DONATION FILTER SECTION -->

   <div class="history-controls">
                <div class="search-box">
                    <input type="text" placeholder="Search donations..." class="search-input" id="search-input">
                    <button class="search-btn">🔍</button>
                </div>
                <div class="filter-group">
                    <select class="filter-select" id="type-filter">
                        <option value="all">All Types</option>
                        <option value="shirt">Shirts</option>
                        <option value="pants">Pants</option>
                        <option value="dress">Dresses</option>
                        <option value="jacket">Jackets</option>
                        <option value="shoes">Shoes</option>
                        <option value="sweater">Sweaters</option>
                        <option value="t-shirt">T-shirts</option>
                    </select>
                </div>
            </div>
</div>

    <div id="filterDonationsSection">
       <div class="donate-form-container">
   <div class="donate-form">
        <h3>All Donations</h3>

    <div class="form-group">
        
<?php if ($hasApprovedDonations): ?>
    <div class="approved-donations-container">
        <?php foreach ($acceptedDonations as $donation): ?>
            <div class="donation-box">
                <div class="donation-header">
                    <h4>Donation ID: <?php echo htmlspecialchars($donation['donations_ID']); ?></h4>
                    <span class="badge approved">APPROVED</span>
                </div>
                
                <div class="donation-body">
                    <div class="detail-group">
                        <div class="detail">
                            <strong>Item Condition:</strong>
                            <?php echo htmlspecialchars($donation['item_condition']); ?>
                        </div>
                        <div class="detail">
                            <strong>Clothing Type:</strong>
                            <?php echo htmlspecialchars($donation['clothing_type']); ?>
                        </div>
                        <div class="detail">
                            <strong>Submitted:</strong>
                            <?php echo date('F j, Y g:i A', strtotime($donation['created_at'])); ?>
                        </div>
                       
                    </div>
                    
                    <?php if (!empty($donation['description'])): ?>
                        <div class="description">
                            <strong>Description:</strong>
                            <p><?php echo htmlspecialchars($donation['description']); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($donation['images'])): ?>
                        <div class="image-section">
                            <strong>Images:</strong>
                            <?php 
                            $imagePath = $donation['images'];
                            if (file_exists($imagePath) && is_file($imagePath)):
                            ?>
                                <div class="donation-image">
                                    <img src="<?php echo $imagePath; ?>" 
                                         alt="Donated item" 
                                         style="max-width: 300px; max-height: 200px; border-radius: 5px;">
                                </div>
                            <?php else: ?>
                                <p>Image path: <?php echo htmlspecialchars($imagePath); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

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
        
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success" style="background: #d4ffd4; padding: 15px; border-radius: 5px; margin: 20px 0; border: 1px solid #00ff00;">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
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

    <?php include 'headerAndFooter/footer.php'; ?>
    
</body>
</html>
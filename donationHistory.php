<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: SignIn.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get user ID from session

require 'backend/connect.php';
$conn = connectDB(); // Connect to database

// Get account_id from accounts table using user_id
$account_id = null;
$account_query = "SELECT account_id FROM accounts WHERE user_id = ?";
if ($stmt = $conn->prepare($account_query)) {
    $stmt->bind_param("i", $user_id); // Use session user_id to find account_id
    $stmt->execute();
    $stmt->bind_result($account_id);
    $stmt->fetch();
    $stmt->close();
}

$userDonations = [];
$totalDonations = 0;

// If account_id was found, fetch donations for this account
if ($account_id) {
    // THIS IS THE IMPORTANT QUERY THAT FETCHES DONATIONS:
    // It gets all donations where account_id matches the user's account_id
    $stmt = $conn->prepare("SELECT * FROM donations WHERE account_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $account_id); // Use the account_id we found
    $stmt->execute();
    $result = $stmt->get_result();

    // Store each donation in an array
    while ($row = $result->fetch_assoc()) {
        $userDonations[] = $row;
    }
    $totalDonations = count($userDonations);
    $stmt->close();
}

$conn->close(); // Close database connection

$isLoggedIn = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="images/logo.png" sizes="16x16">
    <link rel="stylesheet" href="css/donationHistory.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Donation History | SustainWear</title>
    <style>
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }
        
        .status-badge.pending {
            background-color: #FFF3CD;
            color: #856404;
            border: 1px solid #FFEEBA;
        }
        
        .status-badge.completed {
            background-color: #D4EDDA;
            color: #155724;
            border: 1px solid #C3E6CB;
        }
        
        .status-badge.rejected {
            background-color: #F8D7DA;
            color: #721C24;
            border: 1px solid #F5C6CB;
        }
        
        .status-badge.approved {
            background-color: #D1ECF1;
            color: #0C5460;
            border: 1px solid #BEE5EB;
        }
    </style>
</head>
<body>

    <?php 
     include 'headerAndFooter/loggedInHeader.php';
   
   ?>

    <main class="history-main">
        <div class="container">
            <div class="history-header">
                <h1>Your Donation History</h1>
                <p>Track your sustainable impact and see all your past donations</p>
            </div>

            <div class="stats-summary">
                <div class="stat-card">
                    <div class="stat-icon">📦</div>
                    <div class="stat-info">
                        <h3 id="total-donations"><?php echo $totalDonations; ?></h3>
                        <p>Total Donations</p>
                    </div>
                </div>
            </div>

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

            <div class="donations-list" id="donations-list">
                <?php if ($totalDonations === 0): ?>
                    <div class="no-donations">
                        <h3>No Donations Yet</h3>
                        <p>You haven't made any donations yet. Start making a difference today!</p>
                        <a href="donate.php" class="btn-donate">Make Your First Donation</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($userDonations as $donation): ?>
                        <?php
                        $emoji = '👕';
                        $type = strtolower($donation['clothing_type']);
                        if ($type == 'pants') $emoji = '👖';
                        if ($type == 'dress') $emoji = '👗';
                        if ($type == 'jacket') $emoji = '🧥';
                        if ($type == 'shoes') $emoji = '👟';
                        if ($type == 'sweater') $emoji = '🧥';
                        if ($type == 't-shirt') $emoji = '👕';
                        
                        $date = date('M j, Y', strtotime($donation['created_at']));
                        
                        $image = 'images/placeholder-clothing.jpg';
                        if (!empty($donation['images'])) {
                            $image = $donation['images'];
                        }
                        
                        $status = isset($donation['status']) ? strtolower($donation['status']) : 'pending';
                        $status_text = ucfirst($status);
                        $status_class = $status;
                        
                        if ($status == 'pending') {
                            $status_text = 'Pending Review';
                        } elseif ($status == 'approved') {
                            $status_text = 'Approved';
                        } elseif ($status == 'rejected') {
                            $status_text = 'Rejected';
                        } elseif ($status == 'completed') {
                            $status_text = 'Completed';
                        }
                        ?>
                        
                        <div class="donation-item" data-type="<?php echo htmlspecialchars($type); ?>">
                            <div class="donation-image">
                                <img src="<?php echo $image; ?>" 
                                     alt="<?php echo htmlspecialchars($donation['clothing_type']); ?>"
                                     onerror="this.src='images/placeholder-clothing.jpg'">
                            </div>
                            <div class="donation-details">
                                <h3><?php echo htmlspecialchars(ucwords($donation['clothing_type'])); ?></h3>
                                <div class="donation-meta">
                                    <span class="donation-type"><?php echo $emoji . ' ' . htmlspecialchars(ucwords($donation['clothing_type'])); ?></span>
                                    <span class="donation-condition">Condition: <?php echo htmlspecialchars($donation['item_condition']); ?></span>
                                    <span class="donation-date">Donated: <?php echo $date; ?></span>
                                </div>
                                <p class="donation-description"><?php echo htmlspecialchars($donation['description']); ?></p>
                            </div>
                            <div class="donation-status">
                                <span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                <div class="impact-info">
                                    <?php if ($status == 'pending'): ?>
                                        <small>Your donation is awaiting review by charity staff.</small>
                                    <?php elseif ($status == 'approved'): ?>
                                        <small>Your donation has been approved and will be listed.</small>
                                    <?php elseif ($status == 'rejected'): ?>
                                        <small>Your donation did not meet our guidelines.</small>
                                    <?php elseif ($status == 'completed'): ?>
                                        <small>Thank you for your donation!</small>
                                    <?php else: ?>
                                        <small>Status: <?php echo $status_text; ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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

    <script src="js/donationHistory.js"></script>
</body>
</html>
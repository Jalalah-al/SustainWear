<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

require 'backend/connect.php';
$conn = connectDB();

$stmt = $conn->prepare("SELECT * FROM donations WHERE account_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$userDonations = [];
$totalDonations = 0;

while ($row = $result->fetch_assoc()) {
    $userDonations[] = $row;
}
$totalDonations = count($userDonations);

$stmt->close();
$conn->close();

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
                                <span class="status-badge completed">Completed</span>
                                <div class="impact-info">
                                    <small>Thank you for your donation!</small>
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
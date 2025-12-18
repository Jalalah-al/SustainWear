<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: SignIn.php");
    exit();
}

$user_id = $_SESSION['user_id'];

require 'backend/connect.php';
$conn = connectDB();

$user_data = [];
$user_sql = "SELECT username, email, userType FROM users WHERE user_id = ?";
if ($stmt = $conn->prepare($user_sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $stmt->close();
}

$account_data = [];
$account_sql = "SELECT account_id, profilePicture, bio FROM accounts WHERE user_id = ?";
if ($stmt = $conn->prepare($account_sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $account_data = $result->fetch_assoc();
    $stmt->close();
}

$stats = [
    'total_donations' => 0,
    'pending_donations' => 0,
    'approved_donations' => 0,
    'rejected_donations' => 0
];

if (!empty($account_data['account_id'])) {
    $account_id = $account_data['account_id'];
    

    $total_sql = "SELECT COUNT(*) as count FROM donations WHERE account_id = ?";
    if ($stmt = $conn->prepare($total_sql)) {
        $stmt->bind_param("i", $account_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stats['total_donations'] = $row['count'] ?? 0;
        $stmt->close();
    }
    
    $pending_sql = "SELECT COUNT(*) as count FROM donations WHERE account_id = ? AND status = 'pending'";
    if ($stmt = $conn->prepare($pending_sql)) {
        $stmt->bind_param("i", $account_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stats['pending_donations'] = $row['count'] ?? 0;
        $stmt->close();
    }
    
    $approved_sql = "SELECT COUNT(*) as count FROM donations WHERE account_id = ? AND status = 'approved'";
    if ($stmt = $conn->prepare($approved_sql)) {
        $stmt->bind_param("i", $account_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stats['approved_donations'] = $row['count'] ?? 0;
        $stmt->close();
    }
}

$recent_donations = [];
if (!empty($account_data['account_id'])) {
    $recent_sql = "SELECT * FROM donations WHERE account_id = ? ORDER BY created_at DESC LIMIT 3";
    if ($stmt = $conn->prepare($recent_sql)) {
        $stmt->bind_param("i", $account_data['account_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $recent_donations[] = $row;
        }
        $stmt->close();
    }
}

$conn->close();

$carbon_saved = $stats['total_donations'] * 2.5; 
$water_saved = $stats['total_donations'] * 2700; 
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="images/logo.png" sizes="16x16">
    <link rel="stylesheet" href="css/profile.css">
    <script src="js/profile.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>My Profile | SustainWear</title>
</head>
<body>

   <?php 
        include 'headerAndFooter/loggedInHeader.php';
   ?>

    <main class="profile-main">
        <div class="container">
            <div class="profile-header">
                <div class="profile-image-section">
                    <div class="profile-image">
                        <?php if (!empty($account_data['profilePicture'])): ?>
                            <img src="<?php echo htmlspecialchars($account_data['profilePicture']); ?>" alt="Profile Picture">
                        <?php else: ?>
                            <div class="profile-initials">
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
                    </div>
                    <button class="edit-photo-btn">✏️ Edit Photo</button>
                </div>
                
                <div class="profile-info">
                    <h1><?php echo htmlspecialchars($user_data['username'] ?? 'User'); ?></h1>
                    <p class="profile-role">🌱 SustainWear Donor</p>
                    
                    <?php if (!empty($account_data['bio'])): ?>
                        <p class="profile-bio"><?php echo htmlspecialchars($account_data['bio']); ?></p>
                    <?php else: ?>
                        <p class="profile-bio">No bio yet. <a href="#" class="edit-link">Add a bio</a></p>
                    <?php endif; ?>
                    
                    <div class="profile-contact">
                        <p>📧 <?php echo htmlspecialchars($user_data['email'] ?? 'No email'); ?></p>
                        <p>👤 Member since: <?php echo date('F Y'); ?></p>
                    </div>
                </div>
                
                <div class="profile-actions">
                    <button class="btn-edit-profile">Edit Profile</button>
                </div>
            </div>

            <div class="stats-overview">
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['total_donations']; ?></h3>
                        <p>Total Donations</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['pending_donations']; ?></h3>
                        <p>Pending</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['approved_donations']; ?></h3>
                        <p>Approved</p>
                    </div>
                </div>
                
            </div>

            <div class="profile-content">
                <div class="profile-left">
                    <div class="impact-section">
                        <h2>Your Environmental Impact</h2>
                       
                    </div>

                    <div class="recent-section">
                        <div class="section-header">
                            <h2>Recent Donations</h2>
                            <a href="donationHistory.php" class="view-all">View All →</a>
                        </div>
                        
                        <?php if (count($recent_donations) > 0): ?>
                            <div class="recent-donations">
                                <?php foreach ($recent_donations as $donation): ?>
                                    <div class="recent-item">
                                        <div class="recent-icon">
                                            <?php
                                            $emoji = '👕';
                                            $type = strtolower($donation['clothing_type']);
                                            if ($type == 'pants') $emoji = '👖';
                                            if ($type == 'dress') $emoji = '👗';
                                            if ($type == 'jacket') $emoji = '🧥';
                                            if ($type == 'shoes') $emoji = '👟';
                                            if ($type == 'sweater') $emoji = '🧥';
                                            if ($type == 't-shirt') $emoji = '👕';
                                            echo $emoji;
                                            ?>
                                        </div>
                                        <div class="recent-details">
                                            <h4><?php echo htmlspecialchars(ucwords($donation['clothing_type'])); ?></h4>
                                            <p class="recent-date"><?php echo date('M j, Y', strtotime($donation['created_at'])); ?></p>
                                            <span class="status-badge <?php echo $donation['status']; ?>">
                                                <?php echo ucfirst($donation['status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-donations">
                                <p>No donations yet. Start making an impact!</p>
                                <a href="donate.php" class="btn-donate">Make Your First Donation</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="profile-right">
                    <div class="quick-actions">
                        <h2>Quick Actions</h2>
                        <div class="action-buttons">
                            <a href="donate.php" class="action-btn">
                                <span class="action-icon">➕</span>
                                <span class="action-text">New Donation</span>
                            </a>
                            
                            <a href="donationHistory.php" class="action-btn">
                                <span class="action-icon">📋</span>
                                <span class="action-text">View History</span>
                            </a>
                            
                            <button class="action-btn" id="edit-profile-btn">
                                <span class="action-icon">✏️</span>
                                <span class="action-text">Edit Profile</span>
                            </button>
                            
                            <button class="action-btn" id="change-password-btn">
                                <span class="action-icon">🔒</span>
                                <span class="action-text">Change Password</span>
                            </button>
                        </div>
                    </div>

            
    </main>

   <?php include 'headerAndFooter/footer.php'; ?>

</body>
</html>
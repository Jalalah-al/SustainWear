 <?php
session_start();
require_once 'backend/connect.php';

if (isset($_SESSION['user_id'])) {
    $isLoggedIn = true;
    $user_ID = $_SESSION['user_id']; 
} else {
    $isLoggedIn = false;
    $user_ID = null;
}

$username = null;
$account_id = null;
$pendingDonationsCount = 0;

if ($isLoggedIn) {
    $conn = connectDB();
    
    if ($conn) {
        $userQuery = mysqli_query($conn, "SELECT u.*, a.* 
                        FROM users AS u
                        INNER JOIN accounts AS a ON u.user_id = a.user_id 
                        WHERE u.user_id = $user_ID LIMIT 1" );

        if ($userQuery && mysqli_num_rows($userQuery) > 0) {
            $result = mysqli_fetch_assoc($userQuery);
            
            $username = $result['username'];
            $account_id = $result['account_id'];
            $email = $result['email'];
            $userType = $result['userType'];
        }
        
$pendingQuery = mysqli_query($conn, "SELECT COUNT(*) as pending_count 
                    FROM donations 
                    WHERE status = 'pending'");
$pendingResult = mysqli_fetch_assoc($pendingQuery);
$pendingDonationsCount = $pendingResult['pending_count'];


$totalQuery = mysqli_query($conn, "SELECT COUNT(*) as total_count 
                    FROM donations");
$totalResult = mysqli_fetch_assoc($totalQuery);
$totalDonationsCount = $totalResult['total_count'];

        if ($account_id) {
            $donationQuery = mysqli_query($conn, "SELECT * 
                                    FROM donations 
                                    WHERE account_id = $account_id 
                                    ORDER BY created_at DESC LIMIT 1");
            
            if ($donationQuery && mysqli_num_rows($donationQuery) > 0) {
                $donationResult = mysqli_fetch_assoc($donationQuery);
                $clothing_type = $donationResult['clothing_type'];
                $item_condition = $donationResult['item_condition'];
                $description = $donationResult['description'];
                $images = $donationResult['images'];
                $created_at = $donationResult['created_at'];
                $status = $donationResult['status'];
            }
        }
        
        $conn->close();
    }
}
?>
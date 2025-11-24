 <?php
session_start();
require_once 'backend/connect.php';

//---- this will check if the user is logged in and get any relevant user info//--

if (isset($_SESSION['user_id'])) {
    $isLoggedIn = true;
    $user_ID = $_SESSION['user_id']; 
} else {
    $isLoggedIn = false;
    $user_ID = null;
}

$username = null;
$account_id = null;


if ($isLoggedIn) {
    $conn = connectDB();
    
    if ($conn) {
        
        $query = mysqli_query($conn, "SELECT *
                        FROM users AS u
                        INNER JOIN accounts AS a ON u.user_id = a.user_id 
                        LEFT JOIN donations AS d ON d.account_id = a.account_id
                        WHERE u.user_id = $user_ID" );

        if ($query && mysqli_num_rows($query) > 0) {
            $result = mysqli_fetch_assoc($query);
            
            $username = $result['username'];
            $account_id = $result['account_id'];
            $email = $result['email'];
            $userType = $result['userType'];
     
        } else {
            echo "Error: No data found for user.";
        }
        
        $conn->close();
    }
}
?>
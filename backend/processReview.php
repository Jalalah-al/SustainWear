<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: SignIn.php");
    exit();
}

require 'connect.php';
$conn = connectDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donation_id = $_POST['donations_ID'] ?? null;
    $decision = $_POST['decision'] ?? null;
    // $staff_notes = $_POST['staff_notes'] ?? ''; //
    
    if (!$donation_id || !$decision) {
        $_SESSION['error_message'] = "Please select an item and make a decision.";
        header("Location: ../donate.php");
        exit();
    }
    
    if ($decision === 'approve') {
        $new_status = 'approved';
    } elseif ($decision === 'reject') {
        $new_status = 'rejected';
    } else {
        $_SESSION['error_message'] = "Invalid decision selected.";
        header("Location: ../donate.php");
        exit();
    }
    
    $sql = "UPDATE donations 
            SET status = ?
            WHERE donations_ID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $donation_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['success_message'] = "Donation has been {$new_status} successfully!";
        } else {
            $_SESSION['error_message'] = "No donation found with that ID.";
        }
    } else {
        $_SESSION['error_message'] = "Error updating donation: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();
header("Location: ../donate.php");
exit();
?>
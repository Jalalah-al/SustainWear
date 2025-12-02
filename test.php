<?php
include 'backend/connect.php';
$conn = connectDB();

echo "<h3>Checking donations table:</h3>";


$result = $conn->query("SELECT * FROM donations");
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Account ID</th>
                <th>Type</th>
                <th>Description</th>
            </tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['donations_ID']}</td>
                <td>{$row['account_id']}</td>
                <td>{$row['clothing_type']}</td>
                <td>{$row['description']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No donations in database";
}

$conn->close();
?>
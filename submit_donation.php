<?php
// Connect to database
$host = "localhost";
$username = "root";
$password = "";
$database = "sustainwear";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$clothing_type = $_POST['clothingType'];
$item_condition = $_POST['condition'];
$description = $_POST['description'];
$urgent = isset($_POST['urgent']) ? 1 : 0;

// Handle image upload
$image_paths = [];
if (!empty($_FILES['images']['name'][0])) {
    $upload_dir = "uploads/";
    
    // Loop through each uploaded file
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $file_name = $_FILES['images']['name'][$key];
        $file_tmp = $_FILES['images']['tmp_name'][$key];
        
        // Generate unique filename
        $new_file_name = uniqid() . "_" . $file_name;
        $destination = $upload_dir . $new_file_name;
        
        // Move uploaded file
        if (move_uploaded_file($file_tmp, $destination)) {
            $image_paths[] = $destination;
        }
    }
}

// Convert image paths to JSON string
$images_json = json_encode($image_paths);

// Insert into database
$sql = "INSERT INTO donations (clothing_type, item_condition, description, images, urgent) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $clothing_type, $item_condition, $description, $images_json, $urgent);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Donation submitted successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
<?php
session_start();
include("db.php");

// Allow both POST and GET for debugging
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if admin is logged in
    if (!isset($_SESSION['admin'])) {
        echo json_encode(['success' => false, 'message' => 'Admin not logged in']);
        exit();
    }
    
    // Get form data (works for both FormData and regular POST)
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
    $description = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $category = isset($_POST['category']) ? mysqli_real_escape_string($conn, $_POST['category']) : 'Veg';
    $image_url = isset($_POST['image_url']) ? mysqli_real_escape_string($conn, $_POST['image_url']) : '';
    $is_special = isset($_POST['is_special']) ? intval($_POST['is_special']) : 0;
    $is_available = isset($_POST['is_available']) ? intval($_POST['is_available']) : 1;
    
    // Validate required fields
    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Name is required']);
        exit();
    }
    
    if ($price <= 0) {
        echo json_encode(['success' => false, 'message' => 'Valid price is required']);
        exit();
    }
    
    // Use default image if none provided
    if (empty($image_url)) {
        $image_url = 'https://via.placeholder.com/300x200?text=' . urlencode($name);
    }
    
    if ($id > 0) {
        // Update existing item
        $sql = "UPDATE menu_items SET 
                name='$name', 
                description='$description', 
                price='$price', 
                category='$category', 
                image_url='$image_url', 
                is_special='$is_special', 
                is_available='$is_available' 
                WHERE id='$id'";
    } else {
        // Insert new item
        $sql = "INSERT INTO menu_items (name, description, price, category, image_url, is_special, is_available) 
                VALUES ('$name', '$description', '$price', '$category', '$image_url', '$is_special', '$is_available')";
    }
    
    if (mysqli_query($conn, $sql)) {
        $new_id = ($id > 0) ? $id : mysqli_insert_id($conn);
        echo json_encode(['success' => true, 'message' => 'Menu item saved', 'id' => $new_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Only POST method allowed']);
}

mysqli_close($conn);
?>
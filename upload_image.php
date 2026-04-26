<?php
session_start();
header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    echo json_encode(['success' => false, 'message' => 'Admin not logged in']);
    exit();
}

// Create uploads folder if it doesn't exist
$target_dir = "uploads/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['image'];
    $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', basename($file['name']));
    $target_file = $target_dir . $file_name;
    
    // Check file type
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($image_file_type, $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Only JPG, JPEG, PNG, GIF & WEBP files are allowed']);
        exit();
    }
    
    // Check file size (max 2MB)
    if ($file['size'] > 2 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'File is too large. Max 2MB']);
        exit();
    }
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        // Return the full URL path
        $image_url = $target_file;
        echo json_encode(['success' => true, 'image_url' => $image_url]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file']);
    }
} else {
    $error_message = isset($_FILES['image']) ? 'Upload error: ' . $_FILES['image']['error'] : 'No image file received';
    echo json_encode(['success' => false, 'message' => $error_message]);
}
?>
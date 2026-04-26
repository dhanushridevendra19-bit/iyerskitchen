<?php
session_start();
include("db.php");

$data = json_decode(file_get_contents('php://input'), true);

// Check if data received
if(!$data) {
    echo json_encode(['success' => false, 'error' => 'No data received']);
    exit();
}

$user_id = $_SESSION['customer'];
$order_number = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);

// Get cart from request data (not from session)
$cart = $data['cart'] ?? [];

if(empty($cart)) {
    echo json_encode(['success' => false, 'error' => 'Cart is empty']);
    exit();
}

// Insert order
$sql = "INSERT INTO orders (order_number, user_id, customer_name, customer_email, customer_phone, delivery_address, subtotal, gst, discount, total_amount, payment_method, order_status, order_date) 
        VALUES ('$order_number', '$user_id', '{$data['name']}', '{$data['email']}', '{$data['phone']}', '{$data['address']}', '{$data['subtotal']}', '{$data['gst']}', '{$data['discount']}', '{$data['total']}', '{$data['payment_method']}', 'pending', NOW())";

if(mysqli_query($conn, $sql)) {
    $order_id = mysqli_insert_id($conn);
    
    // Insert order items from cart data
    foreach($cart as $item) {
        $subtotal_item = $item['price'] * $item['quantity'];
        $product_id = $item['id'];
        $product_name = mysqli_real_escape_string($conn, $item['name']);
        $quantity = $item['quantity'];
        $price = $item['price'];
        
        $item_sql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price, subtotal) 
                     VALUES ('$order_id', '$product_id', '$product_name', '$quantity', '$price', '$subtotal_item')";
        mysqli_query($conn, $item_sql);
    }
    
    // Generate invoice
    $invoice_number = 'INV-' . date('Ymd') . '-' . rand(100, 999);
    mysqli_query($conn, "INSERT INTO invoices (invoice_number, order_id) VALUES ('$invoice_number', '$order_id')");
    
    // Clear cart from session
    unset($_SESSION['cart']);
    
    echo json_encode(['success' => true, 'order_id' => $order_id, 'order_number' => $order_number]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}
?>
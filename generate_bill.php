<?php
session_start();
include("db.php");

if(!isset($_SESSION['admin'])){
    echo json_encode(['success' => false]);
    exit();
}

$customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
$amount = floatval($_POST['amount']);
$gst = $amount * 0.05;
$total = $amount + $gst;
$bill_number = 'BIL-' . date('Ymd') . '-' . rand(100, 999);

$sql = "INSERT INTO bills (bill_number, customer_name, amount, gst, total_amount, bill_date, status) 
        VALUES ('$bill_number', '$customer_name', '$amount', '$gst', '$total', NOW(), 'paid')";

if(mysqli_query($conn, $sql)){
    echo json_encode(['success' => true, 'bill_number' => $bill_number]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}
?>
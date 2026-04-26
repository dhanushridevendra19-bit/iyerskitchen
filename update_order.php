<?php
session_start();
include("db.php");
$id = $_GET['id'];
$status = $_GET['status'];
mysqli_query($conn, "UPDATE orders SET order_status='$status' WHERE id='$id'");
echo json_encode(['success'=>true]);
?>
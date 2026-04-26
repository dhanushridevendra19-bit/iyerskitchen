<?php
session_start();
include("db.php");
if(!isset($_SESSION['admin'])){ echo json_encode(['success'=>false]); exit(); }
$id = $_POST['customerId'];
$name = $_POST['customerName'];
$email = $_POST['customerEmail'];
$phone = $_POST['customerPhone'];
$gender = $_POST['customerGender'];
$address = $_POST['customerAddress'];
if($id){
    mysqli_query($conn, "UPDATE customers SET name='$name', email='$email', phone='$phone', gender='$gender', address='$address' WHERE id='$id'");
} else {
    mysqli_query($conn, "INSERT INTO customers (name, email, phone, gender, address) VALUES ('$name', '$email', '$phone', '$gender', '$address')");
}
echo json_encode(['success'=>true]);
?>
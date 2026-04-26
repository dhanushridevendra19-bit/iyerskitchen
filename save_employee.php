<?php
session_start();
include("db.php");
if(!isset($_SESSION['admin'])){ echo json_encode(['success'=>false]); exit(); }
$emp_id = $_POST['empId'];
$name = $_POST['empName'];
$position = $_POST['empPosition'];
$phone = $_POST['empPhone'];
$salary = $_POST['empSalary'];
$status = $_POST['empStatus'];
mysqli_query($conn, "INSERT INTO employees (employee_id, name, position, phone, salary, status) VALUES ('$emp_id', '$name', '$position', '$phone', '$salary', '$status')");
echo json_encode(['success'=>true]);
?>
<?php
session_start();
include("db.php");
if(!isset($_SESSION['admin'])){ echo json_encode(['success'=>false]); exit(); }
$id = $_POST['salaryId'];
$emp_id = $_POST['salaryEmployeeId'];
$month = $_POST['salaryMonth'];
$basic = $_POST['salaryBasic'];
$allowances = $_POST['salaryAllowances'];
$deductions = $_POST['salaryDeductions'];
$net = $_POST['net_salary'];
$status = $_POST['salaryStatus'];
if($id){
    mysqli_query($conn, "UPDATE salary_records SET employee_id='$emp_id', month='$month', basic_salary='$basic', allowances='$allowances', deductions='$deductions', net_salary='$net', status='$status' WHERE id='$id'");
} else {
    mysqli_query($conn, "INSERT INTO salary_records (employee_id, month, basic_salary, allowances, deductions, net_salary, status) VALUES ('$emp_id', '$month', '$basic', '$allowances', '$deductions', '$net', '$status')");
}
echo json_encode(['success'=>true]);
?>
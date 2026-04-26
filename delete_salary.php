<?php
session_start();
include("db.php");
if(!isset($_SESSION['admin'])){ echo json_encode(['success'=>false]); exit(); }
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM salary_records WHERE id='$id'");
echo json_encode(['success'=>true]);
?>
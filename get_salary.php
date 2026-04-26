<?php
session_start();
include("db.php");
$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM salary_records WHERE id='$id'");
echo json_encode(mysqli_fetch_assoc($result));
?>
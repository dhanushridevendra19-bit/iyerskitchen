<?php
include("db.php");
$bill_no = $_GET['bill_no'];
$bill = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bills WHERE bill_number='$bill_no'"));
header('Content-Type: text/html');
echo "<h2>IYER'S KITCHEN</h2>";
echo "Bill No: " . $bill['bill_number'] . "<br>";
echo "Customer: " . $bill['customer_name'] . "<br>";
echo "Amount: ₹" . $bill['total_amount'];
?>
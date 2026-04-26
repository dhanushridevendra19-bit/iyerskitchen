<?php
include("db.php");
$bill_no = $_GET['bill_no'];
$bill = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bills WHERE bill_number='$bill_no'"));
?>
<!DOCTYPE html>
<html><head><title>Print Bill</title></head>
<body style="font-family:Arial; padding:20px;">
    <h2 style="text-align:center;">IYER'S KITCHEN</h2>
    <p>Bill No: <?php echo $bill['bill_number']; ?></p>
    <p>Customer: <?php echo $bill['customer_name']; ?></p>
    <p>Date: <?php echo $bill['bill_date']; ?></p>
    <p>Amount: ₹<?php echo $bill['amount']; ?></p>
    <p>GST: ₹<?php echo $bill['gst']; ?></p>
    <p><strong>Total: ₹<?php echo $bill['total_amount']; ?></strong></p>
    <script>window.print();</script>
</body>
</html>
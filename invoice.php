<?php
session_start();
include("db.php");

if (!isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit();
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['customer'];

$order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM orders WHERE id='$order_id' AND user_id='$user_id'"));
if(!$order) die("Order not found");

$items = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id='$order_id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice - <?php echo $order['order_number']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; }
        .invoice-box { max-width: 800px; margin: auto; border: 1px solid #eee; padding: 30px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #d35400; }
        .details { margin-bottom: 30px; }
        .details table { width: 100%; }
        .items-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .items-table th { background: #d35400; color: white; }
        .total { text-align: right; margin-top: 20px; font-size: 1.2rem; }
        .footer { text-align: center; margin-top: 40px; color: #666; font-size: 0.8rem; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <h1>IYER'S KITCHEN</h1>
            <p>100% Vegetarian South Indian Food</p>
            <p>Ghatkopar, Mumbai - 400077 | Tel: +91 98765 43210</p>
        </div>

        <div class="details">
            <table>
                <tr>
                    <td width="50%"><strong>Invoice No:</strong> <?php echo $order['order_number']; ?></td>
                    <td><strong>Date:</strong> <?php echo date('d-m-Y', strtotime($order['order_date'])); ?></td>
                </tr>
                <tr>
                    <td><strong>Customer Name:</strong> <?php echo $order['customer_name']; ?></td>
                    <td><strong>Payment Method:</strong> <?php echo ucfirst($order['payment_method']); ?></td>
                </tr>
                <tr>
                    <td><strong>Phone:</strong> <?php echo $order['customer_phone']; ?></td>
                    <td><strong>Order Status:</strong> <?php echo ucfirst($order['order_status']); ?></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Delivery Address:</strong> <?php echo $order['delivery_address']; ?></td>
                </tr>
            </table>
        </div>

        <table class="items-table">
            <thead>
                <tr><th>Item</th><th>Quantity</th><th>Price</th><th>Total</th></tr>
            </thead>
            <tbody>
                <?php while($item = mysqli_fetch_assoc($items)): ?>
                <tr>
                    <td><?php echo $item['product_name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>₹<?php echo $item['price']; ?></td>
                    <td>₹<?php echo $item['subtotal']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="total">
            <p>Subtotal: ₹<?php echo $order['subtotal']; ?></p>
            <p>GST (5%): ₹<?php echo $order['gst']; ?></p>
            <?php if($order['discount'] > 0): ?>
            <p>Discount: -₹<?php echo $order['discount']; ?></p>
            <?php endif; ?>
            <h3>Grand Total: ₹<?php echo $order['total_amount']; ?></h3>
        </div>

        <div class="footer">
            <p>Thank you for ordering with Iyer's Kitchen!</p>
            <p>This is a computer generated invoice.</p>
        </div>

        <div class="no-print" style="text-align: center; margin-top: 30px;">
            <button onclick="window.print()"><i class="fas fa-print"></i> Print Invoice</button>
            <button onclick="window.close()">Close</button>
        </div>
    </div>
</body>
</html>
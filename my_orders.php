<?php
session_start();
include("db.php");

if (!isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['customer'];
$orders = mysqli_query($conn, "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders - Iyer's Kitchen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: #d35400; color: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .header a { color: white; text-decoration: none; background: rgba(255,255,255,0.2); padding: 10px 15px; border-radius: 5px; }
        .order-card { background: white; border-radius: 10px; margin-bottom: 20px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .order-header { background: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; flex-wrap: wrap; }
        .order-status { padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; }
        .status-pending { background: #fef9e7; color: #f39c12; }
        .status-confirmed { background: #e8f4fc; color: #3498db; }
        .status-preparing { background: #fff3e0; color: #d35400; }
        .status-delivered { background: #eafaf1; color: #27ae60; }
        .status-cancelled { background: #fdeded; color: #e74c3c; }
        .order-body { padding: 20px; }
        .order-items { margin-bottom: 20px; }
        .order-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .order-summary { background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 15px; }
        .summary-row { display: flex; justify-content: space-between; padding: 5px 0; }
        .btn { background: #d35400; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; }
        .btn-outline { background: none; border: 1px solid #d35400; color: #d35400; }
        .empty-orders { text-align: center; padding: 50px; background: white; border-radius: 10px; }
        @media (max-width: 768px) { .order-header { flex-direction: column; gap: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-shopping-bag"></i> My Orders</h1>
            <a href="index.php"><i class="fas fa-arrow-left"></i> Back to Menu</a>
        </div>

        <?php if(mysqli_num_rows($orders) > 0): ?>
            <?php while($order = mysqli_fetch_assoc($orders)): ?>
                <?php
                $order_items = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id='{$order['id']}'");
                ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <strong>Order #: <?php echo $order['order_number']; ?></strong>
                            <div style="font-size: 0.85rem; color: #666;"><?php echo date('d M Y, h:i A', strtotime($order['order_date'])); ?></div>
                        </div>
                        <div>
                            <span class="order-status status-<?php echo $order['order_status']; ?>">
                                <?php echo ucfirst($order['order_status']); ?>
                            </span>
                            <span class="order-status" style="background: #e8f4fc; color: #3498db; margin-left: 10px;">
                                <i class="fas fa-<?php echo $order['payment_method'] == 'cash' ? 'money-bill-wave' : 'qrcode'; ?>"></i>
                                <?php echo $order['payment_method'] == 'cash' ? 'Cash on Delivery' : 'Online Payment'; ?>
                            </span>
                        </div>
                    </div>
                    <div class="order-body">
                        <div class="order-items">
                            <?php while($item = mysqli_fetch_assoc($order_items)): ?>
                                <div class="order-item">
                                    <span><?php echo $item['product_name']; ?> x <?php echo $item['quantity']; ?></span>
                                    <span>₹<?php echo $item['subtotal']; ?></span>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <div class="order-summary">
                            <div class="summary-row"><span>Subtotal</span><span>₹<?php echo $order['subtotal']; ?></span></div>
                            <div class="summary-row"><span>GST (5%)</span><span>₹<?php echo $order['gst']; ?></span></div>
                            <?php if($order['discount'] > 0): ?>
                            <div class="summary-row" style="color:green;"><span>Discount</span><span>-₹<?php echo $order['discount']; ?></span></div>
                            <?php endif; ?>
                            <div class="summary-row" style="font-weight: bold; border-top: 1px solid #ddd; margin-top: 8px; padding-top: 8px;">
                                <span>Total Paid</span><span>₹<?php echo $order['total_amount']; ?></span>
                            </div>
                        </div>
                        <div style="margin-top: 15px; text-align: right;">
                            <button class="btn btn-outline" onclick="viewInvoice(<?php echo $order['id']; ?>)"><i class="fas fa-download"></i> Download Invoice</button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-orders">
                <i class="fas fa-shopping-bag" style="font-size: 4rem; color: #ccc;"></i>
                <p style="margin: 20px 0;">No orders yet</p>
                <a href="index.php" class="btn">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function viewInvoice(orderId) {
            window.open('invoice.php?order_id=' + orderId, '_blank');
        }
    </script>
</body>
</html>
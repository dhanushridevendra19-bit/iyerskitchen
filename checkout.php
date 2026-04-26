<?php
session_start();
include("db.php");

if (!isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['customer'];
$sql = "SELECT * FROM customers WHERE id = '$user_id'";
$customer = mysqli_fetch_assoc(mysqli_query($conn, $sql));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Iyer's Kitchen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f5f5f5; padding: 20px; }
        .checkout-container { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .checkout-section { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #d35400; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .payment-options { display: flex; gap: 20px; margin: 20px 0; }
        .payment-option { flex: 1; padding: 20px; border: 2px solid #ddd; border-radius: 10px; text-align: center; cursor: pointer; transition: 0.3s; }
        .payment-option.selected { border-color: #d35400; background: #fff3e0; }
        .payment-option i { font-size: 2rem; color: #d35400; margin-bottom: 10px; }
        .qr-container { text-align: center; margin-top: 20px; display: none; }
        .qr-container img { width: 200px; margin: 10px; border: 2px solid #d35400; border-radius: 10px; padding: 10px; background: white; }
        .order-summary { background: #f8f9fa; padding: 20px; border-radius: 10px; margin-top: 20px; }
        .summary-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .total-row { font-size: 1.2rem; font-weight: bold; color: #d35400; border-top: 2px solid #d35400; margin-top: 10px; padding-top: 10px; }
        .btn { width: 100%; padding: 15px; background: #d35400; color: white; border: none; border-radius: 5px; font-size: 1.1rem; cursor: pointer; margin-top: 20px; }
        .btn:hover { background: #e67e22; }
        @media (max-width: 768px) { .checkout-container { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="checkout-container">
        <!-- Billing Details -->
        <div class="checkout-section">
            <h2><i class="fas fa-file-invoice"></i> Billing Details</h2>
            <form id="billingForm">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" id="name" value="<?php echo $customer['name']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="email" value="<?php echo $customer['email']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" id="phone" value="<?php echo $customer['phone']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Delivery Address</label>
                    <textarea id="address" rows="3" required></textarea>
                </div>
            </form>
        </div>

        <!-- Order Summary & Payment -->
        <div class="checkout-section">
            <h2><i class="fas fa-shopping-cart"></i> Your Order</h2>
            
            <div id="orderItems"></div>
            
            <div class="order-summary">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="subtotal">₹0</span>
                </div>
                <div class="summary-row">
                    <span>GST (5%)</span>
                    <span id="gst">+ ₹0</span>
                </div>
                <div class="summary-row" id="discountRow" style="color: green;">
                    <span>Discount (10% off on ₹500+)</span>
                    <span id="discount">- ₹0</span>
                </div>
                <div class="total-row">
                    <span><strong>Grand Total</strong></span>
                    <span><strong id="grandTotal">₹0</strong></span>
                </div>
            </div>

            <h3>Payment Method</h3>
            <div class="payment-options">
                <div class="payment-option selected" onclick="selectPayment('cash', this)">
                    <i class="fas fa-money-bill-wave"></i>
                    <h4>Cash on Delivery</h4>
                </div>
                <div class="payment-option" onclick="selectPayment('online', this)">
                    <i class="fas fa-qrcode"></i>
                    <h4>Online Payment</h4>
                </div>
            </div>

            <!-- QR Code for Online Payment -->
            <div class="qr-container" id="qrContainer">
                <p><i class="fas fa-qrcode"></i> Scan QR to pay with UPI</p>
                <img id="qrImage" alt="UPI QR Code" style="width: 200px; margin: 10px auto; border: 2px solid #d35400; border-radius: 10px; padding: 10px; background: white;">
                <p style="font-weight: bold;">Amount: ₹<span id="qrAmount">0</span></p>
                <p style="font-size: 12px; color: #666;">Scan with any UPI app</p>
            </div>
            
            <button class="btn" onclick="placeOrder()">
                <i class="fas fa-check"></i> Place Order
            </button>
        </div>
    </div>

    <script>
        // Load cart from sessionStorage and calculate totals
        function loadCartAndCalculate() {
            const cart = JSON.parse(sessionStorage.getItem('cart') || '[]');
            
            console.log("Cart loaded:", cart); // Debug
            
            if(cart.length === 0) {
                document.getElementById('orderItems').innerHTML = '<div class="summary-row" style="color: red;">Your cart is empty. <a href="index.php">Go back to menu</a></div>';
                return;
            }
            
            let subtotal = 0;
            let orderItemsHtml = '';
            
            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;
                orderItemsHtml += `
                    <div class="summary-row">
                        <span>${item.name} x ${item.quantity}</span>
                        <span>₹${itemTotal}</span>
                    </div>
                `;
            });
            
            const gst = subtotal * 0.05;
            const discount = subtotal > 500 ? subtotal * 0.10 : 0;
            const grandTotal = subtotal + gst - discount;
            
            // Update HTML
            document.getElementById('orderItems').innerHTML = orderItemsHtml;
            document.getElementById('subtotal').innerHTML = `₹${subtotal.toFixed(2)}`;
            document.getElementById('gst').innerHTML = `+ ₹${gst.toFixed(2)}`;
            
            if(discount > 0) {
                document.getElementById('discount').innerHTML = `- ₹${discount.toFixed(2)}`;
                document.getElementById('discountRow').style.display = 'flex';
            } else {
                document.getElementById('discountRow').style.display = 'none';
            }
            
            document.getElementById('grandTotal').innerHTML = `₹${grandTotal.toFixed(2)}`;
            
            // Store for order placement
            window.currentCart = cart;
            window.currentSubtotal = subtotal;
            window.currentGst = gst;
            window.currentDiscount = discount;
            window.currentGrandTotal = grandTotal;
        }
        
        let selectedPayment = 'cash';
        
        function selectPayment(method, element) {
            selectedPayment = method;
            document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
            element.classList.add('selected');
            
            const qrContainer = document.getElementById('qrContainer');
            const qrImage = document.getElementById('qrImage');
            const qrAmount = document.getElementById('qrAmount');
            
            if(method === 'online') {
                qrContainer.style.display = 'block';
                const amount = window.currentGrandTotal || 0;
                qrAmount.innerHTML = amount;
                qrImage.src = 'images/scanner.jpeg?' + Date.now();
            } else {
                qrContainer.style.display = 'none';
            }
        }
        
        function placeOrder() {
            if(!window.currentCart || window.currentCart.length === 0) {
                alert('Cart is empty!');
                window.location.href = 'index.php';
                return;
            }
            
            const orderData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                address: document.getElementById('address').value,
                payment_method: selectedPayment,
                cart: window.currentCart,
                subtotal: window.currentSubtotal,
                gst: window.currentGst,
                discount: window.currentDiscount,
                total: window.currentGrandTotal
            };
            
            fetch('process_order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    sessionStorage.removeItem('cart');
                    if (selectedPayment === 'online') {
                        alert('Order placed! Please complete payment by scanning QR code.');
                    } else {
                        alert('Order placed successfully! Payment will be collected on delivery.');
                    }
                    window.location.href = 'my_orders.php';
                } else {
                    alert('Error: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again.');
            });
        }
        
        // Load cart on page load
        loadCartAndCalculate();
    </script>
</body>
</html>
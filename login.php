<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IYER'S KITCHEN - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #d35400, #e67e22);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: white;
            width: 450px;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }
        h2 {
            text-align: center;
            color: #d35400;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
        }
        .tab {
            flex: 1;
            text-align: center;
            padding: 10px;
            cursor: pointer;
            font-weight: 600;
            color: #666;
        }
        .tab.active {
            color: #d35400;
            border-bottom: 3px solid #d35400;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e1e1;
            border-radius: 5px;
            font-size: 16px;
            padding-right: 40px;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #d35400;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 38px;
            cursor: pointer;
            color: #d35400;
            z-index: 10;
        }
        button {
            width: 100%;
            padding: 14px;
            background: #d35400;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #e67e22;
        }
        .error-message {
            background: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .link {
            text-align: center;
            margin-top: 20px;
        }
        .link a {
            color: #d35400;
            text-decoration: none;
            font-weight: 600;
        }
        .link a:hover {
            text-decoration: underline;
        }
        .register-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            text-align: center;
        }
        .register-section p {
            color: #666;
            margin-bottom: 15px;
        }
        .register-btn {
            background: #2c3e50;
            display: inline-block;
            width: auto;
            padding: 12px 30px;
        }
        .register-btn:hover {
            background: #34495e;
        }
    </style>
</head>
<body>
    <?php
    // Show registration success message
    if(isset($_GET['registered'])) {
        echo "<div style='background: #d4edda; color: #155724; padding: 15px; text-align: center; margin-bottom: 20px; border-radius: 5px; width: 450px;'>✅ Registration successful! Please login.</div>";
    }
    // Show error message
    if(isset($_GET['error'])) {
        if($_GET['error'] == 'notfound') {
            echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; text-align: center; margin-bottom: 20px; border-radius: 5px; width: 450px;'>❌ Email not found! Please register first.</div>";
        }
    }
    ?>

    <div class="login-container">
        <h2><i class="fas fa-utensils"></i> IYER'S KITCHEN</h2>
        <div class="subtitle">Authentic South Indian Vegetarian Food</div>
        
        <div class="tabs">
            <div class="tab active" onclick="showTab('customer')">Customer Login</div>
            <div class="tab" onclick="showTab('admin')">Admin Login</div>
        </div>
        
        <!-- Customer Login -->
        <div id="customer-tab" class="tab-content active">
            <form action="customer_login_check.php" method="POST">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" id="customer-password" placeholder="Enter your password" required>
                    <i class="fas fa-eye password-toggle" id="toggle-customer-password"></i>
                </div>
                <button type="submit" name="customer_login">Login</button>
            </form>
        </div>
        
        <!-- Admin Login -->
        <div id="admin-tab" class="tab-content">
            <form action="admin_login.php" method="POST">
                <div class="form-group">
                    <label><i class="fas fa-user-shield"></i> Username</label>
                    <input type="text" name="username" placeholder="Enter username" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" id="admin-password" placeholder="Enter password" required>
                    <i class="fas fa-eye password-toggle" id="toggle-admin-password"></i>
                </div>
                <button type="submit" name="login">Login as Admin</button>
            </form>
        </div>
        
        <!-- Register Section - Always visible -->
        <div class="register-section">
            <p>New customer? Create an account to order food</p>
            <a href="form.html" class="register-btn" style="background: #27ae60; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                <i class="fas fa-user-plus"></i> Register Now
            </a>
        </div>
    </div>

    <script>
        function showTab(tab) {
            document.getElementById('customer-tab').classList.remove('active');
            document.getElementById('admin-tab').classList.remove('active');
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            
            if (tab === 'customer') {
                document.getElementById('customer-tab').classList.add('active');
                document.querySelectorAll('.tab')[0].classList.add('active');
            } else {
                document.getElementById('admin-tab').classList.add('active');
                document.querySelectorAll('.tab')[1].classList.add('active');
            }
        }

        // Toggle password visibility - SINGLE VERSION
        document.addEventListener('DOMContentLoaded', function() {
            // Customer password toggle
            const toggleCustomer = document.getElementById('toggle-customer-password');
            const customerPassword = document.getElementById('customer-password');
            
            if (toggleCustomer && customerPassword) {
                toggleCustomer.addEventListener('click', function() {
                    if (customerPassword.type === 'password') {
                        customerPassword.type = 'text';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    } else {
                        customerPassword.type = 'password';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    }
                });
            }

            // Admin password toggle
            const toggleAdmin = document.getElementById('toggle-admin-password');
            const adminPassword = document.getElementById('admin-password');
            
            if (toggleAdmin && adminPassword) {
                toggleAdmin.addEventListener('click', function() {
                    if (adminPassword.type === 'password') {
                        adminPassword.type = 'text';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    } else {
                        adminPassword.type = 'password';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    }
                });
            }
        });
    </script>
</body>
</html>
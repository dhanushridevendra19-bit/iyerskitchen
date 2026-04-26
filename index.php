<?php
session_start();
if (!isset($_SESSION['customer']) && !isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IYER'S KITCHEN - Authentic Vegetarian South Indian Food</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #d35400;
            --primary-light: #e67e22;
            --secondary: #2c3e50;
            --danger: #e74c3c;
            --shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        body {
            background-color: #f5f7fa;
            color: var(--secondary);
            line-height: 1.6;
        }

        /* Header */
        header {
            background: linear-gradient(to right, var(--primary), var(--primary-light));
            color: white;
            padding: 1rem 2rem;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            font-size: 2.5rem;
        }

        .logo-text h1 {
            font-size: 1.8rem;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 25px;
            align-items: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: 0.3s;
        }

        nav a:hover {
            background-color: rgba(255,255,255,0.2);
        }

        .cart-icon {
            position: relative;
            cursor: pointer;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            min-width: 200px;
            box-shadow: var(--shadow);
            border-radius: 5px;
            z-index: 1;
        }

        .dropdown-content a {
            color: var(--secondary);
            padding: 12px 16px;
            display: block;
        }

        .dropdown-content a:hover {
            background: #f1f1f1;
            color: var(--primary);
        }

        .user-dropdown:hover .dropdown-content {
            display: block;
        }

        /* Hero Section */
        .hero {
            position: relative;
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            z-index: -2;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: -1;
        }

        .hero-content {
            max-width: 800px;
            padding: 2rem;
        }

        .hero h2 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }

        .cta-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 1.1rem;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }

        .cta-btn:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
        }

        /* Today's Special */
        .todays-special {
            background: white;
            padding: 4rem 2rem;
            text-align: center;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
            color: var(--primary);
            font-size: 2.5rem;
        }

        .section-title:after {
            content: '';
            display: block;
            width: 100px;
            height: 4px;
            background: var(--primary);
            margin: 10px auto;
        }

        .special-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .special-item {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            width: 300px;
            transition: 0.3s;
        }

        .special-item:hover {
            transform: translateY(-10px);
        }

        .special-img {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .special-content {
            padding: 1.5rem;
        }

        .price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }

        .add-to-cart {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        /* Menu Section */
        .menu-section {
            padding: 4rem 2rem;
            background: #f9f9f9;
        }

        .menu-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .menu-item {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .menu-img {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .menu-content {
            padding: 1.5rem;
        }

        /* Wishlist Button */
        .wishlist-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: white;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #e74c3c;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: 0.3s;
            z-index: 10;
        }

        .wishlist-btn:hover {
            transform: scale(1.1);
            background: #e74c3c;
            color: white;
        }

        .wishlist-btn.active {
            background: #e74c3c;
            color: white;
        }

        /* Page Content */
        .page-content {
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 2rem;
        }

        .contact-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .contact-card i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            width: 90%;
            max-width: 800px;
            border-radius: 10px;
            overflow: hidden;
        }

        .modal-header {
            background: var(--primary);
            color: white;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .close-modal {
            background: none;
            border: none;
            color: white;
            font-size: 1.8rem;
            cursor: pointer;
        }

        .modal-body {
            padding: 1.5rem;
            max-height: 60vh;
            overflow-y: auto;
        }

        .cart-total {
            padding: 1.5rem;
            background: #f9f9f9;
            display: flex;
            justify-content: space-between;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .modal-footer {
            padding: 1.5rem;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }

        .btn-secondary {
            background: var(--secondary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Cart Item Styles */
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-info h4 {
            font-size: 1.1rem;
            margin-bottom: 5px;
            color: #333;
        }

        .cart-item-info p {
            font-size: 0.9rem;
            color: #666;
        }

        .cart-item-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f5f5f5;
            padding: 5px 12px;
            border-radius: 25px;
        }

        .quantity-btn {
            background: #d35400;
            color: white;
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        .quantity-btn:hover {
            background: #e67e22;
            transform: scale(1.05);
        }

        .quantity-btn.minus {
            background: #e74c3c;
        }

        .quantity-btn.minus:hover {
            background: #c0392b;
        }

        .quantity {
            font-size: 1.1rem;
            font-weight: 600;
            min-width: 30px;
            text-align: center;
        }

        .remove-item {
            background: none;
            border: none;
            color: #e74c3c;
            cursor: pointer;
            font-size: 1.2rem;
            transition: 0.3s;
        }

        .remove-item:hover {
            color: #c0392b;
            transform: scale(1.1);
        }

        .item-total {
            font-weight: 700;
            color: #d35400;
            min-width: 70px;
            text-align: right;
        }

        @media (max-width: 600px) {
            .cart-item {
                flex-direction: column;
                gap: 10px;
            }
            .cart-item-actions {
                width: 100%;
                justify-content: space-between;
            }
        }

        /* Footer */
        footer {
            background: var(--secondary);
            color: rgba(255,255,255,0.8);
            padding: 3rem 2rem 1.5rem;
        }

        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-column h3 {
            color: white;
            margin-bottom: 1.5rem;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column li {
            margin-bottom: 10px;
        }

        .footer-column a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
        }

        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 1rem;
        }

        .social-icons a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .copyright {
            text-align: center;
            margin-top: 3rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 15px;
            }
            
            nav ul {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .hero h2 {
                font-size: 2.2rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <i class="fas fa-utensils logo-icon"></i>
            <div class="logo-text">
                <h1>IYER'S KITCHEN</h1>
                <p>100% Vegetarian South Indian Food</p>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="#" id="home-link">Home</a></li>
                <li><a href="#" id="menu-link">Menu</a></li>
                <li><a href="#" id="about-link">About Us</a></li>
                <li><a href="#" id="contact-link">Contact</a></li>
                <li><a href="#" id="privacy-link">Privacy</a></li>
                <li class="cart-icon" id="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count" id="cart-count">0</span>
                </li>
                <li class="user-dropdown">
                    <button class="user-btn">
                        <i class="fas fa-user"></i>
                        <span>
                            <?php echo isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : 'Login'; ?>
                        </span>
                    </button>
                    <div class="dropdown-content">
    <?php if(isset($_SESSION['customer']) || isset($_SESSION['admin'])): ?>
        <a href="my_orders.php"><i class="fas fa-shopping-bag"></i> My Orders</a>
        <a href="wishlist.php"><i class="fas fa-heart"></i> My Wishlist</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    <?php else: ?>
        <a href="login.php"><i class="fas fa-user"></i> Customer Login</a>
        <a href="admin_login.php"><i class="fas fa-user-shield"></i> Admin Login</a>
        <a href="form.html"><i class="fas fa-user-plus"></i> Register</a>
    <?php endif; ?>
</div>
                </li>
                <?php if(isset($_SESSION['customer']) || isset($_SESSION['admin'])): ?>
                    <li>
                        <a href="logout.php" style="background: #e74c3c;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Home Page -->
    <section id="customer-home">
        <div class="hero">
            <div class="hero-background" style="background-image: url('https://images.unsplash.com/photo-1563379091339-03246963d9d6?w=1920')"></div>
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h2>Authentic South Indian Delights</h2>
                <p>Experience the true taste of South India with our freshly prepared traditional vegetarian dishes.</p>
                <button class="cta-btn" id="order-now-btn">Order Now</button>
            </div>
        </div>

        <div class="todays-special">
            <h2 class="section-title">Today's Special</h2>
            <div class="special-container" id="special-container"></div>
        </div>

        <div class="menu-section">
            <h2 class="section-title">Our Menu</h2>
            <div class="menu-container" id="menu-container"></div>
        </div>
    </section>

    <!-- About Page -->
    <section id="about-page" class="page-content" style="display: none;">
        <h2>About IYER'S KITCHEN</h2>
        <p>Welcome to IYER'S KITCHEN, your destination for authentic vegetarian South Indian cuisine in Mumbai. Established in 2015, we serve traditional recipes passed down through generations.</p>
        <p>Our founder Venkat Iyer started this venture to bring authentic South Indian food to Mumbai while maintaining traditional cooking methods.</p>
        <button class="cta-btn" onclick="document.getElementById('menu-link').click()">View Our Menu</button>
    </section>

    <!-- Contact Page -->
    <section id="contact-page" class="page-content" style="display: none;">
        <h2>Contact Us</h2>
        <div class="contact-info">
            <div class="contact-card">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Location</h3>
                <p>Ghatkopar, Mumbai - 400077</p>
            </div>
            <div class="contact-card">
                <i class="fas fa-phone"></i>
                <h3>Phone</h3>
                <p>+91 98765 43210</p>
            </div>
            <div class="contact-card">
                <i class="fas fa-envelope"></i>
                <h3>Email</h3>
                <p>info@iyerskitchen.com</p>
            </div>
        </div>
    </section>

    <!-- Privacy Page -->
    <section id="privacy-page" class="page-content" style="display: none;">
        <h2>Privacy Policy</h2>
        <p>We value your privacy. Your personal information is used only for order processing and never shared with third parties.</p>
    </section>

    <!-- Cart Modal -->
    <div class="modal" id="cart-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Your Cart</h3>
                <button class="close-modal" id="close-cart">&times;</button>
            </div>
            <div class="modal-body" id="cart-items"></div>
            <div class="cart-total">
                <span>Total:</span>
                <span id="cart-total-price">₹0</span>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" id="continue-shopping">Continue Shopping</button>
                <button class="cta-btn" id="checkout-btn">Checkout</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-column">
                <h3>IYER'S KITCHEN</h3>
                <p>Fresh South Indian vegetarian food prepared daily.</p>
            </div>
            <div class="footer-column">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#" id="footer-home">Home</a></li>
                    <li><a href="#" id="footer-menu">Menu</a></li>
                    <li><a href="#" id="footer-about">About</a></li>
                    <li><a href="#" id="footer-contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Contact</h3>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> Ghatkopar, Mumbai</li>
                    <li><i class="fas fa-phone"></i> +91 98765 43210</li>
                    <li><i class="fas fa-envelope"></i> info@iyerskitchen.com</li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2023 IYER'S KITCHEN. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        let cart = [];

        // Add to wishlist function
        function addToWishlist(productId) {
            <?php if(isset($_SESSION['customer'])): ?>
                fetch('wishlist.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=add&product_id=${productId}`
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        toastr.success('Added to wishlist! ❤️');
                        // Change button color to indicate added
                        const btn = event.target.closest('.wishlist-btn');
                        if(btn) {
                            btn.classList.add('active');
                            btn.innerHTML = '<i class="fas fa-heart"></i>';
                        }
                    }
                });
            <?php else: ?>
                toastr.info('Please login to add to wishlist');
                window.location.href = 'login.php';
            <?php endif; ?>
        }

        // Load menu from database
        function loadTodaysSpecial() {
            fetch('ajax_menu.php?action=get_special')
                .then(response => response.json())
                .then(items => {
                    const container = document.getElementById('special-container');
                    if (!container) return;
                    container.innerHTML = '';
                    
                    items.slice(0, 3).forEach(item => {
                        container.innerHTML += `
                            <div class="special-item">
                                <div class="special-img" style="background-image: url('${item.image_url || 'https://via.placeholder.com/300x200?text=' + item.name}')">
                                    <div class="special-badge">🔥 Today's Special</div>
                                    <button class="wishlist-btn" onclick="addToWishlist(${item.id})">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                                <div class="special-content">
                                    <h3>${item.name}</h3>
                                    <p>${item.description}</p>
                                    <div class="special-price">
                                        <span class="price">₹${item.price}</span>
                                        <button class="add-to-cart" data-id="${item.id}" data-name="${item.name}" data-price="${item.price}">
                                            <i class="fas fa-cart-plus"></i> Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    attachCartEvents();
                });
        }

        function loadMenuItems() {
            fetch('ajax_menu.php?action=get_all')
                .then(response => response.json())
                .then(items => {
                    const container = document.getElementById('menu-container');
                    if (!container) return;
                    container.innerHTML = '';
                    
                    items.forEach(item => {
                        if (item.is_available) {
                            container.innerHTML += `
                                <div class="menu-item">
                                    <div class="menu-img" style="background-image: url('${item.image_url || 'https://via.placeholder.com/300x200?text=' + item.name}')">
                                        <button class="wishlist-btn" onclick="addToWishlist(${item.id})">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </div>
                                    <div class="menu-content">
                                        <h3>${item.name}</h3>
                                        <p>${item.description}</p>
                                        <div class="menu-price">
                                            <span class="price">₹${item.price}</span>
                                            <button class="add-to-cart" data-id="${item.id}" data-name="${item.name}" data-price="${item.price}">
                                                <i class="fas fa-cart-plus"></i> Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }
                    });
                    
                    attachCartEvents();
                });
        }

        function attachCartEvents() {
            document.querySelectorAll('.add-to-cart').forEach(btn => {
                btn.removeEventListener('click', btn._listener);
                const listener = function() {
                    addToCart(
                        parseInt(this.dataset.id),
                        this.dataset.name,
                        parseInt(this.dataset.price)
                    );
                };
                btn._listener = listener;
                btn.addEventListener('click', listener);
            });
        }

        // Prevent double-click issue
        let lastClickTime = 0;
        let lastClickId = null;

        function addToCart(itemId, itemName, itemPrice) {
            const now = Date.now();
            if (lastClickId === itemId && (now - lastClickTime) < 500) {
                return;
            }
            lastClickTime = now;
            lastClickId = itemId;
            
            const existing = cart.find(i => i.id === itemId);
            if (existing) {
                existing.quantity++;
                toastr.success(`${itemName} quantity increased to ${existing.quantity}!`);
            } else {
                cart.push({id: itemId, name: itemName, price: itemPrice, quantity: 1});
                toastr.success(`${itemName} added to cart!`);
            }
            updateCartCount();
        }
        
        function updateCartCount() {
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cart-count').textContent = count;
            sessionStorage.setItem('cart', JSON.stringify(cart));
        }

        function updateQuantity(productId, action) {
            const itemIndex = cart.findIndex(item => item.id === productId);
            
            if (itemIndex !== -1) {
                if (action === 'increase') {
                    cart[itemIndex].quantity++;
                    toastr.success(`${cart[itemIndex].name} quantity: ${cart[itemIndex].quantity}`);
                } else if (action === 'decrease') {
                    cart[itemIndex].quantity--;
                    if (cart[itemIndex].quantity <= 0) {
                        toastr.success(`${cart[itemIndex].name} removed from cart`);
                        cart.splice(itemIndex, 1);
                    } else {
                        toastr.success(`${cart[itemIndex].name} quantity: ${cart[itemIndex].quantity}`);
                    }
                }
                updateCartCount();
                openCart();
            }
        }

        function removeCartItem(productId) {
            const item = cart.find(item => item.id === productId);
            if (item) {
                toastr.success(`${item.name} removed from cart`);
            }
            cart = cart.filter(item => item.id !== productId);
            updateCartCount();
            openCart();
        }

        function setupEventListeners() {
            document.getElementById('cart-icon').addEventListener('click', openCart);
            document.getElementById('close-cart').addEventListener('click', closeCart);
            document.getElementById('continue-shopping').addEventListener('click', closeCart);
            document.getElementById('checkout-btn').addEventListener('click', () => {
                if(cart.length > 0) {
                    sessionStorage.setItem('cart', JSON.stringify(cart));
                    window.location.href = 'checkout.php';
                } else {
                    toastr.error('Cart is empty!');
                }
            });
            document.getElementById('order-now-btn').addEventListener('click', () => {
                document.getElementById('menu-link').click();
            });

            document.getElementById('home-link').addEventListener('click', e => {
                e.preventDefault();
                showPage('home');
            });

            document.getElementById('menu-link').addEventListener('click', e => {
                e.preventDefault();
                showPage('home');
                setTimeout(() => document.querySelector('.menu-section').scrollIntoView({ behavior: 'smooth' }), 100);
            });

            document.getElementById('about-link').addEventListener('click', e => {
                e.preventDefault();
                showPage('about');
            });

            document.getElementById('contact-link').addEventListener('click', e => {
                e.preventDefault();
                showPage('contact');
            });

            document.getElementById('privacy-link').addEventListener('click', e => {
                e.preventDefault();
                showPage('privacy');
            });

            document.getElementById('footer-home').addEventListener('click', e => {
                e.preventDefault();
                showPage('home');
            });

            document.getElementById('footer-menu').addEventListener('click', e => {
                e.preventDefault();
                showPage('home');
                setTimeout(() => document.querySelector('.menu-section').scrollIntoView({ behavior: 'smooth' }), 100);
            });

            document.getElementById('footer-about').addEventListener('click', e => {
                e.preventDefault();
                showPage('about');
            });

            document.getElementById('footer-contact').addEventListener('click', e => {
                e.preventDefault();
                showPage('contact');
            });
        }

        function openCart() {
            const modal = document.getElementById('cart-modal');
            const cartItems = document.getElementById('cart-items');
            const totalPrice = document.getElementById('cart-total-price');
            
            cartItems.innerHTML = '';
            let total = 0;
            
            if (cart.length === 0) {
                cartItems.innerHTML = '<div style="text-align:center; padding:2rem;"><i class="fas fa-shopping-cart" style="font-size:3rem; color:#ccc;"></i><p>Your cart is empty</p></div>';
            } else {
                cart.forEach((item, index) => {
                    const itemTotal = item.price * item.quantity;
                    total += itemTotal;
                    
                    cartItems.innerHTML += `
                        <div class="cart-item" data-index="${index}">
                            <div class="cart-item-info">
                                <h4>${item.name}</h4>
                                <p>₹${item.price} each</p>
                            </div>
                            <div class="cart-item-actions">
                                <div class="quantity-control">
                                    <button class="quantity-btn minus" data-id="${item.id}">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span class="quantity">${item.quantity}</span>
                                    <button class="quantity-btn plus" data-id="${item.id}">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <button class="remove-item" data-id="${item.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <div class="item-total">₹${itemTotal}</div>
                            </div>
                        </div>
                    `;
                });
            }
            
            totalPrice.textContent = `₹${total}`;
            modal.style.display = 'flex';
            
            document.querySelectorAll('.quantity-btn.minus').forEach(btn => {
                btn.addEventListener('click', function() {
                    updateQuantity(parseInt(this.dataset.id), 'decrease');
                });
            });
            
            document.querySelectorAll('.quantity-btn.plus').forEach(btn => {
                btn.addEventListener('click', function() {
                    updateQuantity(parseInt(this.dataset.id), 'increase');
                });
            });
            
            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', function() {
                    removeCartItem(parseInt(this.dataset.id));
                });
            });
        }

        function closeCart() {
            document.getElementById('cart-modal').style.display = 'none';
        }

        function showPage(page) {
            document.getElementById('customer-home').style.display = 'none';
            document.getElementById('about-page').style.display = 'none';
            document.getElementById('contact-page').style.display = 'none';
            document.getElementById('privacy-page').style.display = 'none';
            
            if (page === 'home') document.getElementById('customer-home').style.display = 'block';
            else if (page === 'about') document.getElementById('about-page').style.display = 'block';
            else if (page === 'contact') document.getElementById('contact-page').style.display = 'block';
            else if (page === 'privacy') document.getElementById('privacy-page').style.display = 'block';
        }

        // Load cart from session storage
        function loadCartFromStorage() {
            const savedCart = sessionStorage.getItem('cart');
            if (savedCart) {
                cart = JSON.parse(savedCart);
                updateCartCount();
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadCartFromStorage();
            loadTodaysSpecial();
            loadMenuItems();
            updateCartCount();
            setupEventListeners();
        });

        toastr.options = { "closeButton": true, "progressBar": true, "timeOut": "3000" };
    </script>
</body>
</html>
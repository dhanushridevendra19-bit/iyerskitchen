<?php
session_start();
include("db.php");

if (!isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['customer'];

// Handle AJAX actions
if(isset($_POST['action'])) {
    $product_id = intval($_POST['product_id']);
    
    if($_POST['action'] == 'add') {
        // Check if already exists
        $check = mysqli_query($conn, "SELECT * FROM wishlist WHERE user_id='$user_id' AND product_id='$product_id'");
        if(mysqli_num_rows($check) == 0) {
            mysqli_query($conn, "INSERT INTO wishlist (user_id, product_id) VALUES ('$user_id', '$product_id')");
        }
        echo json_encode(['success' => true]);
        exit();
    } 
    elseif($_POST['action'] == 'remove') {
        mysqli_query($conn, "DELETE FROM wishlist WHERE user_id='$user_id' AND product_id='$product_id'");
        echo json_encode(['success' => true]);
        exit();
    }
}

// Get wishlist items with menu details
$sql = "SELECT m.* FROM wishlist w 
        JOIN menu_items m ON w.product_id = m.id 
        WHERE w.user_id='$user_id' 
        ORDER BY w.added_at DESC";
$wishlist = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Wishlist - Iyer's Kitchen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: #d35400; color: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .header a { color: white; text-decoration: none; background: rgba(255,255,255,0.2); padding: 10px 15px; border-radius: 5px; }
        .wishlist-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .wishlist-card { background: white; border-radius: 10px; overflow: hidden; position: relative; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .wishlist-card img { width: 100%; height: 180px; object-fit: cover; }
        .wishlist-card-content { padding: 15px; }
        .remove-wishlist { position: absolute; top: 10px; right: 10px; background: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #e74c3c; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        .price { font-size: 1.3rem; font-weight: bold; color: #d35400; margin: 10px 0; }
        .btn { background: #d35400; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; width: 100%; margin-top: 10px; }
        .empty-wishlist { text-align: center; padding: 50px; background: white; border-radius: 10px; }
        .empty-wishlist i { font-size: 4rem; color: #ccc; }
        .empty-wishlist p { margin: 20px 0; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-heart"></i> My Wishlist</h1>
            <a href="index.php"><i class="fas fa-arrow-left"></i> Back to Menu</a>
        </div>
        
        <div class="wishlist-grid">
            <?php if(mysqli_num_rows($wishlist) > 0): ?>
                <?php while($item = mysqli_fetch_assoc($wishlist)): ?>
                <div class="wishlist-card" data-id="<?php echo $item['id']; ?>">
                    <div class="remove-wishlist" onclick="removeFromWishlist(<?php echo $item['id']; ?>, this)">
                        <i class="fas fa-times"></i>
                    </div>
                    <img src="<?php echo $item['image_url'] ?? 'https://via.placeholder.com/280x180?text=' . urlencode($item['name']); ?>" alt="<?php echo $item['name']; ?>">
                    <div class="wishlist-card-content">
                        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p style="color:#666; margin: 10px 0;"><?php echo substr(htmlspecialchars($item['description']), 0, 60); ?>...</p>
                        <div class="price">₹<?php echo $item['price']; ?></div>
                        <button class="btn" onclick="addToCart(<?php echo $item['id']; ?>, '<?php echo addslashes($item['name']); ?>', <?php echo $item['price']; ?>)">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-wishlist" style="grid-column: 1/-1;">
                    <i class="far fa-heart"></i>
                    <p>Your wishlist is empty</p>
                    <a href="index.php" class="btn" style="display: inline-block; width: auto; padding: 10px 30px;">Browse Menu</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function removeFromWishlist(productId, element) {
            fetch('wishlist.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=remove&product_id=${productId}`
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    element.closest('.wishlist-card').remove();
                    alert('Removed from wishlist');
                }
            });
        }

        function addToCart(id, name, price) {
            let cart = JSON.parse(sessionStorage.getItem('cart') || '[]');
            let existing = cart.find(i => i.id == id);
            if(existing) {
                existing.quantity++;
            } else {
                cart.push({id: id, name: name, price: price, quantity: 1});
            }
            sessionStorage.setItem('cart', JSON.stringify(cart));
            alert(name + ' added to cart!');
        }
    </script>
</body>
</html>
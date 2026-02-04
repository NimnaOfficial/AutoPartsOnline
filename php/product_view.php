<?php 
session_start();
include 'db_conn.php'; 

// 1. Check if ID is set in URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    // 2. Fetch Product Data
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        // Product not found, redirect to shop
        header("Location: shop.php");
        exit();
    }
} else {
    // No ID provided, redirect to shop
    header("Location: shop.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?> | AutoHub</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/product.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <nav class="navbar">
        <div class="logo">AutoHub</div>
        <ul class="nav-links">
            <li><a href="/index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="contact.php">Contact</a></li>
            
            
            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="profile.php" style="color: var(--primary);">Hi, <?php echo $_SESSION['username']; ?></a></li>
                <li><a href="logout.php" class="btn-admin">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php" class="btn-admin">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="product-view-container">
        
        <div class="breadcrumb">
            <a href="shop.php">&larr; Back to Catalog</a>
        </div>

        <div class="product-layout">
            
            <div class="product-image-large">
                <?php 
                    // Logic to handle external links vs local images
                    $imgSrc = $product['image_url'];
                    if (!preg_match("/^http/", $imgSrc)) {
                        $imgSrc = "../images/" . $imgSrc;
                    }
                ?>
                <img src="<?php echo $imgSrc; ?>" alt="<?php echo $product['name']; ?>">
            </div>

            <div class="product-info">
                <h1><?php echo $product['name']; ?></h1>
                
                <p class="product-price">LKR <?php echo number_format($product['price'], 2); ?></p>
                
                <div class="stock-badge">
                    In Stock: <span><?php echo $product['stock_quantity']; ?> units</span>
                </div>

                <hr style="border: 0; border-top: 1px solid #333; margin: 20px 0;">

                <h3>Description</h3>
                <p class="product-desc">
                    <?php echo nl2br($product['description']); ?>
                </p>

                <div class="action-buttons" style="display: flex; gap: 10px; margin-top: 20px;">
                    
                    <?php if (isset($_SESSION['username'])): ?>
                        
                        <form action="add_to_cart.php" method="POST" style="flex: 1;">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" name="add_to_cart" class="cart-btn">Add to Cart</button>
                        </form>

                        <a href="checkout.php?id=<?php echo $product['id']; ?>" class="buy-btn" style="flex: 1;">Buy Now</a>
                    
                    <?php else: ?>
                        <a href="login.php" class="login-alert-btn">Login to Order</a>
                    <?php endif; ?>
                    
                </div>

            </div>
        </div>
    </div>

</body>
</html>
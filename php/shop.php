<?php 
session_start();
include 'db_conn.php'; 

// SEARCH LOGIC
$search_query = "";
$sql = "SELECT * FROM products ORDER BY id DESC"; // Default: Show all

if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    // Filter by name (e.g., "Turbo")
    $sql = "SELECT * FROM products WHERE name LIKE '%$search_query%' ORDER BY id DESC";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop | AutoHub</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/shop.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <nav class="navbar">
        <div class="logo">AutoHub</div>
        <ul class="nav-links">
            <li><a href="/index.php">Home</a></li>
            <li><a href="shop.php" class="active">Shop</a></li>
    
            <li><a href="contact.php">Contact</a></li>
            
            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="profile.php" style="color: var(--primary);">Hi, <?php echo $_SESSION['username']; ?></a></li>
                <?php if($_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin.php" class="btn-admin">Admin Panel</a></li>
                <?php endif; ?>
                <li><a href="logout.php" class="btn-admin">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php" class="btn-admin">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <header class="page-header" style="text-align: center; padding: 40px 20px;">
        <h1>Performance Parts Catalog</h1>
        <p style="color: #ccc;">Browse our exclusive collection.</p>

        <div class="search-container">
            <form action="shop.php" method="GET">
                <input type="text" name="search" placeholder="Search for parts..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search</button>
                <?php if($search_query): ?>
                    <a href="shop.php" class="clear-btn">Clear</a>
                <?php endif; ?>
            </form>
        </div>
    </header>

    <section class="shop-container" style="padding: 20px 50px;">
        
        <?php if($search_query): ?>
            <p style="color: #aaa; margin-bottom: 20px;">Showing results for: "<strong><?php echo htmlspecialchars($search_query); ?></strong>"</p>
        <?php endif; ?>

        <div class="product-grid">
            
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // IMAGE PATH LOGIC
                    $imgSrc = $row['image_url'];
                    if (!preg_match("/^http/", $imgSrc)) {
                        $imgSrc = "../images/" . $imgSrc;
                    }
            ?>
            
            <div class="product-card">
                <a href="product_view.php?id=<?php echo $row['id']; ?>" style="text-decoration: none; color: inherit;">
                    <div class="image-box" style="height: 200px; overflow: hidden; border-radius: 5px; margin-bottom: 15px;">
                        <img src="<?php echo $imgSrc; ?>" alt="<?php echo $row['name']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h3><?php echo $row['name']; ?></h3>
                </a>
                
                <p style="font-size: 0.9rem; color: #aaa; min-height: 40px;">
                    <?php echo substr($row['description'], 0, 60); ?>...
                </p>
                
                <div class="card-footer">
                    <span class="price" style="color: var(--primary); font-size: 1.4rem; font-weight: bold; display:block; margin-top: 15px;">
                        LKR <?php echo number_format($row['price'], 2); ?>
                    </span>
                    
                    <div style="display:flex; justify-content: space-between; align-items:center; margin-top:10px;">
                        <span style="color: #4CAF50; font-size: 0.8rem;">
                            In Stock: <?php echo $row['stock_quantity']; ?>
                        </span>
                        
                        <a href="product_view.php?id=<?php echo $row['id']; ?>" style="background-color: #333; color: white; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; border: 1px solid #555;">
                            View
                        </a>
                    </div>
                </div>
            </div>

            <?php
                }
            } else {
                echo "<p style='text-align:center; width:100%; grid-column: 1 / -1; color: #ccc;'>No products found matching your search.</p>";
            }
            ?>

        </div>
    </section>

</body>
</html>
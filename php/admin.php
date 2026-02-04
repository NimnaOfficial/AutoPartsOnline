<?php 
session_start();
include 'db_conn.php'; 

// 1. Handle Login Submission
$error = "";
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SIMPLE ADMIN CHECK (You can change these!)
    if ($username === 'admin' && $password === '123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['username'] = 'Admin';
        $_SESSION['role'] = 'admin';
    } else {
        $error = "Invalid Username or Password!";
    }
}

// 2. Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}

// 3. HANDLE DELETE PRODUCT
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM products WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Product Deleted Successfully'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
    }
}

// 4. Check if User is Logged In
$isLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | AutoHub</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/admin.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <?php if (!$isLoggedIn): ?>
        
        <div class="login-overlay">
            <div class="login-card">
                <h2>Admin Access</h2>
                <p>Please log in to manage products.</p>
                <?php if($error): ?>
                    <p style="color: red; margin-bottom: 10px;"><?php echo $error; ?></p>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" required placeholder="Enter ID">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required placeholder="Enter Pass">
                    </div>
                    <button type="submit" name="login">Login to Dashboard</button>
                </form>
                <br>
                <a href="/index.php" style="color: #aaa; font-size: 0.9rem;">&larr; Back to Website</a>
            </div>
        </div>

    <?php else: ?>

    <nav class="navbar">
        <div class="logo">AutoHub Admin</div>
        <ul class="nav-links">
            <li><a href="admin_orders.php" style="color: #4CAF50;">Orders</a></li>
            <li><a href="admin_messages.php" style="color: #FF9800;">Messages</a></li>
            
            <li><a href="/index.php">View Website</a></li>
            <li><a href="admin.php?logout=true" style="color: var(--primary);">Logout</a></li>
        </ul>
    </nav>

    <div class="admin-wrapper">
        
        <div class="admin-container">
            <h2>Add New Product</h2>
            <form action="save_product.php" method="POST">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" required placeholder="e.g. Brembo Brakes">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" required placeholder="Product details..."></textarea>
                </div>
                <div class="form-group">
                    <label>Price (LKR)</label>
                    <input type="number" step="0.01" name="price" required placeholder="0.00">
                </div>
                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" name="stock" required placeholder="10">
                </div>
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="image_url" required placeholder="https://...">
                </div>
                <button type="submit" name="submit">Add Product</button>
            </form>
        </div>

        <div class="inventory-container">
            <h2>Current Inventory</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM products ORDER BY id DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                // Image Logic
                                $imgSrc = $row['image_url'];
                                if (!preg_match("/^http/", $imgSrc)) {
                                    $imgSrc = "../images/" . $imgSrc;
                                }
                        ?>
                        <tr>
                            <td><img src="<?php echo $imgSrc; ?>" alt="img" class="table-img"></td>
                            <td><?php echo $row['name']; ?></td>
                            <td style="color: var(--primary); font-weight:bold;"><?php echo number_format($row['price']); ?></td>
                            <td><?php echo $row['stock_quantity']; ?></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="action-btn edit-btn">Edit</a>
                                
                                <a href="admin.php?delete_id=<?php echo $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center;'>No products found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <?php endif; ?>

</body>
</html>
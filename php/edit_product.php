<?php 
session_start();
include 'db_conn.php'; 

// 1. SECURITY: Check if Admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php");
    exit();
}

// 2. FETCH DATA: Get the product details based on ID from URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        // If ID doesn't exist, go back to admin
        header("Location: admin.php");
        exit();
    }
}

// 3. UPDATE DATA: Handle the form submission
if (isset($_POST['update_product'])) {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image_url = $_POST['image_url'];

    $stmt = $conn->prepare(
        "UPDATE products 
         SET name = ?, 
             description = ?, 
             price = ?, 
             stock_quantity = ?, 
             image_url = ?
         WHERE id = ?"
    );

    $stmt->bind_param(
        "ssdiss",
        $name,
        $desc,
        $price,
        $stock,
        $image_url,
        $id
    );

    if ($stmt->execute()) {
        echo "<script>
                alert('Product Updated Successfully!');
                window.location.href='admin.php';
              </script>";
    } else {
        echo "<script>
                alert('Error updating product');
              </script>";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | AutoHub</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/admin.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <nav class="navbar">
        <div class="logo">AutoHub Admin</div>
        <ul class="nav-links">
            <li><a href="admin.php">Back to Dashboard</a></li>
        </ul>
    </nav>

    <div class="admin-wrapper" style="justify-content: center;">
        
        <div class="admin-container" style="max-width: 600px; flex: none;">
            <h2>Edit Product: <span style="color: white;"><?php echo $product['name']; ?></span></h2>
            
            <form action="edit_product.php?id=<?php echo $id; ?>" method="POST">
                
                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4" required><?php echo $product['description']; ?></textarea>
                </div>

                <div class="form-group">
                    <label>Price (LKR)</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" name="stock" value="<?php echo $product['stock_quantity']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="image_url" value="<?php echo $product['image_url']; ?>" required>
                </div>
                
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" name="update_product" style="background-color: #2196F3;">Save Changes</button>
                    <a href="admin.php" style="width: 100%; text-align: center; padding: 12px; border-radius: 5px; background-color: #555; text-decoration: none; font-weight: bold; color: white; display: inline-block;">Cancel</a>
                </div>

            </form>
        </div>

    </div>

</body>
</html>
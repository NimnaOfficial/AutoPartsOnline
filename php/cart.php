<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// DELETE ITEM Logic
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    $conn->query("DELETE FROM cart WHERE id=$id");
    header("Location: cart.php");
}

// Fetch Cart Items
$sql = "SELECT cart.id as cart_id, cart.quantity, products.name, products.price, products.image_url 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = $user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart | AutoHub</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/cart.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <nav class="navbar">
        <div class="logo">AutoHub</div>
        <ul class="nav-links">
            <li><a href="shop.php">Back to Shop</a></li>
            <li><a href="profile.php">My Profile</a></li>
        </ul>
    </nav>

    <div class="cart-container">
        <h1>Your Shopping Cart</h1>

        <div class="cart-table">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grand_total = 0;
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $total = $row['price'] * $row['quantity'];
                            $grand_total += $total;
                            
                            // Image Fix
                            $img = $row['image_url'];
                            if (!preg_match("/^http/", $img)) $img = "../images/" . $img;
                    ?>
                    <tr>
                        <td style="display: flex; align-items: center; gap: 15px;">
                            <img src="<?php echo $img; ?>" width="50" height="50" style="object-fit:cover; border-radius:5px;">
                            <?php echo $row['name']; ?>
                        </td>
                        <td>LKR <?php echo number_format($row['price'], 2); ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td style="color: var(--primary);">LKR <?php echo number_format($total, 2); ?></td>
                        <td><a href="cart.php?remove=<?php echo $row['cart_id']; ?>" class="remove-btn">X</a></td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center; padding: 20px;'>Your cart is empty.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="cart-summary">
            <h3>Total: LKR <?php echo number_format($grand_total, 2); ?></h3>
            <?php if ($grand_total > 0): ?>
                <a href="checkout.php?from_cart=true" class="checkout-btn">Proceed to Checkout</a>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>
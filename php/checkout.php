<?php 
session_start();
include 'db_conn.php'; 

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$order_items = [];
$grand_total = 0;

// SCENARIO 1: BUY NOW (Single Item)
if (isset($_GET['id'])) {
    $prod_id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id = $prod_id";
    $res = $conn->query($sql);
    $item = $res->fetch_assoc();
    
    $order_items[] = [
        'product_id' => $item['id'],
        'name' => $item['name'],
        'price' => $item['price'],
        'quantity' => 1,
        'total' => $item['price']
    ];
    $grand_total = $item['price'];
    
    // Flag to know this is NOT from cart
    $_SESSION['is_cart_order'] = false;
} 
// SCENARIO 2: FROM CART (Multiple Items)
elseif (isset($_GET['from_cart'])) {
    $sql = "SELECT c.product_id, c.quantity, p.name, p.price 
            FROM cart c JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = $user_id";
    $res = $conn->query($sql);
    
    while($row = $res->fetch_assoc()) {
        $row['total'] = $row['price'] * $row['quantity'];
        $order_items[] = $row;
        $grand_total += $row['total'];
    }
    
    // Flag to know we need to clear cart later
    $_SESSION['is_cart_order'] = true;
} else {
    header("Location: shop.php"); exit();
}

// HANDLE "PROCEED TO PAYMENT"
if (isset($_POST['go_to_payment'])) {
    // Save these details in SESSION to use on the next page
    $_SESSION['pending_order_items'] = $order_items;
    $_SESSION['pending_grand_total'] = $grand_total;
    
    // Redirect to the Card Page
    header("Location: payment_gateway.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | AutoHub</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/checkout.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <nav class="navbar">
        <div class="logo">AutoHub</div>
        <ul class="nav-links"><li><a href="shop.php">Cancel</a></li></ul>
    </nav>

    <div class="checkout-container">
        <div class="checkout-header"><h1>Order Summary</h1></div>
        
        <div class="checkout-layout">
            <div class="checkout-card summary-card">
                <h3>Items to Purchase</h3>
                <?php foreach ($order_items as $item): ?>
                    <div class="product-preview">
                        <span><?php echo $item['name']; ?> (x<?php echo $item['quantity']; ?>)</span>
                        <span>LKR <?php echo number_format($item['total'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
                
                <hr style="border:0; border-top:1px solid #333; margin:15px 0;">
                
                <div class="total-row">
                    <span>Total Pay</span>
                    <span style="color: var(--primary);">LKR <?php echo number_format($grand_total, 2); ?></span>
                </div>

                <form method="POST">
                    <button type="submit" name="go_to_payment" class="confirm-btn">Proceed to Card Payment</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
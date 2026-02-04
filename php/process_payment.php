<?php
session_start();
include 'db_conn.php';

// Check if verify button was clicked
if (!isset($_POST['pay_now']) || !isset($_SESSION['pending_order_items'])) {
    header("Location: shop.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_items = $_SESSION['pending_order_items'];

// 1. SIMULATE PROCESSING DELAY (2 Seconds)
sleep(2);

// 2. INSERT ORDERS INTO DATABASE
foreach ($order_items as $item) {
    $pid = $item['product_id'];
    $qty = $item['quantity'];
    $total = $item['total'];

    // Status is now 'Paid' instead of 'Processing'
    $sql = "INSERT INTO orders (user_id, product_id, quantity, total_price, status) 
            VALUES ('$user_id', '$pid', '$qty', '$total', 'Paid')";
    $conn->query($sql);

    // Reduce Stock
    $conn->query("UPDATE products SET stock_quantity = stock_quantity - $qty WHERE id = $pid");
}

// 3. CLEAR CART (If this order came from the cart)
if (isset($_SESSION['is_cart_order']) && $_SESSION['is_cart_order'] === true) {
    $conn->query("DELETE FROM cart WHERE user_id = $user_id");
}

// 4. CLEAN UP SESSION
unset($_SESSION['pending_order_items']);
unset($_SESSION['pending_grand_total']);
unset($_SESSION['is_cart_order']);

// 5. REDIRECT TO SUCCESS PAGE (Profile)
echo "<script>
        alert('Payment Approved! Transaction ID: TXN-" . rand(10000,99999) . "'); 
        window.location.href='profile.php';
      </script>";
?>
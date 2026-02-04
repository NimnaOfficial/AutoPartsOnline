<?php
session_start();
include 'db_conn.php';

// 1. Check Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['add_to_cart'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $quantity = 1; // Default add amount

    // 2. Check if item already exists in user's cart
    $check_sql = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        // Item exists -> Update Quantity
        $row = $result->fetch_assoc();
        $new_qty = $row['quantity'] + 1;
        $update_sql = "UPDATE cart SET quantity = $new_qty WHERE id = " . $row['id'];
        $conn->query($update_sql);
    } else {
        // Item new -> Insert
        $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";
        $conn->query($insert_sql);
    }

    // 3. Redirect to Cart Page
    header("Location: cart.php");
    exit();
} else {
    header("Location: shop.php");
}
?>
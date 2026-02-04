<?php 
session_start();
include 'db_conn.php'; 

// 1. Check if User is Logged In
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

// 2. Handle Profile Update
if (isset($_POST['update_profile'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "UPDATE users SET full_name='$full_name', email='$email', phone='$phone', address='$address' WHERE id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        $msg = "<p class='success-msg'>Profile Updated Successfully!</p>";
    } else {
        $msg = "<p class='error-msg'>Error updating profile.</p>";
    }
}

// 3. Fetch Current User Data
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | AutoHub</title>
    <link rel="stylesheet" href="/DMW_CW/css/global.css">
    <link rel="stylesheet" href="/DMW_CW/css/profile.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">AutoHub</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="contact.php">Contact</a></li>
            
            <li><a href="profile.php" class="active" style="color: var(--primary);">Hi, <?php echo $_SESSION['username']; ?></a></li>
            
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li><a href="admin.php" class="btn-admin">Admin Panel</a></li>
            <?php endif; ?>
            
            <li><a href="logout.php" class="btn-admin">Logout</a></li>
        </ul>
    </nav>

    <div class="profile-container">
        <div class="profile-card" style="margin-top: 30px;">
            <div class="profile-header">
                <h2>My Order History</h2>
            </div>
            
            <table style="width: 100%; border-collapse: collapse; color: #ccc;">
                <thead>
                    <tr style="border-bottom: 1px solid #444; text-align: left;">
                        <th style="padding: 10px;">Date</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $oid = $_SESSION['user_id'];
                    $sql_orders = "SELECT o.order_date, o.total_price, o.status, p.name 
                                   FROM orders o 
                                   JOIN products p ON o.product_id = p.id 
                                   WHERE o.user_id = $oid ORDER BY o.order_date DESC";
                    $res_orders = $conn->query($sql_orders);

                    if ($res_orders->num_rows > 0) {
                        while ($order = $res_orders->fetch_assoc()) {
                            $statusColor = ($order['status'] == 'Pending') ? '#FFA500' : '#4CAF50';
                    ?>
                        <tr style="border-bottom: 1px solid #333;">
                            <td style="padding: 12px;"><?php echo date("M d", strtotime($order['order_date'])); ?></td>
                            <td><?php echo $order['name']; ?></td>
                            <td>LKR <?php echo number_format($order['total_price']); ?></td>
                            <td style="color: <?php echo $statusColor; ?>; font-weight: bold;">
                                <?php echo $order['status']; ?>
                            </td>
                        </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='4' style='padding:15px; text-align:center;'>No orders yet.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
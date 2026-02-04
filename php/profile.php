<?php 
session_start();
include 'db_conn.php'; 

// 1. Check if User is Logged In
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

// 2. Handle Profile Update with Prepared Statements
if (isset($_POST['update_profile'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Using Prepared Statements to prevent SQL injection and handle quotes safely
    $stmt = $conn->prepare("UPDATE users SET full_name=?, email=?, phone=?, address=? WHERE id=?");
    $stmt->bind_param("ssssi", $full_name, $email, $phone, $address, $user_id);

    if ($stmt->execute()) {
        $msg = "<p class='success-msg' style='color: #4CAF50; background: rgba(76, 175, 80, 0.1); padding: 10px; border-radius: 5px;'>Profile Updated Successfully!</p>";
    } else {
        $msg = "<p class='error-msg' style='color: #f44336;'>Error updating profile: " . $conn->error . "</p>";
    }
    $stmt->close();
}

// 3. Fetch Current User Data
$stmt_user = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result = $stmt_user->get_result();
$user = $result->fetch_assoc();
$stmt_user->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | AutoHub</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/profile.css">
    <style>
        .profile-container { max-width: 900px; margin: 40px auto; padding: 20px; font-family: 'Poppins', sans-serif; }
        .profile-card { background: #222; padding: 30px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; color: #aaa; margin-bottom: 5px; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; background: #333; border: 1px solid #444; color: white; border-radius: 5px; }
        .update-btn { background: #ff9800; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .update-btn:hover { background: #e68a00; }
    </style>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <nav class="navbar">
        <div class="logo">AutoHub</div>
        <ul class="nav-links">
            <li><a href="/index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="profile.php" class="active" style="color: #ff9800;">Hi, <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></a></li>
            <li><a href="logout.php" class="btn-admin">Logout</a></li>
        </ul>
    </nav>

    <div class="profile-container">
        <div class="profile-card">
            <h2>Edit Profile</h2>
            <?php echo $msg; ?>
            <form method="POST" action="profile.php">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                </div>
                <button type="submit" name="update_profile" class="update-btn">Update Profile</button>
            </form>
        </div>

        <div class="profile-card">
            <h2>My Order History</h2>
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
                    $sql_orders = "SELECT o.order_date, o.total_price, o.status, p.name 
                                   FROM orders o 
                                   JOIN products p ON o.product_id = p.id 
                                   WHERE o.user_id = ? ORDER BY o.order_date DESC";
                    $stmt_orders = $conn->prepare($sql_orders);
                    $stmt_orders->bind_param("i", $user_id);
                    $stmt_orders->execute();
                    $res_orders = $stmt_orders->get_result();

                    if ($res_orders->num_rows > 0) {
                        while ($order = $res_orders->fetch_assoc()) {
                            $statusColor = ($order['status'] == 'Pending') ? '#FFA500' : '#4CAF50';
                    ?>
                        <tr style="border-bottom: 1px solid #333;">
                            <td style="padding: 12px;"><?php echo date("M d", strtotime($order['order_date'])); ?></td>
                            <td><?php echo htmlspecialchars($order['name']); ?></td>
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
<?php 
session_start();
include 'db_conn.php'; 

// 1. Check Admin Login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php");
    exit();
}

// 2. Handle Status Update (e.g., Mark as Shipped)
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $conn->query("UPDATE orders SET status = '$new_status' WHERE id = $order_id");
    echo "<script>alert('Order Status Updated!'); window.location.href='admin_orders.php';</script>";
}

// 3. Fetch All Orders (Newest First)
$sql = "SELECT o.id as order_id, o.order_date, o.status, o.total_price, o.quantity, 
               u.full_name, u.address, 
               p.name as product_name, p.image_url
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN products p ON o.product_id = p.id
        ORDER BY o.order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders | AutoHub</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/admin.css">
    <style>
        /* Extra styles specific to this table */
        .status-select { padding: 5px; border-radius: 4px; background: #333; color: white; border: 1px solid #555; }
        .update-btn { background: var(--primary); color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
        .update-btn:hover { background: var(--primary-hover); }
    </style>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <nav class="navbar">
        <div class="logo">AutoHub Admin</div>
        <ul class="nav-links">
            <li><a href="admin.php">Back to Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="admin-wrapper" style="display: block; max-width: 1200px;">
        
        <div class="inventory-container" style="width: 100%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Customer Orders</h2>
                <span style="color: #aaa;">Total Orders: <?php echo $result->num_rows; ?></span>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Total (LKR)</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $statusColor = ($row['status'] == 'Pending') ? '#FFA500' : '#4CAF50';
                        ?>
                        <tr>
                            <td style="color: #888;">#<?php echo $row['order_id']; ?></td>
                            <td><?php echo date("M d, Y", strtotime($row['order_date'])); ?></td>
                            <td>
                                <strong><?php echo $row['full_name']; ?></strong><br>
                                <span style="font-size: 0.8rem; color: #aaa;"><?php echo substr($row['address'], 0, 20); ?>...</span>
                            </td>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td style="font-weight: bold; color: var(--primary);"><?php echo number_format($row['total_price']); ?></td>
                            
                            <td colspan="2">
                                <form method="POST" style="display: flex; gap: 5px;">
                                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                    
                                    <select name="status" class="status-select">
                                        <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="Shipped" <?php if($row['status']=='Shipped') echo 'selected'; ?>>Shipped</option>
                                        <option value="Completed" <?php if($row['status']=='Completed') echo 'selected'; ?>>Completed</option>
                                        <option value="Cancelled" <?php if($row['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
                                    </select>

                                    <button type="submit" name="update_status" class="update-btn">Update</button>
                                </form>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='8' style='text-align:center;'>No orders found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>
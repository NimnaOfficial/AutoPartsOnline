<?php 
session_start();
include 'db_conn.php'; 

// 1. Check Admin Login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php");
    exit();
}

// 2. Handle Delete Message
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM messages WHERE id = $id");
    echo "<script>alert('Message Deleted'); window.location.href='admin_messages.php';</script>";
}

// 3. Fetch Messages
$sql = "SELECT * FROM messages ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inbox | AutoHub</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">AutoHub Admin</div>
        <ul class="nav-links">
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="admin_orders.php">Orders</a></li>
            <li><a href="/index.php">Website</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="admin-wrapper" style="display: block; max-width: 1000px;">
        
        <div class="inventory-container" style="width: 100%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Customer Messages</h2>
                <span style="color: #aaa;">Total: <?php echo $result->num_rows; ?></span>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td style="color: #888; font-size: 0.85rem; width: 100px;">
                                <?php echo date("M d, Y", strtotime($row['created_at'])); ?>
                            </td>
                            <td style="width: 180px;">
                                <strong><?php echo $row['name']; ?></strong><br>
                                <a href="mailto:<?php echo $row['email']; ?>" style="color: var(--primary); font-size: 0.85rem;">
                                    <?php echo $row['email']; ?>
                                </a>
                            </td>
                            <td style="font-weight: bold; width: 150px;"><?php echo $row['subject']; ?></td>
                            <td style="color: #ccc; line-height: 1.5;"><?php echo $row['message']; ?></td>
                            <td style="text-align: center;">
                                <a href="admin_messages.php?delete_id=<?php echo $row['id']; ?>" 
                                   class="action-btn delete-btn"
                                   onclick="return confirm('Delete this message?');">
                                   X
                                </a>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center;'>No messages found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>
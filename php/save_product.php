<?php
include 'db_conn.php';

if (isset($_POST['submit'])) {
    // 1. Get the data from POST
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image_url = $_POST['image_url'];

    // 2. Use a Prepared Statement to handle special characters (like quotes) safely
    // "ssdis" means: string, string, double(decimal), integer, string
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock_quantity, image_url) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("ssdis", $name, $desc, $price, $stock, $image_url);

        // 3. Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('Product Added Successfully!'); window.location.href='admin.php';</script>";
        } else {
            // Check for specific database errors (like table name or column name mistakes)
            echo "Execution Error: " . $stmt->error;
        }

        // 4. Close the statement
        $stmt->close();
    } else {
        echo "Preparation Error: " . $conn->error;
    }
}

// Close connection at the very end
$conn->close();
?>
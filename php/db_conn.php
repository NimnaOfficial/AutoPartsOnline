<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auto_parts_db";

// Connection using port 3307 (Change to 3306 if needed)
$conn = new mysqli($servername, $username, $password, $dbname, 3307);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
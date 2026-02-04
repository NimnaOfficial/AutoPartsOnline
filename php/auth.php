<?php
session_start();
include 'db_conn.php';

// ==========================================
// 1. REGISTER USER
// ==========================================
if (isset($_POST['register_user'])) {
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);
    $address   = trim($_POST['address']);
    $username  = trim($_POST['username']);
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];

    // Basic Validation
    if (empty($full_name) || empty($email) || empty($phone) || empty($address) || empty($username) || empty($password)) {
        header("Location: login.php?error=All fields are required");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: login.php?error=Invalid email format");
        exit();
    }

    if ($password !== $confirm) {
        header("Location: login.php?error=Passwords do not match");
        exit();
    }

    if (strlen($password) < 6) {
        header("Location: login.php?error=Password must be at least 6 characters");
        exit();
    }

    // A. Check if user already exists (Securely)
    $stmt = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: login.php?error=Username or Email already registered");
        exit();
    }
    $stmt->close();

    // B. Insert New User (Plain Text Password)
    // "ssssss" = 6 strings
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, address, username, password, role) VALUES (?, ?, ?, ?, ?, ?, 'customer')");
    $stmt->bind_param("ssssss", $full_name, $email, $phone, $address, $username, $password);

    if ($stmt->execute()) {
        header("Location: login.php?success=Account created successfully! Please login.");
    } else {
        header("Location: login.php?error=Database error: " . $stmt->error);
    }
    $stmt->close();
}

// ==========================================
// 2. LOGIN USER
// ==========================================
if (isset($_POST['login_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // This is the input you want to check

    // A. Get User from DB (Securely)
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // B. Check Password (Plain Text Comparison)
        // FIXED: Changed '$password_input' to '$password'
        if ($password === $row['password']) {
            
            // Login Success
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] === 'admin') {
                $_SESSION['admin_logged_in'] = true;
                header("Location: admin.php"); 
            } else {
                // Go UP one folder to find index.php
                header("Location: ../index.php"); 
            }
            exit();

        } else {
            header("Location: login.php?error=Incorrect password");
        }
    } else {
        header("Location: login.php?error=User not found");
    }
    $stmt->close();
}
?>
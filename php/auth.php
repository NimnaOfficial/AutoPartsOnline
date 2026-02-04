<?php
session_start();
include 'db_conn.php';

// REGISTER USER
if (isset($_POST['register_user'])) {
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);
    $address   = trim($_POST['address']);
    $username  = trim($_POST['username']);
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];

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

    $check = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = $conn->query($check);
    if ($result->num_rows > 0) {
        header("Location: login.php?error=Username or Email already registered");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (full_name, email, phone, address, username, password, role) 
            VALUES ('$full_name', '$email', '$phone', '$address', '$username', '$hashed_password', 'customer')";

    if ($conn->query($sql) === TRUE) {
        header("Location: login.php?success=Account created successfully! Please login.");
    } else {
        header("Location: login.php?error=Database error");
    }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] === 'admin') {
                $_SESSION['admin_logged_in'] = true;
                header("Location: admin.php"); // Same folder
            } else {
                // If index.php is in main folder, go up one level
                header("Location: index.php"); 
            }
            exit();
        } else {
            header("Location: login.php?error=Incorrect password");
        }
    } else {
        header("Location: login.php?error=User not found");
    }
}
?>
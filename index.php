<?php 
session_start();
include 'php/db_conn.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoHub | Premium Parts</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/home.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <div class="logo">AutoHub</div>
        <ul class="nav-links">
            <li><a href="/index.php" class="active">Home</a></li>
            <li><a href="/php/shop.php">Shop</a></li>
            <li><a href="/php/contact.php">Contact</a></li>
            
            
            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="/php/profile.php" style="color: var(--primary);">Hi, <?php echo $_SESSION['username']; ?></a></li>
                <?php if($_SESSION['role'] === 'admin'): ?>
                    <li><a href="/php/admin.php" class="btn-admin">Admin Panel</a></li>
                <?php endif; ?>
                <li><a href="/php/logout.php" class="btn-admin">Logout</a></li>
            <?php else: ?>
                <li><a href="/php/login.php" class="btn-admin">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <header class="hero">
        <div class="hero-content">
            <h1>Upgrade Your Ride</h1>
            <p>Top-tier parts for high-performance machines.</p>
            <a href="/php/shop.php" class="cta-button">Shop Now</a>
        </div>
    </header>

    <section class="about-section">
        <div class="about-container">
            <div class="about-image">
                <img src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&w=800&q=80" alt="Auto Workshop">
            </div>
            <div class="about-text">
                <h2>Driven by Performance</h2>
                <p>At AutoHub, we don't just sell parts; we fuel passions. Founded by enthusiasts for enthusiasts, we source only the highest-rated components for your vehicle.</p>
                <p>Whether you are tuning for the track or restoring a classic, our expert team ensures you get exactly what you need.</p>
                <br>
                <a href="/php/contact.php" class="secondary-btn">Contact Our Team &rarr;</a>
            </div>
        </div>
    </section>

    <section class="features-section">
        <div class="features-header">
            <h2>Why Choose AutoHub?</h2>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="icon">🚀</div>
                <h3>Fast Delivery</h3>
                <p>Get your parts delivered within 24 hours anywhere in the country.</p>
            </div>
            <div class="feature-card">
                <div class="icon">🛡️</div>
                <h3>Genuine Parts</h3>
                <p>100% authentic components directly from top global manufacturers.</p>
            </div>
            <div class="feature-card">
                <div class="icon">🔧</div>
                <h3>Expert Support</h3>
                <p>Not sure what fits? Our certified mechanics are here to help 24/7.</p>
            </div>
        </div>
    </section>

    <footer>
        <p>
            <a href="/php/admin.php" style="text-decoration: none; color: inherit; cursor: text;">
                &copy;
            </a> 
            2025 AutoHub. Built for NIBM Coursework.
        </p>
    </footer>

</body>
</html>
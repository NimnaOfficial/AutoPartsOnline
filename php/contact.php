<?php 
session_start(); // <--- Add this line at the top of EVERY page
include 'db_conn.php'; 

$msg_status = "";

// Handle Form Submission
if (isset($_POST['send_message'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Insert into 'messages' table
    $sql = "INSERT INTO messages (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        $msg_status = "<p class='success-msg'>Message sent successfully! We will contact you soon.</p>";
    } else {
        $msg_status = "<p class='error-msg'>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | AutoHub</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/contact.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <nav class="navbar">
        <div class="logo">AutoHub</div>
        <ul class="nav-links">
            <li><a href="/index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="contact.php" class="active">Contact</a></li>
            <li><a href="profile.php" style="color: var(--primary);">Hi, <?php echo $_SESSION['username'] ?? 'Guest'; ?></a></li>
            <li><a href="logout.php" class="btn-admin">Logout</a></li>
            
        </ul>
    </nav>

    <header class="contact-header">
        <h1>Get In Touch</h1>
        <p>Have a question about a part? We are here to help.</p>
    </header>

    <div class="contact-container">
        
        <div class="contact-info">
            <div class="info-card">
                <div class="icon">📍</div>
                <h3>Headquarters</h3>
                <p>123 Racing Road,<br>Colombo 07, Sri Lanka.</p>
            </div>

            <div class="info-card">
                <div class="icon">📞</div>
                <h3>Phone</h3>
                <p>+94 77 123 4567<br>+94 11 222 3333</p>
            </div>

            <div class="info-card">
                <div class="icon">✉️</div>
                <h3>Email</h3>
                <p>support@autohub.lk<br>sales@autohub.lk</p>
            </div>

            <div class="info-card">
                <div class="icon">⏰</div>
                <h3>Working Hours</h3>
                <p>Mon - Fri: 9am - 6pm<br>Weekend: 10am - 4pm</p>
            </div>
        </div>

        <div class="contact-form-section">
            <h2>Send us a Message</h2>
            
            <?php echo $msg_status; ?>

            <form method="POST" action="contact.php">
                <div class="form-row">
                    <div class="form-group half">
                        <label>Your Name</label>
                        <input type="text" name="name" required placeholder="Batman">
                    </div>
                    <div class="form-group half">
                        <label>Email Address</label>
                        <input type="email" name="email" required placeholder="batman@example.com">
                    </div>
                </div>

                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" name="subject" required placeholder="Inquiry about...">
                </div>

                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" rows="5" required placeholder="How can we help you?"></textarea>
                </div>

                <button type="submit" name="send_message" class="submit-btn">Send Message</button>
            </form>
        </div>

    </div>

    <footer>
        <p>&copy; 2025 AutoHub. Built for NIBM Coursework.</p>
    </footer>

</body>
</html>
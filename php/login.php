<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register | AutoHub</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/login.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <nav class="navbar">
        <div class="logo">AutoHub</div>
        <ul class="nav-links">
            <li><a href="/index.php">Back to Home</a></li>
        </ul>
    </nav>

    <div class="auth-container">
        <div class="auth-box">
            
            <?php if (isset($_GET['error'])) { ?>
                <p class="error-msg"><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php } ?>
            <?php if (isset($_GET['success'])) { ?>
                <p class="success-msg"><?php echo htmlspecialchars($_GET['success']); ?></p>
            <?php } ?>

            <div id="login-form">
                <h2>Welcome Back</h2>
                <p class="subtitle">Login to access your garage.</p>
                
                <form action="auth.php" method="POST">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit" name="login_user" class="btn-auth">Log In</button>
                </form>
                
                <p class="switch-text">
                    New to AutoHub? <span onclick="toggleForms()">Create an account</span>
                </p>
            </div>

            <div id="register-form" style="display: none;">
                <h2>Create Account</h2>
                <p class="subtitle">Join the club today.</p>
                
                <form action="auth.php" method="POST">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" required placeholder="Bat Man">
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" required placeholder="bat@example.com">
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" pattern="[0-9]{10}" required placeholder="0771234567">
                    </div>
                    <div class="form-group">
                        <label>Shipping Address</label>
                        <input type="text" name="address" required placeholder="123 Street, City">
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" required placeholder="Unique username">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" minlength="6" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" required>
                    </div>

                    <button type="submit" name="register_user" class="btn-auth">Sign Up</button>
                </form>

                <p class="switch-text">
                    Already have an account? <span onclick="toggleForms()">Login here</span>
                </p>
            </div>

        </div>
    </div>

    <script>
        function toggleForms() {
            var login = document.getElementById("login-form");
            var register = document.getElementById("register-form");
            
            if (login.style.display === "none") {
                login.style.display = "block";
                register.style.display = "none";
            } else {
                login.style.display = "none";
                register.style.display = "block";
            }
        }
    </script>

</body>
</html>
<?php 
session_start();
if (!isset($_SESSION['pending_order_items'])) {
    header("Location: shop.php"); // Block access if no order pending
    exit();
}
$total = $_SESSION['pending_grand_total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Payment | AutoHub</title>
    <link rel="stylesheet" href="/css/global.css">
    <style>
        body { background-color: #0d0d0d; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .payment-card {
            background-color: white; /* White card for contrast like a bank */
            color: #333;
            width: 400px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 50px rgba(0,0,0,0.5);
        }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .card-icons img { height: 30px; margin-left: 5px; }
        input {
            width: 100%; padding: 12px; margin-bottom: 15px;
            border: 1px solid #ccc; border-radius: 5px;
            background: #f9f9f9; color: #333;
        }
        .row { display: flex; gap: 10px; }
        .pay-btn {
            width: 100%; padding: 15px; background: #007bff; color: white;
            border: none; font-size: 1.1rem; font-weight: bold;
            border-radius: 5px; cursor: pointer;
        }
        .pay-btn:hover { background: #0056b3; }
    </style>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <div class="payment-card">
        <div class="card-header">
            <h3>Card Payment</h3>
            <div class="card-icons">
                <span style="font-weight:bold; color: #1a1f71;">VISA</span>
                <span style="font-weight:bold; color: #eb001b;">MasterCard</span>
            </div>
        </div>

        <p style="margin-bottom: 20px; color: #555;">Total Amount: <strong>LKR <?php echo number_format($total, 2); ?></strong></p>

        <form action="process_payment.php" method="POST">
            
            <label style="font-size: 0.8rem; font-weight: bold;">Card Number</label>
            <input type="text" name="card_num" placeholder="0000 0000 0000 0000" maxlength="19" required>

            <label style="font-size: 0.8rem; font-weight: bold;">Cardholder Name</label>
            <input type="text" name="card_name" placeholder="JOHN DOE" required>

            <div class="row">
                <div style="flex: 1;">
                    <label style="font-size: 0.8rem; font-weight: bold;">Expiry Date</label>
                    <input type="text" name="expiry" placeholder="MM/YY" maxlength="5" required>
                </div>
                <div style="flex: 1;">
                    <label style="font-size: 0.8rem; font-weight: bold;">CVV</label>
                    <input type="password" name="cvv" placeholder="123" maxlength="3" required>
                </div>
            </div>

            <button type="submit" name="pay_now" class="pay-btn">Pay Now</button>
            <a href="checkout.php" style="display:block; text-align:center; margin-top:15px; color: #999; text-decoration:none;">Cancel Payment</a>
        </form>
    </div>

</body>
</html>
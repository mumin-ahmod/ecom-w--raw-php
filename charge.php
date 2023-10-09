<?php
require_once 'vendor/stripe/stripe-php/init.php'; // Include the Stripe PHP library

$stripeSecretKey = 'sk_test_51NzCHGSJmr02EggBN6weavUpUYecY3QjuSQ2gM6myCdL6CCA6BXwLq9OeGd88sBXbVWy4FXQ2uRrUpymn9GU2dD900z2TtJef8'; // Replace with your Stripe Secret Key

\Stripe\Stripe::setApiKey($stripeSecretKey);

$token = $_POST['token'];

try {
    $charge = \Stripe\Charge::create([
        'amount' => 1000, // Amount in cents (adjust as needed)
        'currency' => 'usd',
        'description' => 'Payment for goods or services',
        'source' => $token,
    ]);

    // Handle success (e.g., update your database, send email confirmation)
    echo json_encode(['status' => 'success']);
} catch (\Stripe\Error\Base $e) {
    // Handle Stripe errors (e.g., card declined)
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

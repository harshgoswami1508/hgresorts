<?php
// stripe_checkout.php

require_once('vendor/autoload.php'); // Assuming you're using Composer

// ⚠️ REPLACE WITH YOUR ACTUAL KEYS ⚠️
\Stripe\Stripe::setApiKey('sk_live_YOUR_SECRET_KEY'); 

$bookingId = $_GET['booking_id'] ?? null;
$amount = $_GET['amount'] ?? 0;
$email = $_GET['email'] ?? '';

if (!$bookingId || $amount <= 0) {
    die("Invalid payment request.");
}

try {
    // Convert amount to cents/smallest unit (e.g., $10.50 -> 1050)
    $amount_cents = round($amount * 100);

    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'unit_amount' => $amount_cents,
                'product_data' => [
                    'name' => 'HG Resort Booking #' . $bookingId,
                    'description' => 'Payment for room reservation.',
                ],
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'customer_email' => $email, // Pre-fills the email
        // URLs that Stripe redirects to after success/cancellation
        'success_url' => 'https://yourdomain.com/payment_success.php?session_id={CHECKOUT_SESSION_ID}&booking_id=' . $bookingId,
        'cancel_url' => 'https://yourdomain.com/payment_cancel.php?booking_id=' . $bookingId,
    ]);

    // Redirect user to Stripe's hosted payment page
    header("HTTP/1.1 303 See Other");
    header("Location: " . $checkout_session->url);
    exit;

} catch (Exception $e) {
    // Handle error gracefully
    error_log("Stripe Error: " . $e->getMessage());
    die("Payment system is currently unavailable. Please try again later. " . $e->getMessage());
}
?>
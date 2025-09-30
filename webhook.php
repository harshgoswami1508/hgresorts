<?php
include('common/con.php');

// Get POST payload from gateway
$payload = file_get_contents("php://input");
$data = json_decode($payload, true);

// Verify signature from gateway here (depends on provider)

if ($data['status'] == 'SUCCESS') {
    $bookingId = $data['booking_id'];
    $transactionId = $data['transaction_id'];

    // Update booking status
    $stmt = $conn->prepare("UPDATE bookings SET status='Paid', transaction_id=? WHERE id=?");
    $stmt->bind_param("si", $transactionId, $bookingId);
    $stmt->execute();
}

http_response_code(200); // acknowledge webhook
?>

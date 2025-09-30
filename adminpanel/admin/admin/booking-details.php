<?php
session_start();
include_once('../includes/config.php');

if (strlen($_SESSION['adminid']) == 0) {
    header('location:logout.php');
    exit();
}

// Get booking ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<h2>Invalid Booking ID</h2>";
    exit();
}

$bookingID = intval($_GET['id']);

// Fetch booking with room details
$query = "SELECT b.*, r.room_nm AS room_name, r.room_photo
          FROM bookings b
          LEFT JOIN rooms_details r ON b.room_name = r.room_nm
          WHERE b.id = $bookingID
          LIMIT 1";

$result = mysqli_query($con, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    echo "<h2>Booking not found</h2>";
    exit();
}

$row = mysqli_fetch_assoc($result);

// Room image fallback
$roomImage = !empty($row['room_photo']) ? htmlspecialchars($row['room_photo']) : '../../assets/img/default-room.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Details | HG Resort</title>
    <link href="../css/styles.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .detail-label { font-weight: 600; color: #374151; text-transform: uppercase; font-size: 0.875rem; }
        .detail-value { color: #111827; font-size: 1rem; }
        .room-img { width: 100%; max-width: 350px; height: 220px; object-fit: cover; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: transform 0.3s ease; }
        .room-img:hover { transform: scale(1.05); }
        .card { background: #fff; border-radius: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.08); padding: 2rem; }
        .grid-label { display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e5e7eb; }
        .back-link { font-size: 0.95rem; font-weight: 500; }
    </style>
</head>
<body class="sb-nav-fixed bg-gray-50">
    <?php include_once('includes/navbar.php'); ?>
    <div id="layoutSidenav">
        <?php include_once('includes/sidebar.php'); ?>
        <div id="layoutSidenav_content">
            <main class="p-6">
                <div class="container mx-auto">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Booking Details</h1>
                    <a href="booking.php" class="back-link text-blue-600 hover:text-blue-800 mb-6 inline-block">&larr; Back to Bookings</a>

                    <div class="card">
                        <!-- Room Image -->
                        <div class="mb-6 text-center">
                            <img src="<?= $roomImage ?>" alt="<?= htmlspecialchars($row['room_name']) ?>" class="room-img mx-auto">
                            <h2 class="text-2xl font-semibold mt-3"><?= htmlspecialchars($row['room_name']) ?></h2>
                        </div>

                        <!-- Booking Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="grid-label"><span class="detail-label">Booking ID</span><span class="detail-value"><?= $row['id'] ?></span></div>
                                <div class="grid-label"><span class="detail-label">Full Name</span><span class="detail-value"><?= htmlspecialchars($row['full_name']) ?></span></div>
                                <div class="grid-label"><span class="detail-label">Email</span><span class="detail-value"><?= htmlspecialchars($row['email']) ?></span></div>
                                <div class="grid-label"><span class="detail-label">Phone</span><span class="detail-value"><?= htmlspecialchars($row['phone']) ?></span></div>
                                <div class="grid-label"><span class="detail-label">Check-In</span><span class="detail-value"><?= date('M j, Y', strtotime($row['check_in'])) ?></span></div>
                                <div class="grid-label"><span class="detail-label">Check-Out</span><span class="detail-value"><?= date('M j, Y', strtotime($row['check_out'])) ?></span></div>
                                <div class="grid-label"><span class="detail-label">Adults</span><span class="detail-value"><?= $row['adults'] ?></span></div>
                                <div class="grid-label"><span class="detail-label">Children</span><span class="detail-value"><?= $row['children'] ?></span></div>
                            </div>

                            <div>
                                <div class="grid-label"><span class="detail-label">Room Price</span><span class="detail-value">₹ <?= number_format($row['room_price'], 2) ?></span></div>
                                <div class="grid-label"><span class="detail-label">Resort Fee</span><span class="detail-value">₹ <?= number_format($row['resort_fee'], 2) ?></span></div>
                                <div class="grid-label"><span class="detail-label">Final Total</span><span class="detail-value font-bold text-green-700">₹ <?= number_format($row['final_total'], 2) ?></span></div>
                                <div class="grid-label"><span class="detail-label">Payment Method</span><span class="detail-value"><?= htmlspecialchars($row['payment_method']) ?></span></div>
                                <div class="grid-label"><span class="detail-label">Booked At</span><span class="detail-value"><?= date('M j, Y h:i A', strtotime($row['created_at'])) ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include_once('../includes/footer.php'); ?>
        </div>
    </div>
</body>
</html>

<?php
if (isset($con)) {
    mysqli_close($con);
}
?>

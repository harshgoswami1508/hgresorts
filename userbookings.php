<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

include 'common/con.php';

// Static amenities
$staticAmenities = [
    ['icon' => 'fas fa-wifi', 'name' => 'Free Wi-Fi'],
    ['icon' => 'fas fa-snowflake', 'name' => 'Air Conditioning'], 
    ['icon' => 'fas fa-tv', 'name' => 'Smart TV'],
    ['icon' => 'fas fa-mug-hot', 'name' => 'Coffee/Tea Maker'],
    ['icon' => 'fas fa-bath', 'name' => 'Private Bathroom'],
    ['icon' => 'fas fa-glass-martini-alt', 'name' => 'Mini Bar'],
    ['icon' => 'fas fa-concierge-bell', 'name' => '24-Hour Room Service'],
    ['icon' => 'fas fa-broom', 'name' => 'Daily Housekeeping'],
];

// Get user's ID
$fullName = $_SESSION['user_name'];
$userQuery = mysqli_query($con, "SELECT id FROM users WHERE full_name='".mysqli_real_escape_string($con, $fullName)."'");
$userRow = mysqli_fetch_assoc($userQuery);
$userId = $userRow['id'] ?? 0;

if ($userId === 0) {
    header("Location: logout.php");
    exit();
}

// Handle cancel booking request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $bookingId = intval($_POST['booking_id']);
    mysqli_query($con, "DELETE FROM bookings WHERE id='$bookingId' AND user_id='$userId'");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Fetch all bookings for this user and join with rooms_details to get image, price, desc
$allBookingsQuery = mysqli_query($con, "
    SELECT b.*, r.room_photo, r.room_nm, r.room_price, r.descc
    FROM bookings b
    LEFT JOIN rooms_details r ON b.room_name = r.room_nm
    WHERE b.user_id='$userId'
    ORDER BY b.created_at DESC
");

$allBookings = [];
if ($allBookingsQuery && mysqli_num_rows($allBookingsQuery) > 0) {
    while ($b = mysqli_fetch_assoc($allBookingsQuery)) {
        $b['room_name'] = $b['room_name'] ?? $b['room_nm'];
        $b['room_image'] = !empty($b['room_photo']) ? 'adminpanel/admin/admin/'.$b['room_photo'] : 'assets/img/hero/1.jpg';
        $b['room_price'] = $b['room_price'] ?? 0;
        $b['room_desc'] = $b['descc'] ?? '';

        // Calculate nights and total
        $checkIn = new DateTime($b['check_in']);
        $checkOut = new DateTime($b['check_out']);
        $interval = $checkIn->diff($checkOut);
        $nights = max(1, $interval->days);
        $b['calculated_total'] = $b['room_price'] * $nights;

        // Payment status
        $b['payment_status'] = $b['payment_status'] ?? 'Pending';

        $allBookings[] = $b;
    }
}

include('common/userheader.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Bookings - HG Resort</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?php include('common/style.php'); ?>

<script>
tailwind.config = {
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                primary: {50:'#f0f9ff',100:'#e0f2fe',200:'#bae6fd',300:'#7dd3fc',400:'#38bdf8',500:'#0ea5e9',600:'#0284c7',700:'#0369a1',800:'#075985',900:'#0c4a6e'}
            },
            keyframes: { fadeInUp: {'0%': {opacity:0, transform:'translateY(20px)'}, '100%': {opacity:1, transform:'translateY(0)'}} },
            animation: { fadeInUp:'fadeInUp 0.6s ease-out forwards' }
        }
    }
}
</script>
<style>
/* Image hover zoom effect */
.room-image-container img {
    transition: transform 0.5s ease;
}
.room-image-container img:hover {
    transform: scale(1.05);
}
/* Force black text for booking details */
.booking-details * {
    color: #000 !important;
}
</style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
<main>
<section class="relative h-60 flex items-center justify-center text-center overflow-hidden">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="parallax absolute inset-0" style="background-image: url('assets/img/hero/roomspage_hero.jpg');"></div>
    <div class="relative max-w-3xl px-4 sm:px-6 lg:px-8 z-10">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Your Reservations</h1>
        <p class="text-lg text-white/90">View and manage all your upcoming and past stays.</p>
    </div>
</section>

<section class="py-16 bg-gray-50 dark:bg-gray-900">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8">Booking History 📅</h2>

    <?php if (!empty($allBookings)): ?>
    <div class="space-y-10">
        <?php foreach ($allBookings as $booking): ?>
        <div class="bg-white dark:bg-gray-800 shadow-xl hover:shadow-2xl transition duration-300 rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700 text-center booking-details">

            <!-- Centered Room Image with hover zoom -->
            <div class="room-image-container mx-auto my-4 w-50 h-80 overflow-hidden rounded-md shadow-lg">
                <img src="<?= htmlspecialchars($booking['room_image']) ?>" alt="<?= htmlspecialchars($booking['room_name']) ?>" class="w-full h-full object-cover">
            </div>

            <!-- Booking Details -->
            <div class="p-6 text-left md:text-left">
                <h3 class="text-2xl font-bold mb-2"><?= htmlspecialchars($booking['room_name']) ?></h3>
                <p class="text-sm mb-4"><?= nl2br(htmlspecialchars($booking['room_desc'])) ?></p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Booking Summary -->
                    <div>
                        <h4 class="text-2xl font-bold mb-2">Booking Summary</h4>
                        <div class="space-y-1 text-sm">
                            <p><strong>Booking ID:</strong> <?= htmlspecialchars($booking['id']) ?></p>
                            <p><strong>Guests:</strong> <?= htmlspecialchars($booking['adults']) ?> Adults, <?= htmlspecialchars($booking['children']) ?> Children</p>
                            <p><strong>Check-in:</strong> <?= htmlspecialchars($booking['check_in']) ?></p>
                            <p><strong>Check-out:</strong> <?= htmlspecialchars($booking['check_out']) ?></p>
                            <p><strong>Nights:</strong> <?= $nights ?></p>
                        </div>
                    </div>

                    <!-- Charges & Status -->
                    <div class="border-t md:border-t-0 md:border-l border-gray-100 dark:border-gray-600 pt-4 md:pt-0 md:pl-4">
                        <h4 class="text-lg font-semibold mb-2">Charges & Status</h4>
                        <div class="space-y-2 text-sm">
                            <p><strong>Room Price:</strong> Rs <?= number_format($booking['room_price'],2) ?> / night</p>
                            <p><strong>Final Total:</strong> Rs <?= number_format($booking['calculated_total'], 2) ?></p>
                            <p><strong>Payment Status:</strong> 
                                <span class="<?= $booking['payment_status']==='Paid' ? 'text-green-500' : 'text-yellow-500 font-bold' ?> bg-yellow-100 dark:bg-yellow-900/50 px-2 py-0.5 rounded-full text-xs uppercase">
                                    <?= htmlspecialchars($booking['payment_status']) ?>
                                </span>
                            </p>
                            <!-- Cancel Booking Button -->
                            <div class="mt-4 hidden md:block">
                                <form action="" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 shadow-md">
                                        <i class="fas fa-times-circle mr-1"></i> Cancel Booking
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Amenities -->
                    <div class="border-t md:border-t-0 md:border-l border-gray-100 dark:border-gray-600 pt-4 md:pt-0 md:pl-4">
                        <h4 class="text-lg font-semibold mb-2">Room Amenities</h4>
                        <div class="grid grid-cols-2 gap-y-2 text-xs">
                            <?php foreach ($staticAmenities as $amenity) { ?>
                                <div class="flex items-center justify-center md:justify-start">
                                    <i class="<?= $amenity['icon']; ?> mr-2 w-4 h-4"></i>
                                    <span><?= $amenity['name']; ?></span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Cancel Button for Mobile -->
                <div class="mt-6 md:hidden">
                    <form action="" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-3 rounded-lg transition-colors duration-200 shadow-lg">
                            <i class="fas fa-times-circle mr-2"></i> Cancel Booking
                        </button>
                    </form>
                </div>
            </div>

        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-16 border-4 border-dashed border-primary-300/50 dark:border-primary-700/50 rounded-2xl bg-white dark:bg-gray-800 shadow-xl">
        <i class="fas fa-box-open text-7xl text-primary-500 mb-6 animate-pulse"></i>
        <p class="text-2xl font-bold mb-4">No Reservations Found</p>
        <p class="text-lg mb-8">It looks like your travel plans are still being made. Let's fix that!</p>
        <a href="rooms.php" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-8 rounded-full text-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
            Book Your Dream Stay Now!
        </a>
    </div>
    <?php endif; ?>
</div>
</section>
</main>

<footer class="bg-gray-100 dark:bg-gray-800 py-8 text-center mt-auto">
    <p class="text-gray-700 dark:text-gray-300">&copy; 2025 HG Resort. All rights reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</body>
</html>

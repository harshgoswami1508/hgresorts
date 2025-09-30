<?php
session_start();

// 1️⃣ Login check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 7️⃣ Check for success status in URL after redirection from confirm_booking.php
$booking_confirmed = (isset($_GET['status']) && $_GET['status'] === 'success');

// ⚠️ DATABASE CONFIGURATION
if (!defined('DB_SERVER'))
    define('DB_SERVER', 'localhost');
if (!defined('DB_USER'))
    define('DB_USER', 'root');
if (!defined('DB_PASS'))
    define('DB_PASS', '');
if (!defined('DB_NAME'))
    define('DB_NAME', 'hgresort');

$host = DB_SERVER;
$db = DB_NAME;
$dbUser = DB_USER;
$dbPass = DB_PASS;
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// Initialize variables
$fullName = 'Guest';
$userEmail = 'guest@example.com';
$userPhone = '';
$userFirstName = '';
$userLastName = '';

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
    $userId = $_SESSION['user_id'];

    // 2️⃣ Retrieve user data from database including phone number
    $stmt = $pdo->prepare("SELECT full_name, email, phone FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $userData = $stmt->fetch();

    if ($userData) {
        $fullName = $userData['full_name'] ?? $fullName;
        $userEmail = $userData['email'] ?? $userEmail;
        $userPhone = $userData['phone'] ?? $userPhone;

        // Split full name into first/last
        $nameParts = explode(' ', $fullName);
        $userFirstName = $nameParts[0] ?? '';
        $userLastName = implode(' ', array_slice($nameParts, 1));
    } else {
        $fullName = 'User Not Found';
        $userEmail = 'error@example.com';
        $userPhone = '';
    }

} catch (\PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $fullName = 'Database Error';
    $userEmail = 'db-error@example.com';
    $userPhone = '';
}

// 3️⃣ Get room details from URL
$roomName = isset($_GET['roomName']) ? urldecode($_GET['roomName']) : null;
$roomPrice = isset($_GET['price']) ? urldecode($_GET['price']) : null;
$roomImage = isset($_GET['image']) ? urldecode($_GET['image']) : null;

// 4️⃣ Room descriptions
$roomDescriptions = [
    'Deluxe Ocean View' => 'Spacious room with a private balcony overlooking the sea.',
    'Executive Suite' => 'Separate living area, jacuzzi bath, and premium services.',
    'Standard Garden Room' => 'Comfortable room with a view of our lush tropical gardens.'
];
$roomDesc = $roomDescriptions[$roomName] ?? 'Enjoy a comfortable stay with all amenities included.';

// 5️⃣ Fees
$resortFee = 250; // ₹ per stay
$taxPercent = 18; // 18%

// 6️⃣ Amenities
$amenitiesGroups = [
    'Essential Comfort' => [
        'WiFi' => ['icon' => 'fa-wifi'],
        'Room Service' => ['icon' => 'fa-concierge-bell'],
        'Parking' => ['icon' => 'fa-car'],
    ],
    'Leisure & Relaxation' => [
        'Pool Access' => ['icon' => 'fa-swimming-pool'],
        'Gym Access' => ['icon' => 'fa-dumbbell'],
        'Spa Access' => ['icon' => 'fa-spa'],
    ],
    'Premium Services' => [
        'Meals' => ['icon' => 'fa-utensils'],
        'Airport Pickup' => ['icon' => 'fa-shuttle-space'],
        'Laundry Service' => ['icon' => 'fa-tshirt'],
    ],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HG Resort - Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/@preline/preline@2.0.0/dist/preline.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd', 300: '#7dd3fc', 400: '#38bdf8', 500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1', 800: '#075985', 900: '#0c4a6e' }
                    }
                }
            }
        }
    </script>
    <style>
        .form-card {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, .1), 0 4px 6px -2px rgba(0, 0, 0, .05);
        }

        .input-field {
            width: 100%;
            padding: .75rem;
            border: 1px solid #d1d5db;
            border-radius: .5rem;
            transition: .15s;
            outline: none;
        }

        .input-field:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, .3);
        }

        .input-field[readonly] {
            background-color: #f3f4f6;
            cursor: not-allowed;
            color: #4b5563;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: .75rem;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .input-with-icon {
            position: relative;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <?php include(isset($_SESSION['user_name']) ? 'common/userheader.php' : 'common/header.php'); ?>

    <section class="hero-section relative h-64 flex items-center justify-center text-center"
        style="background-image:url('assets/img/hero/4.jpg');background-size:cover;">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10 text-white">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">Book Your Stay</h1>
            <p class="text-lg md:text-xl">Reserve your perfect getaway at HG Resort</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:py-16 lg:px-8">
        <?php if ($booking_confirmed): ?>
            <div id="success-banner"
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="py-1"><svg class="fill-current h-6 w-6 text-green-500 mr-4"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM6.7 9.29L9 11.6l4.3-4.3 1.4 1.4L9 14.4l-3.7-3.7 1.4-1.4z" />
                        </svg></div>
                    <div>
                        <p class="font-bold text-xl">🎉 Booking Confirmed! 🥂</p>
                        <p>Your reservation has been successfully secured. We've sent a confirmation email with your booking
                            details.</p>
                    </div>
                </div>
            </div>
            <script>setTimeout(() => { window.location.href = 'dashboard.php'; }, 5000);</script>
            <style>
                #bookingForm,
                #bookingSummary {
                    display: none;
                }
            </style>
        <?php else: ?>

            <div class="lg:grid lg:grid-cols-3 lg:gap-12">

                <div class="lg:col-span-2 form-card bg-white p-6 sm:p-8 rounded-2xl shadow-xl">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-3">Reservation Details</h2>
                    <form id="bookingForm" method="POST" action="confirm_booking.php">
                        <input type="hidden" name="final_total" id="finalTotalHidden">
                        <input type="hidden" name="payment_method" value="at_resort">

                        <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-3">Guest Contact</h2>
                        <div class="grid sm:grid-cols-3 gap-6 mb-8">
                            <div class="input-with-icon">
                                <i class="fas fa-user input-icon text-black"></i>
                                <input type="text" name="full_name" required class="input-field pl-10"
                                    placeholder="Full Name" value="<?php echo htmlspecialchars($fullName); ?>">
                                <input type="hidden" name="first_name"
                                    value="<?php echo htmlspecialchars($userFirstName); ?>">
                                <input type="hidden" name="last_name"
                                    value="<?php echo htmlspecialchars($userLastName); ?>">
                            </div>

                            <div class="input-with-icon">
                                <i class="fas fa-envelope input-icon text-black"></i>
                                <input type="email" name="email" required class="input-field pl-10"
                                    placeholder="Email Address" value="<?php echo htmlspecialchars($userEmail); ?>">
                            </div>

                            <div class="input-with-icon">
                                <i class="fas fa-phone input-icon text-black"></i>
                                <input type="tel" name="phone" required class="input-field pl-10" placeholder="Phone Number"
                                    value="<?php echo htmlspecialchars($userPhone); ?>" maxlength="10" pattern="[0-9]{10}"
                                    inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)">
                            </div>
                        </div>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-3">Stay Details</h2>
                        <div class="grid md:grid-cols-3 gap-6 mb-8">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Check-in Date</label>
                                <input type="date" name="check_in" id="checkInDate" required class="input-field">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Check-out Date</label>
                                <input type="date" name="check_out" id="checkOutDate" required class="input-field">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Adults</label>
                                    <input type="number" name="adults" id="adults" required min="1" value="1"
                                        class="input-field">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Children</label>
                                    <input type="number" name="children" id="children" required min="0" value="0"
                                        class="input-field">
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-lg font-semibold text-gray-800 mb-3">Room Selected</label>
                            <div class="p-4 border-2 border-gray-200 rounded-xl bg-gray-50">
                                <span
                                    class="font-medium text-gray-900"><?php echo $roomName ?: 'Room not selected'; ?></span>
                                <p class="text-sm text-gray-500 mt-1"><?php echo $roomDesc; ?></p>
                                <input type="hidden" name="room_name" value="<?php echo htmlspecialchars($roomName); ?>">
                                <input type="hidden" name="room_price" value="<?php echo htmlspecialchars($roomPrice); ?>">
                                <input type="hidden" name="resort_fee" value="<?php echo htmlspecialchars($resortFee); ?>">
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-lg font-semibold text-gray-800 mb-4">Included Amenities</label>
                            <div id="amenitiesContainer" class="space-y-6">
                                <?php foreach ($amenitiesGroups as $groupName => $amenities): ?>
                                    <div class="border border-gray-200 p-4 rounded-xl bg-gray-50">
                                        <h4 class="text-md font-bold text-primary-700 mb-3"><?php echo $groupName; ?></h4>
                                        <div class="grid sm:grid-cols-3 gap-4">
                                            <?php foreach ($amenities as $amenityName => $details): ?>
                                                <div
                                                    class="flex items-center p-3 border border-gray-200 rounded-lg bg-white transition duration-300 shadow-sm">
                                                    <i
                                                        class="fas <?php echo $details['icon']; ?> text-xl text-primary-600 mr-3"></i>
                                                    <div class="flex-grow"><span
                                                            class="text-gray-800 font-medium block"><?php echo $amenityName; ?></span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full py-4 bg-green-600 text-white font-bold text-lg rounded-xl hover:bg-green-700 transition duration-300 shadow-lg">
                            Confirm Booking & Pay at Resort
                        </button>
                    </form>
                </div>

                <div class="mt-8 lg:mt-0">
                    <div class="sticky top-8 bg-primary-50 p-6 rounded-2xl shadow-md border border-primary-200"
                        id="bookingSummary">
                        <?php if ($roomName && $roomImage && $roomPrice): ?>
                            <img src="<?php echo $roomImage; ?>" alt="<?php echo $roomName; ?>"
                                class="w-full h-auto rounded-xl object-cover mb-4 shadow-lg">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2"><?php echo $roomName; ?></h3>
                            <p class="text-gray-700 mb-4"><?php echo $roomDesc; ?></p>
                            <div class="space-y-3 text-gray-700">
                                <div class="flex justify-between"><span>Room Rate (per Night)</span><span
                                        class="font-medium">₹<?php echo $roomPrice; ?></span></div>
                                <div class="flex justify-between"><span>Service Charge (Per Stay)</span><span
                                        class="font-medium">₹<?php echo $resortFee; ?></span></div>
                                <div class="flex justify-between"><span>Taxes & Fees (<?php echo $taxPercent; ?>%)</span><span
                                        class="font-medium" id="taxAmount">₹0.00</span></div>
                                <div class="border-t border-dashed pt-4 mt-4"></div>
                                <div class="flex justify-between text-2xl font-bold text-primary-700"><span>Grand
                                        Total</span><span class="font-medium" id="grandTotal">₹0.00</span></div>
                                <p class="text-sm text-gray-500 mt-4 pt-2 border-t text-center">*Final price calculated based on
                                    your stay duration and number of guests. Payment will be due upon check-in.</p>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-700">No room selected. Please go back and choose a room.</p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-gray-100 dark:bg-gray-800 py-8 text-center">
        <p class="text-gray-700 dark:text-gray-300">&copy; 2025 HG Resort. All rights reserved.</p>
    </footer>

    <?php include('common/script.php'); ?>
    <script>
        window.onload = function () {
            const today = new Date().toISOString().split('T')[0];
            const checkIn = document.getElementById('checkInDate');
            const checkOut = document.getElementById('checkOutDate');
            const adultsInput = document.getElementById('adults');
            const childrenInput = document.getElementById('children');

            checkIn.setAttribute('min', today);
            checkOut.setAttribute('min', today);

            function updateTotal() {
                const roomPrice = parseFloat(<?php echo $roomPrice ?: 0; ?>);
                const resortFee = parseFloat(<?php echo $resortFee; ?>);
                const taxPercent = parseFloat(<?php echo $taxPercent; ?>);

                const inDate = new Date(checkIn.value);
                const outDate = new Date(checkOut.value);
                let nights = Math.ceil((outDate - inDate) / (1000 * 60 * 60 * 24));
                if (nights < 1 || isNaN(nights) || outDate <= inDate) nights = 1;

                const adults = parseInt(adultsInput.value) || 1;
                const children = parseInt(childrenInput.value) || 0;
                const totalPeople = adults + children;

                const discountPercent = Math.min((totalPeople - 1) * 5, 25);
                const effectivePricePerAdult = roomPrice * (1 - discountPercent / 100);
                const roomTotal = effectivePricePerAdult * adults * nights + (children * (effectivePricePerAdult * 0.5) * nights);
                const subTotal = roomTotal + resortFee;
                const tax = subTotal * taxPercent / 100;
                const grandTotal = (subTotal + tax).toFixed(2);

                document.getElementById('taxAmount').innerText = '₹' + tax.toFixed(2);
                document.getElementById('grandTotal').innerText = '₹' + grandTotal;
                document.getElementById('finalTotalHidden').value = grandTotal;
            }

            checkIn.addEventListener('change', () => { checkOut.setAttribute('min', checkIn.value); if (new Date(checkOut.value) <= new Date(checkIn.value)) { let nextDay = new Date(checkIn.value); nextDay.setDate(nextDay.getDate() + 1); checkOut.value = nextDay.toISOString().split('T')[0]; } updateTotal(); });
            checkOut.addEventListener('change', updateTotal);
            adultsInput.addEventListener('change', updateTotal);
            childrenInput.addEventListener('change', updateTotal);

            if (!checkIn.value) checkIn.value = today;
            if (!checkOut.value) { let nextDay = new Date(checkIn.value); nextDay.setDate(nextDay.getDate() + 1); checkOut.value = nextDay.toISOString().split('T')[0]; }

            updateTotal();
        };
    </script>
</body>

</html>
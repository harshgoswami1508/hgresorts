<?php
session_start();

// 1️⃣ Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2️⃣ Database config (same as booking page)
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

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);

    // 3️⃣ Collect POST data from booking form
    $user_id = $_SESSION['user_id'];
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $check_in = $_POST['check_in'] ?? '';
    $check_out = $_POST['check_out'] ?? '';
    $adults = $_POST['adults'] ?? 1;
    $children = $_POST['children'] ?? 0;
    $room_name = $_POST['room_name'] ?? '';
    $room_price = $_POST['room_price'] ?? 0;
    $resort_fee = $_POST['resort_fee'] ?? 0;
    $final_total = $_POST['final_total'] ?? 0;
    $payment_method = $_POST['payment_method'] ?? 'at_resort';

    // 4️⃣ Insert into database
    $sql = "INSERT INTO bookings 
    (user_id, full_name, email, phone, check_in, check_out, adults, children, room_name, room_price, resort_fee, final_total, payment_method)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $user_id,
        $full_name,
        $email,
        $phone,
        $check_in,
        $check_out,
        $adults,
        $children,
        $room_name,
        $room_price,
        $resort_fee,
        $final_total,
        $payment_method
    ]);

    // 5️⃣ Redirect back to booking page with success banner
    header("Location: booking.php?status=success&roomName=$roomName&price=$roomPrice&image=$roomImage");
    exit;

} catch (PDOException $e) {
    error_log("Booking Insert Error: " . $e->getMessage());
    echo "Something went wrong saving your booking.";
}
?>
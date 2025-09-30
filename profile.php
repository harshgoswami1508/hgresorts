<?php
session_start();
require('common/con.php'); // Database connection

// Redirect if not logged in
if (!isset($_SESSION['loginId'])) {
    header("Location: login.php");
    exit();
}

$loginId = intval($_SESSION['loginId']);
$user = null;
$pastStays = [];

// Fetch user details
$stmt = $con->prepare("SELECT full_name, email FROM users WHERE id=? LIMIT 1");
$stmt->bind_param("i", $loginId);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
}
$stmt->close();

// Fetch past stays (example table: bookings)
$stayStmt = $con->prepare("SELECT room_nm, check_in, check_out FROM bookings WHERE user_id=? ORDER BY check_in DESC");
$stayStmt->bind_param("i", $loginId);
$stayStmt->execute();
$stayResult = $stayStmt->get_result();
while ($row = $stayResult->fetch_assoc()) {
    $pastStays[] = $row;
}
$stayStmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HG Resort - Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">

    <!-- Header -->
    <?php include('common/header.php'); ?>

    <!-- Profile Section -->
    <main class="py-16">
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-8 flex flex-col gap-6">

                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">My Profile</h2>

                <?php if ($user): ?>
                <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-medium mb-1">Full Name</label>
                        <input type="text" name="name"
                               class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-gray-800"
                               value="<?= htmlspecialchars($user['fullname']) ?>" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email"
                               class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-gray-800"
                               value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Phone Number</label>
                        <input type="tel" name="phone"
                               class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-gray-800"
                               value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Your Past Stays</label>
                        <ul class="list-disc list-inside bg-gray-50 dark:bg-gray-800 p-4 rounded border border-gray-200 dark:border-gray-700">
                            <?php if (!empty($pastStays)): ?>
                                <?php foreach ($pastStays as $stay): ?>
                                    <li><?= htmlspecialchars($stay['room_name']) ?> – <?= htmlspecialchars(date('d M Y', strtotime($stay['check_in']))) ?> to <?= htmlspecialchars(date('d M Y', strtotime($stay['check_out']))) ?></li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li>No past stays found.</li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit"
                                class="px-6 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 transition">
                            Update Profile
                        </button>
                    </div>

                </form>
                <?php else: ?>
                    <div class="p-4 bg-yellow-100 text-yellow-700 rounded">User not found.</div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <?php include('common/footer.php'); ?>

</body>
</html>

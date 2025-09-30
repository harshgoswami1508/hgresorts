<?php
// Start session at the very top
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

include 'common/con.php'; // Make sure your DB connection is included

// Get user's first name (split by space if full name)
$fullName = $_SESSION['user_name'];
$firstName = explode(' ', trim($fullName))[0];

// Fetch user ID
$userQuery = mysqli_query($con, "SELECT id FROM users WHERE full_name='".mysqli_real_escape_string($con, $fullName)."'");
$userRow = mysqli_fetch_assoc($userQuery);
$userId = $userRow['id'] ?? 0;

// Fetch latest 5 bookings for the new 'Recent Bookings' dropdown
$bookingQuery = mysqli_query($con, "SELECT * FROM bookings WHERE user_id='$userId' ORDER BY check_out DESC LIMIT 5");
$bookings = [];
if ($bookingQuery && mysqli_num_rows($bookingQuery) > 0) {
    while ($b = mysqli_fetch_assoc($bookingQuery)) $bookings[] = $b;
}
?>

<header class="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md shadow-sm transition-colors duration-300">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center">
                <a href="dashboard.php" class="flex items-center">
                    <i class="fas fa-umbrella-beach text-2xl text-primary-600 mr-2"></i>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">HG Resort</span>
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-6">
                <a href="dashboard.php" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Home</a>
                <a href="rooms.php" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Rooms</a>
                <a href="services.php" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Service</a>
                <a href="about.php" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">About</a>
                <a href="contact.php" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Contact</a>
                <a href="feedback.php" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Feedback</a>
                <a href="userbookings.php" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Bookings</a>

                <div class="relative flex items-center space-x-3">
                    <i class="fa-solid fa-user"></i>
                    <span class="text-gray-900 dark:text-gray-200 font-medium"><?= htmlspecialchars($firstName) ?></span>

                   

                    <a href="logout.php" class="ml-2 bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition-colors duration-200 font-medium">Logout</a>
                </div>
            </div>

            <div class="md:hidden flex items-center">
                <button id="mobileMenuButton" class="text-gray-700 dark:text-gray-300 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </nav>

    <div id="mobileMenu" class="hidden md:hidden bg-white dark:bg-gray-900/95 shadow-lg">
        <a href="dashboard.php" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Home</a>
        <a href="rooms.php" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Rooms</a>
        <a href="services.php" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Service</a>
        <a href="about.php" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">About</a>
        <a href="contact.php" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Contact</a>
        <a href="feedback.php" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Feedback</a>
        <a href="userbookings.php" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Bookings</a>

        <div class="border-t border-gray-200 dark:border-gray-700 mt-2 px-4 py-3 flex flex-col gap-2">
            <div class="flex items-center space-x-2">
                <span class="text-gray-900 dark:text-gray-200 font-medium"><?= htmlspecialchars($firstName) ?></span>
            </div>
            <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white text-center px-3 py-2 rounded font-medium transition-colors duration-200">Logout</a>
        </div>
    </div>
</header>

<script>
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Recent dropdown toggle (renamed from bookingBtn)
    const recentBtn = document.getElementById('recentBtn');
    const recentDropdown = document.getElementById('recentDropdown');
    recentBtn.addEventListener('click', () => {
        recentDropdown.classList.toggle('hidden');
    });

    // Close recent dropdown if clicked outside
    document.addEventListener('click', (e) => {
        if (!recentBtn.contains(e.target) && !recentDropdown.contains(e.target)) {
            recentDropdown.classList.add('hidden');
        }
    });
</script>
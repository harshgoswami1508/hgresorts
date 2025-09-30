<?php
// Start session at the very top
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Default header background
if (!isset($headerBg)) {
    $headerBg = 'bg-white/80 dark:bg-gray-900/80';
}

// Determine display name
$displayName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
?>

<header class="sticky top-0 z-50 <?= $headerBg ?> backdrop-blur-md shadow-sm transition-colors duration-300">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="index.php" class="flex items-center">
                    <i class="fas fa-umbrella-beach text-2xl text-primary-600 mr-2"></i>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">HG Resort</span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="index.php"
                    class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Home</a>
                <a href="rooms.php"
                    class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Rooms</a>
                <a href="services.php"
                    class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Service</a>
                <a href="about.php"
                    class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">About</a>
                <a href="contact.php"
                    class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Contact</a>
                <a href="feedback.php"
                    class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Feedback</a>

                <?php if ($displayName): ?>
                    <a href="login.php" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Login</a>
                <?php else: ?>
                    <a href="signup.php"
                        class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Signup</a>
                    <a href="login.php"
                        class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Login</a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button id="mobileMenuButton" class="text-gray-700 dark:text-gray-300 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden bg-white dark:bg-gray-900/95 shadow-lg">
        <a href="index.php"
            class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Home</a>
        <a href="rooms.php"
            class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Rooms</a>
        <a href="services.php"
            class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Service</a>
        <a href="about.php"
            class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">About</a>
        <a href="contact.php"
            class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Contact</a>
        <a href="feedback.php"
            class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Feedback</a>

        <?php if ($displayName): ?>
            <span class="block px-4 py-3 text-gray-900 dark:text-gray-200"><?= htmlspecialchars($displayName) ?></span>
            <a href="logout.php" class="block px-4 py-3 text-red-500 hover:text-red-600">Logout</a>
        <?php else: ?>
            <a href="signup.php"
                class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Signup</a>
            <a href="login.php"
                class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Login</a>
        <?php endif; ?>
    </div>
</header>

<script>
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');

    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>
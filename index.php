<?php
include_once('common/con.php');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- STATIC AMENITIES DATA ---
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HG Resort - Luxury Beachfront Accommodation</title>
    <base target="_self">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/@preline/preline@2.0.0/dist/preline.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: 0, transform: 'translateY(20px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)' }
                        }
                    },
                    animation: {
                        fadeInUp: 'fadeInUp 0.6s ease-out forwards'
                    }
                }
            }
        }
    </script>
    <style>
        .parallax {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            will-change: transform;
        }

        .room-card, .amenity-card {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease-out;
        }

        .room-card.visible, .amenity-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .room-card:hover img, .gallery-img:hover {
            transform: scale(1.05);
            transition: transform 0.5s ease;
        }
    </style>
    <?php include('common/style.php'); ?>
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">

    <!-- Navigation -->
    <?php include('common/header.php'); ?>

    <!-- Main Content -->
    <main>
        <!-- Hero Section -->
        <section id="home" class="relative h-screen flex items-center justify-center text-center overflow-hidden">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="parallax absolute inset-0" style="background-image: url('assets/img/hero/back.jpg');"></div>
            <div class="relative max-w-4xl px-4 sm:px-6 lg:px-8 z-10 fade-in">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">Experience Luxury at HG Resort</h1>
                <p class="text-xl text-gray-100 mb-8">Your perfect getaway with stunning ocean views and world-class amenities</p>
                <button onclick="navigate(event, 'rooms')" 
                    class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 transform hover:scale-105">
                    Book Your Stay
                </button>
            </div>
        </section>

        <!-- Rooms Section (Dynamic, same as rooms.php) -->
        <section id="rooms" class="py-20 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Our Accommodations</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Choose from our selection of luxurious rooms and suites designed for your comfort</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    $query = "SELECT room_nm, room_price, room_photo, descc FROM rooms_details";
                    $result = mysqli_query($con, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $roomName = $row['room_nm'];
                            $roomPrice = $row['room_price'];
                            $roomImage = $row['room_photo'];
                            $roomDesc = $row['descc'];
                    ?>
                        <div class="room-card bg-white dark:bg-gray-700 rounded-xl shadow-lg overflow-hidden flex flex-col">
                            <div class="h-64 overflow-hidden flex-shrink-0">
                                <img src="adminpanel/admin/admin/<?php echo $roomImage; ?>" alt="<?php echo $roomName; ?>" class="w-full h-full object-cover transition duration-500 ease-in-out">
                            </div>
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-2"><?php echo $roomName; ?></h3>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4"><?php echo nl2br(htmlspecialchars($roomDesc)); ?></p>
                                <div class="mb-4 pt-4 border-t border-gray-100 dark:border-gray-600 mt-auto">
                                    <h4 class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-2 uppercase tracking-wider">Room Features</h4>
                                    <div class="grid grid-cols-2 gap-y-2 text-xs">
                                        <?php foreach ($staticAmenities as $amenity) { ?>
                                            <div class="flex items-center text-gray-700 dark:text-gray-200">
                                                <i class="<?php echo $amenity['icon']; ?> mr-2 text-primary-500 w-4 h-4"></i>
                                                <span><?php echo $amenity['name']; ?></span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-4 pt-2 border-t border-gray-100 dark:border-gray-600">
                                    <p class="text-xl text-gray-900 dark:text-white font-bold">
                                        <span class="text-primary-600">Rs. <?php echo number_format($roomPrice); ?></span> / night
                                    </p>
                                    <a href="booking.php?roomName=<?php echo urlencode($roomName); ?>&price=<?php echo urlencode($roomPrice); ?>&image=<?php echo urlencode('adminpanel/admin/admin/' . $roomImage); ?>&desc=<?php echo urlencode($roomDesc); ?>"
                                        class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-6 rounded-full transition duration-300 inline-block shadow-md hover:shadow-lg">
                                        Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                    } else {
                        echo '<div class="col-span-3 text-center py-10 text-xl font-medium text-gray-700 dark:text-gray-300"><i class="fas fa-bed mr-2 text-primary-500"></i> No rooms available at the moment.</div>';
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Amenities Section -->
        <section id="amenities" class="py-20 bg-gray-50 dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Resort Amenities</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Enjoy our world-class facilities during your stay</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php foreach ($staticAmenities as $amenity) { ?>
                        <div class="amenity-card bg-white dark:bg-gray-700 p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 text-center">
                            <div class="text-primary-600 dark:text-primary-400 mb-4">
                                <i class="<?php echo $amenity['icon']; ?> text-4xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2"><?php echo $amenity['name']; ?></h3>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="py-20 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Contact Us</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-8">Have questions? Get in touch with our friendly team.</p>
                <a href="contact.php" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-6 rounded-full transition duration-300">Email Us</a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 dark:bg-gray-800 py-8 text-center">
        <p class="text-gray-700 dark:text-gray-300">&copy; 2025 HG Resort. All rights reserved.</p>
    </footer>

    <script>
        // Animate room cards & amenities on scroll
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('visible');
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.room-card, .amenity-card').forEach(el => observer.observe(el));

        // Navigation smooth scroll
        function navigate(event, sectionId) {
            event.preventDefault();
            document.getElementById(sectionId).scrollIntoView({ behavior: 'smooth' });
        }
    </script>

    <?php include('common/script.php'); ?>
</body>
</html>

<?php
include_once('common/con.php');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- UPDATED STATIC AMENITIES DATA ---
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

// Fetch all room details
$query = "SELECT room_nm, room_price, room_photo, descc FROM rooms_details";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HG Resort - Rooms</title>
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
        .room-card { opacity: 0; transform: translateY(20px); transition: all 0.6s ease-out; height: 100%; } /* Added height: 100% */
        .room-card.visible { opacity: 1; transform: translateY(0); }
        .room-card:hover img { transform: scale(1.05); transition: transform 0.5s ease; }
        .gallery-img { opacity: 0; transform: scale(0.95); transition: opacity 0.6s ease, transform 0.6s ease; }
        .gallery-img.visible { opacity: 1; transform: scale(1); }
        .gallery-img:hover { transform: scale(1.05); }
    </style>

    <?php include('common/style.php'); ?>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">

    <?php
    if (isset($_SESSION['user_name'])) {
        include('common/userheader.php');
    } else {
        include('common/header.php');
    }
    ?>

    <main>
        <section class="relative h-80 flex items-center justify-center text-center overflow-hidden"
            style="background-image: url('assets/img/hero/roomspage_hero.jpg'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative z-10 text-white">
                <h1 class="text-4xl md:text-5xl text-white font-bold">Our Rooms</h1>
                <p class="mt-4 text-lg font-medium text-black tracking-wide">Explore our luxurious accommodations for a perfect stay</p>
            </div>
        </section>

        <section id="rooms" class="py-20 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $roomName = $row['room_nm'];
                            $roomPrice = $row['room_price'];
                            $roomImage = $row['room_photo'];
                            $roomDesc = $row['descc'];
                    ?>
                        <div class="room-card bg-white dark:bg-gray-700 rounded-xl shadow-lg overflow-hidden transition transform duration-300 flex flex-col">
                            <div class="h-64 overflow-hidden flex-shrink-0">
                                <img src="adminpanel/admin/admin/<?php echo $roomImage; ?>" alt="<?php echo $roomName; ?>" class="w-full h-full object-cover transition duration-500 ease-in-out">
                            </div>
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-2"><?php echo $roomName; ?></h3>
                                
                                <div class="mb-4">
                                    <p class="text-gray-600 dark:text-gray-300 text-sm"><?php echo nl2br(htmlspecialchars($roomDesc)); ?></p>
                                </div>
                                
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
                        echo '<div class="col-span-3 text-center py-10 text-xl font-medium text-gray-700 dark:text-gray-300">
                                <i class="fas fa-bed mr-2 text-primary-500"></i> No rooms available at the moment. Please check back later.
                              </div>';
                    }
                    ?>
                </div>
            </div>
        </section>

        <section class="py-16 bg-gray-50 dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Resort Gallery</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <img src="assets/img/gallery/gallery1.jpg" alt="Gallery 1" class="gallery-img rounded-xl shadow-lg w-full h-60 object-cover">
                    <img src="assets/img/gallery/gallery2.jpg" alt="Gallery 2" class="gallery-img rounded-xl shadow-lg w-full h-60 object-cover">
                    <img src="assets/img/gallery/gallery3.jpg" alt="Gallery 3" class="gallery-img rounded-xl shadow-lg w-full h-60 object-cover">
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-gray-100 dark:bg-gray-800 py-8 text-center border-t border-gray-200 dark:border-gray-700">
        <p class="text-gray-700 dark:text-gray-300">&copy; 2025 HG Resort. All rights reserved.</p>
    </footer>

    <?php include('common/script.php'); ?>
    <script>
        // Animate Room Cards on Scroll (using Intersection Observer)
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target); // Stop observing once visible
                }
            });
        }, { threshold: 0.1 });
        
        // Observe both room cards and gallery images
        document.querySelectorAll('.room-card').forEach(card => observer.observe(card));
        document.querySelectorAll('.gallery-img').forEach(img => observer.observe(img));
    </script>
</body>
</html>
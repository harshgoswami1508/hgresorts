<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HG Resort - About Us</title>
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

        .gallery-item {
            opacity: 0;
            transform: scale(0.95);
            transition: all 0.5s ease;
            cursor: pointer;
        }

        .gallery-item.visible {
            opacity: 1;
            transform: scale(1);
        }

        .gallery-item:hover {
            transform: scale(1.05);
            filter: brightness(1.1);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.4s ease;
        }

        .hero-section {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
    <?php include('common/style.php'); ?>
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">

    <?php
    // Include correct header
    if (isset($_SESSION['user_name'])) {
        include('common/userheader.php');
    } else {
        include('common/header.php');
    }
    ?>

    <!-- Hero Section -->
    <section class="relative h-[300px] md:h-[400px] flex items-center justify-center text-center overflow-hidden">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="parallax absolute inset-0"
            style="background-image: url('assets/img/hero/aboutpage_hero.jpg'); background-size: cover; background-position: center top;">
        </div>
        <div class="relative max-w-4xl px-4 sm:px-6 lg:px-8 z-10 fade-in">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">About HG Resort</h1>
            <p class="text-xl text-gray-100 mb-8">Luxury, comfort, and unforgettable experiences await you</p>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-20 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-12 items-center">
            <div class="animate-fadeInUp">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Welcome to HG Resorts</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-4">
                    At HG Resorts, we are dedicated to providing you with an unparalleled experience in luxury and
                    relaxation. Our story began with a passion for creating memorable moments for travelers seeking the
                    perfect getaway.
                </p>
                <p class="text-gray-600 dark:text-gray-300">
                    Nestled in some of the world's most stunning locations, our resorts offer a unique blend of natural
                    beauty and modern comfort. We pride ourselves on our commitment to quality, exceptional service, and
                    sustainable practices.
                </p>
            </div>
            <div class="animate-fadeInUp">
                <img src="assets/img/image/about.jpg" alt="HG Resort" class="rounded-xl shadow-lg w-full object-cover">
            </div>
        </div>
    </section>

    <!-- Customer Experience Section -->
    <section class="py-20 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-12 items-center">
            <div class="relative">
                <img src="assets/img/customer/customar1.png" alt="Customer"
                    class="rounded-xl shadow-lg animate-fadeInUp mb-6">
                <div
                    class="absolute bottom-4 left-4 bg-primary-600 text-white px-6 py-4 rounded-xl shadow-lg animate-bounce">
                    <h3 class="text-lg font-bold">2 Years of Service Experience</h3>
                </div>
            </div>
            <div class="animate-fadeInUp">
                <span class="text-primary-600 uppercase font-bold">About our Resorts</span>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Make the customer the hero of your
                    story</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-4">
                    Resort fun is all about providing a variety of enjoyable experiences for guests, ensuring that they
                    have a memorable and relaxing time during their vacation.
                </p>
                <p class="text-gray-600 dark:text-gray-300">
                    The specific activities and amenities can vary widely depending on the type and location of the
                    resort, from spa treatments to adventurous excursions.
                </p>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-20 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Gallery</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <img src="assets/img/gallery/gallery1.jpg" alt="Gallery"
                    class="rounded-xl shadow-md w-full object-cover gallery-item">
                <img src="assets/img/gallery/gallery2.jpg" alt="Gallery"
                    class="rounded-xl shadow-md w-full object-cover gallery-item">
                <img src="assets/img/gallery/gallery3.jpg" alt="Gallery"
                    class="rounded-xl shadow-md w-full object-cover gallery-item">
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-100 dark:bg-gray-800 py-8 text-center">
        <p class="text-gray-700 dark:text-gray-300">&copy; 2025 HG Resort. All rights reserved.</p>
    </footer>

    <!-- Scripts -->
    <?php include('common/script.php'); ?>
    <script>
        // Intersection Observer for fadeInUp animations
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fadeInUp');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.animate-fadeInUp').forEach(el => observer.observe(el));

        // Gallery fade-in and scale-up
        const galleryObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.2 });

        document.querySelectorAll('.gallery-item').forEach((item, index) => {
            galleryObserver.observe(item);
            item.style.transitionDelay = `${index * 0.2}s`; // staggered delay
        });
    </script>
</body>

</html>

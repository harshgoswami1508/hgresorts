<?php
// Start session and DB connection if needed
include('common/con.php'); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HG Resort - Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/@preline/preline@2.0.0/dist/preline.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php include('common/style.php'); ?>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#f0f9ff',100:'#e0f2fe',200:'#bae6fd',300:'#7dd3fc',400:'#38bdf8',500:'#0ea5e9',600:'#0284c7',700:'#0369a1',800:'#075985',900:'#0c4a6e' }
                    },
                    keyframes: {
                        fadeInUp: {'0%':{opacity:0,transform:'translateY(20px)'},'100%':{opacity:1,transform:'translateY(0)'}}
                    },
                    animation: { fadeInUp:'fadeInUp 0.6s ease-out forwards' }
                }
            }
        }
    </script>

    <style>
        .parallax { background-attachment: fixed; background-position: center; background-repeat: no-repeat; background-size: cover; will-change: transform; }
        .gallery-item { opacity: 0; transform: scale(0.95); transition: all 0.5s ease; cursor: pointer; }
        .gallery-item.visible { opacity: 1; transform: scale(1); }
        .gallery-item:hover { transform: scale(1.05); filter: brightness(1.1); box-shadow: 0 10px 20px rgba(0,0,0,0.2); transition: all 0.4s ease; }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">

    <!-- Header: Use user header if logged in -->
    <?php
    if (isset($_SESSION['user_name'])) {
        include('common/userheader.php');
    } else {
        include('common/header.php');
    }
    ?>

    <!-- Main Content -->
    <main>
        <!-- Hero Section -->
        <section class="hero-section relative h-80 flex items-center justify-center text-center" style="background-image: url('assets/img/hero/servicespage_hero.jpg');">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative z-10 text-white">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">Services</h1>
                <p class="text-lg text-black md:text-xl">Explore our premium resort services</p>
            </div>
        </section>

        <!-- Services Details -->
        <section class="py-20 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20">

                <!-- Dining -->
                <div class="flex flex-col lg:flex-row lg:items-center lg:space-x-12">
                    <div class="lg:w-1/2">
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Dining & Drinks</h3>
                        <p class="text-gray-600 dark:text-gray-300">The dining experience at HG Resorts is a culinary journey that caters to all tastes and preferences. Our inviting dining areas provide the perfect backdrop for enjoying a diverse range of delectable dishes, from local specialties to international cuisine.</p>
                    </div>
                    <div class="lg:w-1/2 mt-6 lg:mt-0">
                        <img src="assets/img/dining/dining.png" alt="Dining" class="rounded-xl shadow-lg w-full object-cover">
                    </div>
                </div>

                <!-- Pool -->
                <div class="flex flex-col lg:flex-row-reverse lg:items-center gap-12">
                    <div class="lg:w-1/2 lg:pr-8">
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Swimming Pool</h3>
                        <p class="text-gray-600 dark:text-gray-300">Swimming at the resort is pure paradise. The shimmering pool, nestled amidst the tropical beauty, offers a refreshing escape from the everyday hustle. Whether you're floating lazily, sipping cocktails by the water's edge, or taking a dip to cool off, the resort's poolside serenity is the perfect way to unwind and savor the moments of relaxation.</p>
                    </div>
                    <div class="lg:w-1/2 mt-6 lg:mt-0 lg:-ml-8">
                        <img src="assets/img/image/Swimming.jpg" alt="Pool" class="rounded-xl shadow-lg w-full object-cover">
                    </div>
                </div>

                <!-- Gallery -->
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Gallery</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <img src="assets/img/gallery/gallery1.jpg" alt="Gallery" class="rounded-xl shadow-md w-full object-cover gallery-item">
                        <img src="assets/img/gallery/gallery2.jpg" alt="Gallery" class="rounded-xl shadow-md w-full object-cover gallery-item">
                        <img src="assets/img/gallery/gallery3.jpg" alt="Gallery" class="rounded-xl shadow-md w-full object-cover gallery-item">
                    </div>
                </div>

            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 dark:bg-gray-800 py-8 text-center">
        <p class="text-gray-700 dark:text-gray-300">&copy; 2025 HG Resort. All rights reserved.</p>
    </footer>

    <?php include('common/script.php'); ?>
    <script>
        // Fade-in gallery on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.2 });

        document.querySelectorAll('.gallery-item').forEach(item => observer.observe(item));
        document.querySelectorAll('.gallery-item').forEach((item, index) => {
            item.style.transitionDelay = `${index * 0.2}s`; // staggered delay
        });
    </script>
</body>
</html>

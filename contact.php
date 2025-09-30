<?php
include('common/con.php'); // DB connection

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and get details
$user_name = '';
$user_email = '';
$user_phone = '';
$user_logged_in = false;

if (isset($_SESSION['user_id'])) {
    $user_logged_in = true;
    $user_id = $_SESSION['user_id'];
    $query = mysqli_query($con, "SELECT full_name, email, phone FROM users WHERE id='$user_id'");
    if ($query && mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);
        $user_name = $user['full_name'];
        $user_email = $user['email'];
        $user_phone = $user['phone'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HG Resort - Contact Us</title>
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
        .parallax {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            will-change: transform;
        }

        /* Modal transitions */
        #messageBox { transition: opacity 0.3s ease, transform 0.3s ease; }
        #messageBox.show { opacity: 1 !important; transform: scale(1) !important; display:flex !important; }
        #messageBox.hidden-box { opacity: 0 !important; transform: scale(0.9) !important; display:none !important; }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">

    <!-- Header -->
    <?php
    if ($user_logged_in) {
        include('common/userheader.php');
    } else {
        include('common/header.php');
    }
    ?>

    <main>
        <!-- Contact Hero -->
        <section class="relative h-96 flex items-center justify-center text-center overflow-hidden">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="parallax absolute inset-0" style="background-image: url('assets/img/hero/2.jpg');"></div>
            <div class="relative max-w-3xl px-4 sm:px-6 lg:px-8 z-10">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Get in Touch</h1>
                <p class="text-lg text-black">We’re here to answer any questions about your stay</p>
            </div>
        </section>

        <!-- Contact Form Section -->
        <section class="py-20 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <section class="rounded-2xl p-8 md:p-12 shadow-xl max-w-3xl mx-auto" style="background-image: url('assets/img/hero/1.jpg');">
                    <h2 class="text-3xl font-bold text-black text-center mb-8">Send Us a Message</h2>
                    <form id="contactForm" class="space-y-6">
                        <div>
                            <label for="name" class="block text-lg font-medium text-black mb-2">Full Name</label>
                            <input type="text" id="name" name="name" class="form-input block w-full rounded-md shadow-sm p-3" required
                                   value="<?php echo htmlspecialchars($user_name); ?>">
                        </div>
                        <div>
                            <label for="email" class="block text-lg font-medium text-black mb-2">Email Address</label>
                            <input type="email" id="email" name="email" class="form-input block w-full rounded-md shadow-sm p-3" required
                                   value="<?php echo htmlspecialchars($user_email); ?>">
                        </div>
                        <div>
                            <label for="phone" class="block text-lg font-medium text-black mb-2">Phone Number (Optional)</label>
                            <input type="tel" id="phone" name="phone" class="form-input block w-full rounded-md shadow-sm p-3"
                                   value="<?php echo htmlspecialchars($user_phone); ?>">
                        </div>
                        <div>
                            <label for="message" class="block text-lg font-medium text-black mb-2">Your Message</label>
                            <textarea id="message" name="message" rows="5" class="form-textarea block w-full rounded-md shadow-sm p-3" required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="w-full bg-none hover:bg-indigo-700 text-black font-bold py-3 px-6 rounded-full transition-all duration-300 shadow-lg">Send Message</button>
                        </div>
                    </form>

                    <!-- Popup Modal -->
                    <div id="messageBox" class="hidden-box fixed inset-0 flex items-center justify-center bg-black/50 z-50 opacity-0 transform scale-90">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 max-w-md w-full text-center">
                            <i id="messageIcon" class="fas fa-info-circle text-5xl text-primary-600 mb-4"></i>
                            <h3 id="messageTitle" class="text-2xl font-bold mb-4"></h3>
                            <p id="messageText" class="text-gray-700 dark:text-gray-300 mb-6"></p>
                            <button id="actionButton" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-full transition-colors duration-300"></button>
                        </div>
                    </div>

                </section>
            </div>
        </section>
    </main>

    <footer class="bg-gray-100 dark:bg-gray-800 py-8 text-center">
        <p class="text-gray-700 dark:text-gray-300">&copy; 2025 HG Resort. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $('#contactForm').submit(function (e) {
            e.preventDefault();

            <?php if(!$user_logged_in) { ?>
                // Not logged in → show login popup
                $('#messageIcon').attr('class','fas fa-lock text-5xl text-red-500 mb-4');
                $('#messageTitle').text('Login Required');
                $('#messageText').text('Please login first to send a message.');
                $('#actionButton').text('Go to Login');

                $('#messageBox').removeClass('hidden-box').addClass('show');

                $('#actionButton').off('click').on('click', function () {
                    window.location.href = 'login.php';
                });
                return;
            <?php } else { ?>
                // Logged in → submit form via AJAX
                var formData = $(this).serialize();
                $.ajax({
                    url: 'contact_records.php',
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response === 'success') {
                            $('#contactForm')[0].reset();
                            $('#messageIcon').attr('class','fas fa-check-circle text-5xl text-green-500 mb-4');
                            $('#messageTitle').text('Message Sent!');
                            $('#messageText').text('Thank you for contacting us. We will get back to you shortly.');
                            $('#actionButton').text('Close');

                            $('#messageBox').removeClass('hidden-box').addClass('show');

                            $('#actionButton').off('click').on('click', function () {
                                $('#messageBox').removeClass('show').addClass('hidden-box');
                            });
                        } else {
                            alert('Error: ' + response);
                        }
                    },
                    error: function () { alert('Something went wrong. Please try again.'); }
                });
            <?php } ?>
        });
    </script>

</body>
</html>

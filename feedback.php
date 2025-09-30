<?php
include('common/style.php');
include('common/con.php');
session_start(); // Ensure session is started

// --- Dynamic Pre-fill Logic ---
$prefillFullName = '';
$prefillEmail = '';
$prefillPhone = '';

$user_logged_in = false;
if (isset($_SESSION['user_id'])) {
    $user_logged_in = true;
    $userId = intval($_SESSION['user_id']);
    // fetch user details from DB
    $userSql = "SELECT full_name, email, phone FROM users WHERE id = $userId LIMIT 1";
    $userResult = mysqli_query($con, $userSql);
    if ($userRow = mysqli_fetch_assoc($userResult)) {
        $prefillFullName = $userRow['full_name'];
        $prefillEmail = $userRow['email'];
        $prefillPhone = $userRow['phone'];
    }
}

// --- Handle form submit ---
$dbSuccess = false;
$dbError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $fullName = mysqli_real_escape_string($con, $_POST['fullName']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $customerFeedback = mysqli_real_escape_string($con, $_POST['customerFeedback']);
    $overallRating = mysqli_real_escape_string($con, $_POST['overallRating']);
    $userId = intval($_SESSION['user_id']);

    $sql = "INSERT INTO feedbacks (user_id, full_name, email, phone, comments, overall_rating, created_at) 
        VALUES ($userId, '$fullName', '$email', '$phone', '$customerFeedback', '$overallRating', NOW())";

    if (mysqli_query($con, $sql)) {
        $dbSuccess = true;
    } else {
        $dbError = mysqli_error($con);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resort Feedback Form</title>
    <link rel="stylesheet" href="assets/css/feedback.css">
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
                    },
                    keyframes: {
                        fadeInUp: { '0%': { opacity: 0, transform: 'translateY(20px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
                        pulseShadow: { '0%,100%': { boxShadow: '0 0 0 0 rgba(14,165,230,0.4)' }, '50%': { boxShadow: '0 0 0 10px rgba(14,165,230,0)' } }
                    },
                    animation: { fadeInUp: 'fadeInUp 0.6s ease-out forwards', pulseShadow: 'pulseShadow 2s infinite' }
                }
            }
        }
    </script>
    <style>
        .hero-section {
            background-image: url('assets/img/hero/servicespage_hero.jpg');
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .feedback-form {
            background: linear-gradient(145deg, var(--form-bg-light), var(--form-bg-dark));
            border: 1px solid var(--form-border);
        }
        .dark .feedback-form {
            --form-bg-light: #1f2937;
            --form-bg-dark: #111827;
            --form-border: #374151;
        }
        .feedback-form {
            --form-bg-light: #ffffff;
            --form-bg-dark: #f9fafb;
            --form-border: #e5e7eb;
        }
        .star-rating {
            direction: rtl;
            display: flex;
            justify-content: center;
            unicode-bidi: bidi-override;
            font-size: 0
        }
        .star-rating input { display: none }
        .star-rating label.star {
            font-size: 2.5rem;
            color: #cbd5e1;
            cursor: pointer;
            transition: color .3s, transform .2s;
            line-height: 1;
            margin: 0 .25rem
        }
        .star-rating label.star:hover,
        .star-rating label.star:hover~label.star,
        .star-rating input:checked~label.star {
            color: #fbbf24;
            text-shadow: 0 0 5px rgba(251, 191, 36, .6)
        }
        .star-rating label.star:hover { transform: scale(1.1) }
        .star-rating .star { cursor: pointer; font-size: 2rem; color: #d1d5db; transition: color 0.2s; }
        .star-rating input[type="radio"] { display: none; }
        .star-rating input[type="radio"]:checked~label { color: #facc15; }

        /* Modal transitions */
        #messageBox { transition: opacity 0.3s ease, transform 0.3s ease; }
        #messageBox.show { opacity: 1 !important; transform: scale(1) !important; display:flex !important; }
        #messageBox.hidden-box { opacity: 0 !important; transform: scale(0.9) !important; display:none !important; }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-500 font-sans">

<?php
if ($user_logged_in) {
    include('common/userheader.php');
} else {
    include('common/header.php');
}
?>

<main>
    <section class="hero-section relative h-[300px] flex items-center justify-center text-center shadow-inner-xl">
        <div class="absolute inset-0 bg-primary-800/60 backdrop-blur-[1px]"></div>
        <div class="relative z-10 text-white animate-fadeInUp">
            <h1 class="text-5xl md:text-6xl font-extrabold text-white tracking-tight mb-2">Guest Feedback</h1>
            <p class="text-lg font-light text-black opacity-90">Your Voice, Our Improvement</p>
        </div>
    </section>

    <section class="py-20 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="feedback-form p-10 rounded-3xl shadow-2xl border border-primary-200 dark:border-primary-900">

                <div class="text-center mb-10 border-b border-primary-100 dark:border-primary-800 pb-5">
                    <h2 class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-2">We Value Your Feedback ✨</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-lg">Help us refine your next stay at Our Resort.</p>
                </div>

                <form id="feedbackForm" action="" method="POST" class="space-y-6">
                    <div class="p-6 bg-primary-50 dark:bg-gray-700 rounded-xl shadow-inner">
                        <h3 class="text-2xl font-semibold mb-4 text-primary-700 dark:text-primary-300 border-b pb-2 border-primary-200 dark:border-gray-600">
                            <i class="fas fa-user-circle mr-2"></i>Guest Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group col-span-1 md:col-span-2">
                                <label for="fullName" class="block mb-2 font-medium text-gray-700 dark:text-gray-200">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" id="fullName" name="fullName" required
                                    value="<?= htmlspecialchars($prefillFullName) ?>"
                                    class="w-full border-gray-300 dark:border-gray-600 rounded-lg p-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition duration-200 hover:border-primary-400">
                            </div>
                            <div class="form-group">
                                <label for="email" class="block mb-2 font-medium text-gray-700 dark:text-gray-200">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" required
                                    value="<?= htmlspecialchars($prefillEmail) ?>"
                                    class="w-full border-gray-300 dark:border-gray-600 rounded-lg p-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition duration-200 hover:border-primary-400">
                            </div>
                            <div class="form-group">
                                <label for="phone" class="block mb-2 font-medium text-gray-700 dark:text-gray-200">Phone Number <span class="text-red-500">*</span></label>
                                <input type="tel" id="phone" name="phone" required maxlength="10" pattern="[0-9]{10}"
                                    placeholder="10-digit number only"
                                    value="<?= htmlspecialchars($prefillPhone) ?>"
                                    class="w-full border-gray-300 dark:border-gray-600 rounded-lg p-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition duration-200 hover:border-primary-400">
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-xl shadow-inner">
                        <h3 class="text-2xl font-semibold mb-4 text-primary-700 dark:text-primary-300 border-b pb-2 border-primary-200 dark:border-gray-600">
                            <i class="fas fa-comment-dots mr-2"></i>Your Experience
                        </h3>
                        <div class="form-group">
                            <label for="customerFeedback" class="block mb-2 font-medium text-gray-700 dark:text-gray-200">Please share your experience <span class="text-red-500">*</span></label>
                            <textarea id="customerFeedback" name="customerFeedback" rows="6" required
                                placeholder="Write your feedback and suggestions here..."
                                class="w-full border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition duration-200 hover:border-primary-400"></textarea>
                        </div>
                    </div>

                    <div class="p-6 bg-primary-50 dark:bg-gray-700 rounded-xl shadow-inner">
                        <h3 class="text-2xl font-semibold mb-6 text-primary-700 dark:text-primary-300 border-b pb-2 border-primary-200 dark:border-gray-600">
                            <i class="fas fa-star-half-alt mr-2"></i>Overall Rating
                        </h3>
                        <div class="text-center">
                            <label class="block mb-4 text-xl font-medium text-gray-700 dark:text-gray-200">
                                How would you rate your overall experience? <span class="text-red-500">*</span>
                            </label>
                            <div class="rating-container flex flex-col items-center">
                                <div class="star-rating mb-2 flex flex-row">
                                    <input type="radio" id="star1" name="overallRating" value="1" required>
                                    <label for="star1" class="star">★</label>
                                    <input type="radio" id="star2" name="overallRating" value="2">
                                    <label for="star2" class="star">★</label>
                                    <input type="radio" id="star3" name="overallRating" value="3">
                                    <label for="star3" class="star">★</label>
                                    <input type="radio" id="star4" name="overallRating" value="4">
                                    <label for="star4" class="star">★</label>
                                    <input type="radio" id="star5" name="overallRating" value="5">
                                    <label for="star5" class="star">★</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center space-x-6 mt-10">
                        <button type="submit"
                            class="bg-primary-600 text-white px-10 py-3 rounded-full font-bold text-lg hover:bg-primary-700 transition duration-300 ease-in-out shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-800 animate-pulseShadow">
                            <i class="fas fa-paper-plane mr-2"></i> Submit Feedback
                        </button>
                        <button type="reset"
                            class="bg-gray-300 text-gray-800 px-10 py-3 rounded-full font-semibold text-lg hover:bg-gray-400 transition duration-300 ease-in-out shadow-md dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">
                            <i class="fas fa-eraser mr-2"></i> Clear Form
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </section>
</main>

<footer class="bg-gray-800 dark:bg-gray-950 py-8 text-center mt-10 shadow-inner">
    <p class="text-gray-400 dark:text-gray-500 text-sm">&copy; 2025 HG Resort. All rights reserved.</p>
</footer>

<!-- Popup Modal -->
<div id="messageBox" class="hidden-box fixed inset-0 flex items-center justify-center bg-black/50 z-50 opacity-0 transform scale-90">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 max-w-md w-full text-center">
        <i id="messageIcon" class="fas fa-info-circle text-5xl text-primary-600 mb-4"></i>
        <h3 id="messageTitle" class="text-2xl font-bold mb-4"></h3>
        <p id="messageText" class="text-gray-700 dark:text-gray-300 mb-6"></p>
        <button id="actionButton" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-full transition-colors duration-300"></button>
    </div>
</div>

<?php include('common/script.php'); ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
    $('#feedbackForm').submit(function(e) {
        e.preventDefault();

        <?php if(!$user_logged_in): ?>
            // Not logged in → show login popup
            $('#messageIcon').attr('class','fas fa-lock text-5xl text-red-500 mb-4');
            $('#messageTitle').text('Login Required');
            $('#messageText').text('Please login first to submit feedback.');
            $('#actionButton').text('Go to Login');

            $('#messageBox').removeClass('hidden-box').addClass('show');

            $('#actionButton').off('click').on('click', function () {
                window.location.href = 'login.php';
            });
            return;
        <?php else: ?>
            // Logged in → submit form via normal POST
            this.submit();
        <?php endif; ?>
    });
</script>

</body>
</html>

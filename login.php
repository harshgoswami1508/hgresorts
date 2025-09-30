<?php
session_start();
include('common/con.php'); // DB connection file

// For showing toast message
if (isset($_SESSION['toast'])) {
    $toast = $_SESSION['toast'];
    unset($_SESSION['toast']);
} else {
    $toast = '';
}

// Default message for this page
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    if (empty($email) || empty($password)) {
        $message = '<div class="bg-red-100 text-red-700 p-3 rounded mb-4">All fields are required.</div>';
    } else {
        $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                // Valid user
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];

                header('Location: dashboard.php');
                exit;
            } else {
                // Wrong password
                $message = '<div class="bg-red-100 text-red-700 p-3 rounded mb-4">Invalid credentials. Please try again.</div>';
            }
        } else {
            // No account found
            $message = '<div class="bg-red-100 text-red-700 p-3 rounded mb-4">No account found with this email. <a href="signup.php" class="underline text-primary-600 hover:text-primary-700">Sign up here</a>.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HG Resort - Login</title>
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
              50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd', 300: '#7dd3fc',
              400: '#38bdf8', 500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1',
              800: '#075985', 900: '#0c4a6e',
            }
          }
        }
      }
    }
  </script>
  <style>
    .hero-section {
      background-attachment: fixed;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
    }
    .form-input:focus {
      border-color: #0284c7;
      box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.3);
    }
  </style>
  <?php include('common/style.php'); ?>
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
<?php include('common/header.php'); ?>

<main>
  <section class="hero-section relative h-60 flex items-center justify-center text-center"
      style="background-image: url('assets/img/image/gallery/3.jpg');">
      <div class="absolute inset-0 bg-black/40"></div>
      <div class="relative z-10 text-white">
          <h1 class="text-4xl md:text-5xl text-white font-bold mb-2">Login</h1>
          <p class="text-lg md:text-xl text-black">Access your HG Resort account</p>
      </div>
  </section>

  <section class="py-16 bg-white dark:bg-gray-900">
      <div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 sm:p-10">
          <div class="text-center mb-6">
              <i class="fas fa-umbrella-beach text-2xl text-primary-600 mr-2"></i>
              <h2 class="mt-3 text-2xl font-bold text-gray-900 dark:text-white">Welcome Back</h2>
              <p class="text-gray-600 dark:text-gray-300 text-sm">Log in to your account</p>
          </div>

          <!-- Show toast if any -->
          <?php if (!empty($toast)): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?php echo $toast; ?></div>
          <?php endif; ?>

          <!-- Show error message -->
          <?php if (!empty($message)) echo $message; ?>

          <form method="post" class="space-y-6">
              <div>
                  <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email Address</label>
                  <input id="email" name="email" type="email" required
                      class="form-input w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white">
              </div>

              <div>
                  <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Password</label>
                  <input id="password" name="password" type="password" required
                      class="form-input w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white">
              </div>

              <button type="submit"
                  class="w-full py-3 px-4 rounded-lg shadow-md text-base font-medium text-white bg-primary-600 hover:bg-primary-700 transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-600">
                  Log In
              </button>
          </form>

          <div class="mt-6 text-center">
              <p class="text-sm text-gray-600 dark:text-gray-300">
                  Don’t have an account?
                  <a href="signup.php" class="font-medium text-primary-600 hover:text-primary-700 dark:hover:text-primary-400">
                      Sign up here
                  </a>
              </p>
          </div>
      </div>
  </section>
</main>

<footer class="bg-gray-100 dark:bg-gray-800 py-8 text-center">
  <p class="text-gray-700 dark:text-gray-300">&copy; 2025 HG Resort. All rights reserved.</p>
</footer>

<?php include('common/script.php'); ?>
</body>
</html>

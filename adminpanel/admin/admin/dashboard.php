<?php 
session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['adminid']==0)) {
  header('location:logout.php');
} else{
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="HG Resorts Admin Dashboard" />
    <meta name="author" content="" />
    <title>HG Resort | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>

    <style>
        /* Dashboard Styling */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
        }

        h1 {
            font-weight: 700;
            color: #1f2937;
        }

        .breadcrumb {
            background: transparent;
            margin-bottom: 1rem;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            transition: all 0.25s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-body {
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-body span {
            font-size: 1.6rem;
            font-weight: bold;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: #fff;
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
        }

        .card-footer {
            background: rgba(255, 255, 255, 0.1);
            font-weight: 500;
        }

        .card-footer a {
            color: #fff;
            text-decoration: none;
        }

        .card-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <?php include_once('includes/navbar.php'); ?>
    <div id="layoutSidenav">
        <?php include_once('includes/sidebar.php'); ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                        <h1 class="mb-0">Dashboard</h1>
                    </div>

                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Overview</li>
                    </ol>

                    <div class="row g-4">
                        <?php
                        $query=mysqli_query($con,"select id from users");
                        $totalusers=mysqli_num_rows($query);
                        ?>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-gradient-primary text-white mb-4">
                                <div class="card-body">
                                    <span><i class="fas fa-users me-2"></i>Total Users</span>
                                    <span><?php echo $totalusers;?></span>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="stretched-link text-white" href="manage-users.php">View Details</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <?php
                        $query=mysqli_query($con,"select id from bookings");
                        $totalbook=mysqli_num_rows($query);
                        ?>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-gradient-success text-white mb-4">
                                <div class="card-body">
                                    <span><i class="fas fa-calendar-check me-2"></i>Bookings</span>
                                    <span><?php echo $totalbook;?></span>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="stretched-link text-white" href="booking.php">View Details</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- You can add more cards here (e.g., Feedbacks, Rooms, Revenue) -->
                    </div>
                </div>
            </main>
            <?php include_once('../includes/footer.php'); ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
</body>

</html>
<?php } ?>

<?php
session_start();
include_once('../includes/config.php');

if (strlen($_SESSION['adminid']) == 0) {
    header('location:logout.php');
    exit();
}

// Database Connection Check
if (!isset($con) || mysqli_connect_errno()) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Admin Panel for HG Resort Bookings" />
    <meta name="author" content="" />
    <title>HG Resort | Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .table-container {
            overflow-x: auto;
        }
        #bookings-table {
            width: 100% !important;
        }
        /* Hover effect for rows */
        #bookings-table tbody tr:hover {
            background-color: #f3f4f6; /* light gray */
        }
        /* Room image styling */
        .room-img {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .room-img:hover {
            transform: scale(2);
            z-index: 10;
            position: relative;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
        }
        /* Optional tooltip */
        .room-img:hover::after {
            content: attr(data-name);
            position: absolute;
            top: -2rem;
            left: 50%;
            transform: translateX(-50%);
            background: #111;
            color: #fff;
            padding: 2px 6px;
            font-size: 12px;
            border-radius: 4px;
            white-space: nowrap;
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
                    <h1 class="mt-4 text-3xl font-bold">Booking Records</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">View All Bookings</li>
                    </ol>
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card shadow-lg mb-4">
                                        <div class="card-header bg-primary text-white p-3 rounded-t-xl">
                                            <h3 class="card-title text-xl font-semibold">Booking Details</h3>
                                        </div>
                                        <div class="card-body table-container p-4 bg-white rounded-b-xl">
                                            <table id="bookings-table" class="min-w-full divide-y divide-gray-200 table-fixed border-collapse">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-2 py-3 w-10 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                                                        <th class="px-2 py-3 w-32 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Full Name</th>
                                                        <th class="px-2 py-3 w-40 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Email</th>
                                                        <th class="px-2 py-3 w-20 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Check-In</th>
                                                        <th class="px-2 py-3 w-20 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Check-Out</th>
                                                        <th class="px-2 py-3 w-24 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Room Name</th>
                                                        <th class="px-2 py-3 w-20 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Image</th>
                                                        <th class="px-2 py-3 w-20 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Total</th>
                                                        <th class="px-2 py-3 w-24 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Booked At</th>
                                                        <th class="px-2 py-3 w-12 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <?php
                                                    $query = "SELECT b.id, b.full_name, b.email, b.check_in, b.check_out, 
                                                                     b.final_total, b.created_at, 
                                                                     r.room_nm AS room_name, r.room_photo
                                                              FROM bookings b
                                                              LEFT JOIN rooms_details r ON b.room_name = r.room_nm
                                                              ORDER BY b.id ASC";
                                                    $query_run = mysqli_query($con, $query);

                                                    if (mysqli_num_rows($query_run) > 0) {
                                                        while ($row = mysqli_fetch_assoc($query_run)) {
                                                            $roomImage = !empty($row['room_photo']) ? htmlspecialchars($row['room_photo']) : '../../assets/img/default-room.jpg';
                                                            ?>
                                                            <tr class="hover:bg-gray-50 text-xs">
                                                                <td class="px-2 py-3 font-mono"><?= $row['id'] ?></td>
                                                                <td class="px-2 py-3 font-medium"><?= htmlspecialchars($row['full_name']) ?></td>
                                                                <td class="px-2 py-3 truncate"><?= htmlspecialchars($row['email']) ?></td>
                                                                <td class="px-2 py-3"><?= date('M j', strtotime($row['check_in'])) ?></td>
                                                                <td class="px-2 py-3"><?= date('M j', strtotime($row['check_out'])) ?></td>
                                                                <td class="px-2 py-3 font-semibold"><?= htmlspecialchars($row['room_name']) ?></td>
                                                                <td class="px-2 py-3 text-center">
                                                                    <img src="<?= $roomImage ?>" data-name="<?= htmlspecialchars($row['room_name']) ?>" 
                                                                         alt="<?= htmlspecialchars($row['room_name']) ?> Image" 
                                                                         class="room-img mx-auto">
                                                                </td>
                                                                <td class="px-2 py-3 font-bold text-green-700">₹ <?= number_format($row['final_total'], 2) ?></td>
                                                                <td class="px-2 py-3"><?= date('M j, Y', strtotime($row['created_at'])) ?></td>
                                                                <td class="px-2 py-3 text-center">
                                                                    <a href="booking-details.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:text-blue-800 font-medium text-sm">View</a>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        echo '<tr><td colspan="10" class="px-4 py-3 text-center text-gray-500">No bookings found.</td></tr>';
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
            <?php include_once('../includes/footer.php'); ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', event => {
            const bookingsTable = document.getElementById('bookings-table');
            if (bookingsTable) {
                new simpleDatatables.DataTable(bookingsTable, {
                    searchable: true,
                    perPageSelect: [10, 25, 50, 100],
                    sortable: true
                });
            }
        });
    </script>
</body>
</html>

<?php 
if (isset($con)) {
    mysqli_close($con);
}
?>

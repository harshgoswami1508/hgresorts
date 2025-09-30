<?php 
session_start();
include_once('../includes/config.php');

if (strlen($_SESSION['adminid']) == 0) {
    header('location:logout.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>HG Resort | Admin Panel - Users</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <?php include_once('includes/navbar.php'); ?>
    <div id="layoutSidenav">
        <?php include_once('includes/sidebar.php'); ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 mt-4">
                    <h1 class="mt-4">Registered Users</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">All Users</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-users me-1"></i>
                            User Details
                        </div>
                        <div class="card-body">
                            <table id="usersTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Registered At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM users ORDER BY created_at ASC";
                                    $query_run = mysqli_query($con, $query);
                                    $count = 1;

                                    if (mysqli_num_rows($query_run) > 0) {
                                        while ($row = mysqli_fetch_assoc($query_run)) {
                                            // Check if created_at is valid
                                            if (!empty($row['created_at']) && $row['created_at'] != '0000-00-00 00:00:00') {
                                                $datetime = new DateTime($row['created_at'], new DateTimeZone('UTC')); // Adjust if DB is UTC
                                                $datetime->setTimezone(new DateTimeZone('Asia/Kolkata')); // Change to your timezone
                                                $registered_at = $datetime->format('d-m-Y h:i A');
                                            } else {
                                                $registered_at = "-";
                                            }
                                            ?>
                                            <tr>
                                                <td><?= $count++; ?></td>
                                                <td><?= htmlspecialchars($row['full_name']); ?></td>
                                                <td><?= htmlspecialchars($row['email']); ?></td>
                                                <td><?= !empty($row['phone']) ? htmlspecialchars($row['phone']) : "-"; ?></td>
                                                <td><?= $registered_at; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="5" class="text-center">No users found.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </main>
            <?php include_once('../includes/footer.php'); ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script>
        // Initialize DataTable
        document.addEventListener("DOMContentLoaded", () => {
            new simpleDatatables.DataTable("#usersTable", {
                searchable: true,
                perPage: 10,
                perPageSelect: [5, 10, 25, 50],
            });
        });
    </script>
</body>
</html>

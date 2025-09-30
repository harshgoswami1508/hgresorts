<?php
session_start();
include_once('../includes/config.php');

if (strlen($_SESSION['adminid']) == 0) {
    header('location:logout.php');
    exit();
}

// Optional: delete user record
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $msg = mysqli_query($con, "DELETE FROM users WHERE id='$userId'");
    if ($msg) {
        echo "<script>alert('User record deleted');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Users | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Table hover effect */
        #users-table tbody tr:hover {
            background-color: #f3f4f6;
        }

        /* Overflow for mobile */
        .table-container {
            overflow-x: auto;
        }
    </style>
</head>

<body class="sb-nav-fixed bg-gray-50">
    <?php include_once('includes/navbar.php'); ?>
    <div id="layoutSidenav">
        <?php include_once('includes/sidebar.php'); ?>
        <div id="layoutSidenav_content">
            <main class="p-6">
                <div class="container mx-auto">
                    <h1 class="text-3xl font-bold mb-4">Manage Users</h1>
                    <ol class="breadcrumb mb-6">
                        <li class="breadcrumb-item"><a href="dashboard.php" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>

                    <div class="card shadow-lg bg-white rounded-xl p-4">
                        <div class="mb-4">
                            <h2 class="text-xl font-semibold">Registered Users</h2>
                        </div>

                        <div class="table-container">
                            <table id="users-table" class="min-w-full divide-y divide-gray-200 table-auto border-collapse">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 uppercase">S.No</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 uppercase">Full Name</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 uppercase">Email</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 uppercase">Phone Number</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 uppercase">Registration Date</th>
                                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-700 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    $ret = mysqli_query($con, "SELECT id, full_name, email, phone, created_at FROM users ORDER BY id ASC");
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($ret)) { ?>
                                        <tr class="hover:bg-gray-50 text-sm">
                                            <td class="px-4 py-2"><?= $cnt ?></td>
                                            <td class="px-4 py-2"><?= htmlspecialchars($row['full_name']) ?></td>
                                            <td class="px-4 py-2"><?= htmlspecialchars($row['email']) ?></td>
                                            <td class="px-4 py-2"><?= !empty($row['phone']) ? htmlspecialchars($row['phone']) : '-' ?></td>
                                            <td class="px-4 py-2"><?= date('M j, Y h:i A', strtotime($row['created_at'])) ?></td>
                                            <td class="px-4 py-2 text-center">
                                                <a href="users.php?id=<?= $row['id'] ?>" class="text-red-600 hover:text-red-800 font-medium text-sm"
                                                   onclick="return confirm('Are you sure you want to delete this user?');">
                                                   Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php $cnt++; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <?php include('../includes/footer.php'); ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', event => {
            const usersTable = document.getElementById('users-table');
            if (usersTable) {
                new simpleDatatables.DataTable(usersTable, {
                    searchable: true,
                    perPageSelect: [10, 25, 50, 100],
                    sortable: true
                });
            }
        });
    </script>
</body>
</html>

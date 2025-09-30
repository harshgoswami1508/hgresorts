<?php 
session_start();
include_once('../includes/config.php'); // Make sure $con is your DB connection
if (strlen($_SESSION['adminid']) == 0) {
    header('location:logout.php');
    exit();
}

// Set the correct timezone (change as needed, e.g., 'Asia/Kolkata')
date_default_timezone_set('Asia/Kolkata');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<title>Admin Panel | Contact Messages</title>

<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
<link href="../css/styles.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>

<style>
    /* Custom Table Styling */
    #contactTable {
        border-radius: 10px;
        overflow: hidden;
    }
    #contactTable thead {
        background: #1e3a8a; /* Indigo */
        color: white;
    }
    #contactTable tbody tr:hover {
        background: #f3f4f6; /* Gray hover */
    }
    .no-message {
        text-align: center;
        font-style: italic;
        color: #6b7280;
    }
</style>
</head>

<body class="sb-nav-fixed bg-gray-50">
<?php include_once('includes/navbar.php'); ?>
<div id="layoutSidenav">
    <?php include_once('includes/sidebar.php'); ?>
    <div id="layoutSidenav_content">
        <main class="p-4">
            <div class="container-fluid">
                <h1 class="mt-4 text-2xl font-bold">Contact Messages</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">All Contact Messages</li>
                </ol>

                <div class="card shadow-lg">
                    <div class="card-header bg-indigo-600 text-white font-semibold">
                        <i class="fas fa-envelope me-1 text-black"></i>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="contactTable" class="table table-bordered table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Message</th>
                                        <th>Submitted On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM contact_request ORDER BY created_at ASC";
                                    $result = mysqli_query($con, $query);
                                    $count = 1;
                                    if(mysqli_num_rows($result) > 0) {
                                        while($row = mysqli_fetch_assoc($result)) {

                                            // Safely format the created_at timestamp
                                            $createdAt = !empty($row['created_at']) ? 
                                                (new DateTime($row['created_at']))->format('d-m-Y h:i A') 
                                                : '-';

                                            echo "<tr>
                                                    <td>".$count++."</td>
                                                    <td>".htmlspecialchars($row['name'])."</td>
                                                    <td>".htmlspecialchars($row['email'])."</td>
                                                    <td>".(!empty($row['phone']) ? htmlspecialchars($row['phone']) : "-")."</td>
                                                    <td>".(!empty($row['message']) ? htmlspecialchars($row['message']) : "<span class='no-message'>No message</span>")."</td>
                                                    <td>".$createdAt."</td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='no-message'>No contact messages found.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main>
        <?php include_once('../includes/footer.php'); ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../js/scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script>
    // Initialize datatable
    const dataTable = new simpleDatatables.DataTable("#contactTable", {
        searchable: true,
        perPageSelect: [5, 10, 25, 50],
        perPage: 10,
    });
</script>
</body>
</html>

<?php
session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['adminid']) == 0) {
    header('location:logout.php');
} else {

    // Handle room deletion
    if (isset($_GET['val']) && $_GET['val'] == "del" && isset($_GET['id'])) {
        $roomID = $_GET['id'];
        $deleteQuery = "DELETE FROM rooms_details WHERE ID='$roomID'";
        mysqli_query($con, $deleteQuery);
        $success = "Room deleted successfully!";
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Rooms | Admin Panel</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"
            crossorigin="anonymous"></script>
    </head>

    <body class="sb-nav-fixed">

        <?php include_once('includes/navbar.php'); ?>

        <div id="layoutSidenav">
            <?php include_once('includes/sidebar.php'); ?>

            <div id="layoutSidenav_content">
                <main class="p-6">
                    <div class="container-fluid">
                        <h1 class="text-2xl font-bold mb-4">Rooms Management</h1>

                        <?php if (isset($success)): ?>
                            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl">
                                <?= $success ?>
                            </div>
                        <?php endif; ?>


                        <div class="overflow-x-auto bg-white shadow rounded-xl p-4">
                            <table id="rooms-table" class="min-w-full divide-y divide-gray-200 table-auto">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ID</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Room Name</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Price</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Photo</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    $query = "SELECT * FROM rooms_details";
                                    $result = mysqli_query($con, $query);

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $shortDesc = strlen($row['descc']) > 50 ? substr($row['descc'], 0, 50) . '...' : $row['room_description'];
                                            ?>
                                            <tr>
                                                <td class="px-4 py-2"><?= $row['ID'] ?></td>
                                                <td class="px-4 py-2"><?= $row['room_nm'] ?></td>
                                                <td class="px-4 py-2"><?= $row['room_price'] ?></td>
                                                <td class="px-4 py-2"><?= $shortDesc ?></td>
                                                <td class="px-4 py-2">
                                                    <img src="<?= $row['room_photo'] ?>" alt="Room Photo"
                                                        class="w-36 h-36 object-cover rounded-lg border border-gray-200">
                                                </td>
                                                <td class="px-4 py-2 space-x-2">
                                                    <a href="edit-room.php?id=<?= $row['ID'] ?>"
                                                        class="text-blue-600 hover:underline">Edit <i class="fas fa-edit"></i></a>
                                                    <a href="room.php?id=<?= $row['ID'] ?>&val=del"
                                                        onclick="return confirm('Are you sure you want to delete this room?')"
                                                        class="text-red-600 hover:underline">Delete <i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="6" class="px-4 py-2 text-center text-gray-500">No rooms found.</td></tr>';
                                    }
                                    ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </main>

                <?php include_once('../includes/footer.php'); ?>
            </div>
        </div>

        <script src="../plugins/jquery/jquery.min.js"></script>
        <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
        <script src="../dist/js/adminlte.min.js"></script>
        <script src="../dist/js/demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>

        <script>
            // Initialize datatable
            const dataTable = new simpleDatatables.DataTable("#bookings-table");
        </script>
    </body>

    </html>

<?php } ?>
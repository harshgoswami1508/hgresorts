<?php
session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['adminid']==0)) {
    header('location:logout.php');
} else{

if (isset($_POST['submit'])) {
    $room_nm = mysqli_real_escape_string($con, $_POST['room_nm']);
    $room_price = mysqli_real_escape_string($con, $_POST['room_price']);
    $descc = mysqli_real_escape_string($con, $_POST['descc']); // Escape special chars

    // File upload
    $targetDirectory = "uploads/";
    $originalFileName = $_FILES["room_photo"]["name"];
    $targetFile = $targetDirectory . $originalFileName;

    if (move_uploaded_file($_FILES["room_photo"]["tmp_name"], $targetFile)) {
        $sql = "INSERT INTO rooms_details 
            (room_nm, room_price, descc, room_photo_original_name, room_photo) 
            VALUES ('$room_nm', '$room_price', '$descc', '$originalFileName', '$targetFile')";

        if (mysqli_query($con, $sql)) {
            $success = "Room added successfully!";
        } else {
            $error = "Database error: " . mysqli_error($con);
        }
    } else {
        $error = "Error uploading file.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>HG Resort | Add Room</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <div class="container-fluid px-4 py-6">
                    <h1 class="text-2xl font-bold mb-4">Add New Room</h1>

                    <?php if(isset($success)): ?>
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl">
                            <?= $success ?>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($error)): ?>
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <div class="max-w-3xl bg-white rounded-3xl shadow-2xl p-8">
                        <form method="POST" enctype="multipart/form-data" class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Room Name</label>
                                <input type="text" name="room_nm" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Room Price ($)</label>
                                <input type="number" name="room_price" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Room Description</label>
                                <textarea name="descc" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Room Photo</label>
                                <input type="file" name="room_photo" required class="mt-1 block w-full text-gray-700">
                            </div>

                            <button type="submit" name="submit" class="w-full py-3 px-4 rounded-full bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors">
                                Add Room
                            </button>
                        </form>
                    </div>
                </div>
            </main>

            <?php include_once('../includes/footer.php'); ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>

</body>
</html>

<?php } ?>

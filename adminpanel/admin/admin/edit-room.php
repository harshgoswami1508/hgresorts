<?php
session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['adminid']==0)) {
    header('location:logout.php');
} else {

if (isset($_POST['submit'])) {
    $id = mysqli_real_escape_string($con, $_POST['ID']);
    $room_nm = mysqli_real_escape_string($con, $_POST['room_nm']);
    $room_price = mysqli_real_escape_string($con, $_POST['room_price']);
    $descc = mysqli_real_escape_string($con, $_POST['descc']);

    // File upload
    if (!empty($_FILES['room_photo']['name'])) {
        $targetDirectory = "uploads/";
        $originalFileName = $_FILES["room_photo"]["name"];
        $targetFile = $targetDirectory . $originalFileName;

        if (move_uploaded_file($_FILES["room_photo"]["tmp_name"], $targetFile)) {
            $sql = "UPDATE rooms_details SET room_nm='$room_nm', room_price='$room_price', descc='$descc', 
                    room_photo_original_name='$originalFileName', room_photo='$targetFile' WHERE ID='$id'";
        } else {
            $error = "Error uploading file.";
        }
    } else {
        $sql = "UPDATE rooms_details SET room_nm='$room_nm', room_price='$room_price', descc='$descc' WHERE ID='$id'";
    }

    if (isset($sql) && mysqli_query($con, $sql)) {
        $success = "Room updated successfully!";
    } elseif (!isset($error)) {
        $error = "Database error: " . mysqli_error($con);
    }
}

// Fetch room details for editing
$id = $_GET['id'] ?? '';
$row = [];
if (!empty($id)) {
    $qry = "SELECT * FROM rooms_details WHERE ID='$id'";
    $res = mysqli_query($con, $qry);
    $row = mysqli_fetch_assoc($res);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Edit Room | HG Resort Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed bg-gray-100">

<?php include_once('includes/navbar.php'); ?>
<div id="layoutSidenav">
    <?php include_once('includes/sidebar.php'); ?>

    <div id="layoutSidenav_content">
        <main class="py-6">
            <div class="container mx-auto px-4">
                <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Room Details</h1>

                <?php if(isset($success)): ?>
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl"><?= $success ?></div>
                <?php endif; ?>

                <?php if(isset($error)): ?>
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl"><?= $error ?></div>
                <?php endif; ?>

                <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-3xl mx-auto">
                    <form method="POST" enctype="multipart/form-data" class="space-y-6">
                        <input type="hidden" name="ID" value="<?= $row['ID'] ?? '' ?>">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room Name</label>
                            <input type="text" name="room_nm" required value="<?= $row['room_nm'] ?? '' ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room Price</label>
                            <input type="number" name="room_price" required value="<?= $row['room_price'] ?? '' ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room Description</label>
                            <textarea name="descc" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500"><?= $row['descc'] ?? '' ?></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room Photo</label>
                            <input type="file" name="room_photo" class="mt-1 block w-full text-gray-700">
                            <?php if(!empty($row['room_photo'])): ?>
                                <img src="<?= $row['room_photo'] ?>" alt="Room Image" class="mt-4 rounded-lg shadow-md w-48 h-48 object-cover">
                            <?php endif; ?>
                        </div>

                        <button type="submit" name="submit" class="w-full py-3 px-4 rounded-full bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors">
                            Update Room
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

</body>
</html>

<?php } ?>

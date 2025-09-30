<?php
include_once('../includes/config.php');

if ($_REQUEST['val'] == 'del' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $qry = "DELETE FROM rooms_details WHERE Id='" . $id . "'";
    $res = mysqli_query($con, $qry);

    if ($res) {
        // The deletion was successful, you can redirect or display a success message here.
        header("location:room.php");
    } else {
        // Handle the case where the deletion fails.
        echo "Error: " . mysqli_error($con);
    }
}
?>
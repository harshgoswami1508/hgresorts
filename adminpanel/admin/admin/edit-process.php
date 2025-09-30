   <?php
    include_once('../includes/config.php');

    if (isset($_POST['submit']) && $_POST['submit'] == "add") 
    {
        extract($_POST);
        $filename = $_FILES['room_photo']['name'];
        $newname = time() . '-' . $filename;
        $path = 'uploads/' . $newname;
        if (move_uploaded_file($_FILES['room_photo']['tmp_name'], $path)) 
        {
            $catqry = "INSERT INTO room_details (room_nm,room_photo) VALUES ('".$room_nm."','".$newname."')";
            mysqli_query($con,$catqry);
            header("location:room.php");
        }
    }
    ?>
    
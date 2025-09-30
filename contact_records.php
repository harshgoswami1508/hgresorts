<?php
include('common/con.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $message = trim($_POST['message']);

    if($name && $email && $message){
        $stmt = mysqli_prepare($con, "INSERT INTO contact_request (name, email, phone, message) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $phone, $message);
        if(mysqli_stmt_execute($stmt)){
            echo 'success';
        } else {
            echo 'Database error';
        }
        mysqli_stmt_close($stmt);
    } else {
        echo 'Please fill all required fields';
    }
} else {
    echo 'Invalid request';
}
?>

<?php 
session_start(); 
include_once('../includes/config.php');

// Code for login 
if(isset($_POST['login']))
{
    $adminusername=$_POST['username'];
    $pass=md5($_POST['password']);

    $ret=mysqli_query($con,"SELECT * FROM admin WHERE username='$adminusername' and password='$pass'");
    $num=mysqli_fetch_array($ret);
    
    if($num>0)
    {
        $extra="dashboard.php";
        $_SESSION['login']=$_POST['username'];
        $_SESSION['adminid']=$num['id'];
        echo "<script>window.location.href='".$extra."'</script>";
        exit();
    }
    else
    {
        echo "<script>alert('Invalid username or password');</script>";
        $extra="index.php";
        echo "<script>window.location.href='".$extra."'</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="HG Resorts Admin Login Panel" />
    <meta name="author" content="" />
    <title>HG Resorts | Admin Login</title>

    <link href="../css/styles.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>

    <style>
        body {
            background: linear-gradient(135deg, #1f2937, #111827);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 1rem;
            background-color: #2a3440;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
            width: 100%;
            max-width: 420px;
        }
        .card-header {
            text-align: center;
            padding: 1.5rem 1rem 0.5rem;
        }
        .card-header h2 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 800;
            color: #4ade80;
        }
        .card-header h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #ddd;
            margin-top: 0.5rem;
        }
        hr {
            border-color: rgba(255,255,255,0.15);
            margin: 0.5rem 0 1rem;
        }
        .card-body {
            padding: 1.5rem;
        }
        .form-floating label {
            color: rgba(255,255,255,0.7);
        }
        .form-control {
            background: #374151;
            border: 1px solid #4b5563;
            border-radius: 0.5rem;
            color: #fff;
        }
        .form-control:focus {
            border-color: #4ade80;
            box-shadow: 0 0 0 0.25rem rgba(74, 222, 128, 0.25);
            background: #374151;
            color: #fff;
        }
        .btn-primary {
            background: #4ade80;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            padding: 0.65rem 1rem;
            width: 100%;
        }
        .btn-primary:hover {
            background: #22c55e;
        }
        .extra-links {
            margin-top: 1rem;
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
        }
        .extra-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
        }
        .extra-links a:hover {
            color: #4ade80;
        }
        .card-footer {
            text-align: center;
            padding: 1rem;
            background: #232b36;
            font-size: 0.9rem;
        }
        .card-footer a {
            color: #aaa;
            text-decoration: none;
        }
        .card-footer a:hover {
            color: #4ade80;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-header">
            <h2>HG Resorts</h2>
            <hr />
            <h3>Admin Login</h3>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="form-floating mb-3">
                    <input class="form-control" id="inputUsername" name="username" type="text" placeholder="Username" required />
                    <label for="inputUsername"><i class="fas fa-user-shield me-2"></i>Username</label>
                </div>

                <div class="form-floating mb-3">
                    <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Password" required />
                    <label for="inputPassword"><i class="fas fa-lock me-2"></i>Password</label>
                </div>

                <button class="btn btn-primary mt-2" name="login" type="submit">
                    <i class="fas fa-sign-in-alt me-1"></i> Login
                </button>

                <div class="extra-links">
                    <a href="../index.php"><i class="fas fa-arrow-left me-1"></i>Back to Home</a>
                </div>
            </form>
        </div>
        <div class="card-footer">
            &copy; <?php echo date('Y'); ?> HG Resorts Admin Side
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
</body>
</html>

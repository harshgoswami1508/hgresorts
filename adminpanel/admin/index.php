<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>HG Resorts | Admin Panel</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: linear-gradient(135deg, #232526, #414345);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: "Segoe UI", sans-serif;
        }
        .card {
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.35);
            background: #1e1e2f;
        }
        .card-header {
            background: #004e92;
            color: #fff;
            padding: 1.5rem;
            text-align: center;
        }
        .card-header h2 {
            margin: 0;
            font-size: 1.8rem;
        }
        .card-body {
            text-align: center;
            padding: 2rem 1.5rem;
        }
        .card-body i {
            color: #00b4d8;
        }
        .card-body p {
            color: #aaa;
        }
        .card-footer {
            background: #2c2c3c;
            padding: 1rem;
            text-align: center;
        }
        .btn-custom {
            background: #00b4d8;
            color: #fff;
            border-radius: 30px;
            font-weight: bold;
            transition: 0.3s;
            padding: 0.75rem;
        }
        .btn-custom:hover {
            background: #0096c7;
            transform: scale(1.03);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h2>HG Resorts Admin Side</h2>
                    </div>
                    <div class="card-body">
                        <i class="fas fa-user-shield fa-4x mb-3 text-black"></i>
                        <h3 class="text-white">Admin Panel Login</h3>
                        <p>Restricted access for authorized administrators only</p>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-custom w-100" href="admin">
                            <i class="fas fa-sign-in-alt me-2"></i> Login Here
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>

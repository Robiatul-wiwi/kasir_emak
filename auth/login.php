<?php
session_start();


if (isset($_SESSION["ssLoginPOS"])) {
    header("location: ../dashboard.php");
    exit();
}

require "../config/config.php";

if (isset($_POST['login'])) {
    // Ambil input dan trim spasi
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepared statement untuk keamanan
    $stmt = mysqli_prepare($koneksi, "SELECT userid, username, password FROM tbl_user WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // cek password dengan password_verify
        if (password_verify($password, $row['password'])) {
            // simpan session
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['userid'] = $row['userid'];

            // set session
            $_SESSION["ssLoginPOS"] = true;
            $_SESSION["ssUserPOS"]  = $username;

            header("Location: ../dashboard.php");
            exit();
        } else {
            echo "<script>alert('Password salah.');</script>";
        }
    } else {
        echo "<script>alert('Username tidak terdaftar.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | kasir_emak</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= $main_url ?>asset/AdminLTE-3.2.0/dist/css/adminlte.min.css">
    <!-- favicon -->
    <link rel="shortcut icon" href="<?= $main_url ?>asset/image/cart.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>
<body class="hold-transition login-page" id="bg-login">
<div class="login-box slide-down" style="margin-top: -70px;">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="#" class="h1"><b>kasir_emak</b></a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <form action="" method="post">
                <div class="input-group mb-4">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-user"></span></div>
                    </div>
                </div>
                <div class="input-group mb-4">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>
                <div class="mb-4">
                    <button type="submit" name="login" class="btn btn-primary btn-block">Sign In</button>
                </div>
            </form>

            <p class="my-3 text-center">
                <strong>Copyright &copy; 2025 <span class="text-info">kasir_emak</span></strong>
            </p>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>
</body>
</html>

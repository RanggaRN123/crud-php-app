<?php
session_start();
include "koneksi.php";

function input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errorUsername = "";
$errorPassword = "";
$errorLogin = "";
$username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = input($_POST["username"]);
    $password = input($_POST["password"]);

    // Validasi kosong
    if (empty($username)) {
        $errorUsername = "Username harus diisi.";
    }
    if (empty($password)) {
        $errorPassword = "Password harus diisi.";
    }

    if (!$errorUsername && !$errorPassword) {
        // Cek username di database
        $cek = mysqli_query($kon, "SELECT * FROM users WHERE username='$username'");
        if (mysqli_num_rows($cek) == 1) {
            $data = mysqli_fetch_assoc($cek);
            // Verifikasi password
            if (password_verify($password, $data['password'])) {
                // Login sukses, simpan session
                $_SESSION['username'] = $data['username'];
                header("Location: index.php");
                exit;
            } else {
                $errorLogin = "Password salah.";
            }
        } else {
            $errorLogin = "Username tidak ditemukan.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Akun</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="max-width: 500px; margin-top: 50px;">
    <h3 class="text-center mb-4">Login</h3>

    <?php if ($errorLogin): ?>
        <div class="alert alert-danger"><?= $errorLogin ?></div>
    <?php endif; ?>

    <form action="" method="post" novalidate>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control <?= $errorUsername ? 'is-invalid' : '' ?>" placeholder="Masukkan username" autofocus value="<?= htmlspecialchars($username) ?>">
            <?php if ($errorUsername): ?>
                <div class="invalid-feedback"><?= $errorUsername ?></div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control <?= $errorPassword ? 'is-invalid' : '' ?>" placeholder="Masukkan password">
            <?php if ($errorPassword): ?>
                <div class="invalid-feedback"><?= $errorPassword ?></div>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
    <p class="text-center mt-3">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</div>
</body>
</html>

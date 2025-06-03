<?php
session_start();
include "koneksi.php";

function input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Inisialisasi error dan success
$errorUsername = "";
$errorPassword = "";
$errorPassword2 = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = input($_POST["username"]);
    $password = input($_POST["password"]);
    $password2 = input($_POST["password2"]);

    // Validasi input kosong
    if (empty($username)) {
        $errorUsername = "Username harus diisi.";
    }

    if (empty($password)) {
        $errorPassword = "Password harus diisi.";
    }

    if (empty($password2)) {
        $errorPassword2 = "Konfirmasi password harus diisi.";
    }

    // Validasi password sama (hanya jika password dan konfirmasi tidak kosong)
    if (!$errorPassword && !$errorPassword2 && ($password !== $password2)) {
        $errorPassword2 = "Password dan konfirmasi password tidak sama.";
    }

    // Cek username di database (hanya jika username tidak kosong)
    if (!$errorUsername) {
        $cek = mysqli_query($kon, "SELECT * FROM users WHERE username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            $errorUsername = "Username sudah terdaftar, silakan pakai username lain.";
        }
    }

    // Kalau semua error kosong, insert data ke database
    if (!$errorUsername && !$errorPassword && !$errorPassword2) {
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        $insert = mysqli_query($kon, "INSERT INTO users (username, password) VALUES ('$username', '$pass_hash')");
        if ($insert) {
            $success = "Registrasi berhasil! Silakan login.";
            // Bersihkan input supaya form kosong setelah submit sukses
            $username = $password = $password2 = "";
        } else {
            // Kalau insert gagal
            $errorUsername = "Registrasi gagal, silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Akun</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="max-width: 500px; margin-top: 50px;">
    <h3 class="text-center mb-4">Daftar Akun</h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form action="" method="post" novalidate>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control <?= $errorUsername ? 'is-invalid' : '' ?>" placeholder="Masukkan username" autofocus value="<?= htmlspecialchars($username ?? '') ?>">
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
        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="password2" class="form-control <?= $errorPassword2 ? 'is-invalid' : '' ?>" placeholder="Konfirmasi password">
            <?php if ($errorPassword2): ?>
                <div class="invalid-feedback"><?= $errorPassword2 ?></div>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Daftar</button>
    </form>
    <p class="text-center mt-3">Sudah punya akun? <a href="login.php">Login di sini</a></p>
</div>
</body>
</html>

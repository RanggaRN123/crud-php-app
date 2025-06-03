<!DOCTYPE html>
<html>
<head>
    <title>Form Pendaftaran Mahasiswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <?php
    include "koneksi.php";

    function input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $errors = [];
    $nama = $nim = $jurusan = $no_hp = $alamat = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama = input($_POST["nama"]);
        $nim = input($_POST["nim"]);
        $jurusan = input($_POST["jurusan"]);
        $no_hp = input($_POST["no_hp"]);
        $alamat = input($_POST["alamat"]);

        if (empty($nama)) $errors['nama'] = "Nama harus diisi";
        if (empty($nim)) $errors['nim'] = "NIM harus diisi";
        elseif (!ctype_digit($nim)) $errors['nim'] = "NIM harus berupa angka";

        if (empty($jurusan)) $errors['jurusan'] = "Jurusan harus diisi";
        if (empty($no_hp)) $errors['no_hp'] = "No HP harus diisi";
        elseif (!ctype_digit($no_hp)) $errors['no_hp'] = "No HP harus berupa angka";

        if (empty($alamat)) $errors['alamat'] = "Alamat harus diisi";

        if (empty($errors)) {
            $sql = "INSERT INTO mahasiswa (nama, nim, jurusan, no_hp, alamat) VALUES
                    ('$nama', '$nim', '$jurusan', '$no_hp', '$alamat')";
            $hasil = mysqli_query($kon, $sql);

            if ($hasil) {
                header("Location: index.php");
                exit;
            } else {
                echo "<div class='alert alert-danger'>Data gagal disimpan.</div>";
            }
        }
    }
    ?>

    <h2>Input Data Mahasiswa</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <?php if (!empty($errors)) echo "<div class='alert alert-danger'>Silakan periksa input Anda.</div>"; ?>

        <div class="form-group">
            <label>Nama:</label>
            <input type="text" name="nama" class="form-control" value="<?php echo $nama; ?>" />
            <?php if (isset($errors['nama'])) echo "<small class='text-danger'>{$errors['nama']}</small>"; ?>
        </div>

        <div class="form-group">
            <label>NIM:</label>
            <input type="text" name="nim" class="form-control" value="<?php echo $nim; ?>" />
            <?php if (isset($errors['nim'])) echo "<small class='text-danger'>{$errors['nim']}</small>"; ?>
        </div>

        <div class="form-group">
            <label>Jurusan:</label>
            <input type="text" name="jurusan" class="form-control" value="<?php echo $jurusan; ?>" />
            <?php if (isset($errors['jurusan'])) echo "<small class='text-danger'>{$errors['jurusan']}</small>"; ?>
        </div>

        <div class="form-group">
            <label>No HP:</label>
            <input type="text" name="no_hp" class="form-control" value="<?php echo $no_hp; ?>" />
            <?php if (isset($errors['no_hp'])) echo "<small class='text-danger'>{$errors['no_hp']}</small>"; ?>
        </div>

        <div class="form-group">
            <label>Alamat:</label>
            <textarea name="alamat" class="form-control"><?php echo $alamat; ?></textarea>
            <?php if (isset($errors['alamat'])) echo "<small class='text-danger'>{$errors['alamat']}</small>"; ?>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        <a href="index.php" class="btn btn-secondary ml-2">Batal</a>
    </form>
</div>
</body>
</html>

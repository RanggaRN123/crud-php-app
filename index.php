<?php
session_start();

// Cek apakah user sudah login, kalau belum arahkan ke login.php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";

// Proses logout jika tombol logout ditekan
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" crossorigin="anonymous">
<title>Radhen Adebos</title>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark d-flex justify-content-between align-items-center px-3">
        <span class="navbar-brand mb-0 h1">CRUD SEDERHANA BY RANGGA</span>
        <div class="d-flex align-items-center">
            <span class="text-white mr-3">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <form method="post" class="mb-0">
                <button type="submit" name="logout" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </nav>

<div class="container">
    <br>
    <h4><center>DAFTAR MAHASISWA</center></h4>

<?php
    // Proses hapus data
    if (isset($_GET['id_mahasiswa'])) {
        $id_mahasiswa=htmlspecialchars($_GET["id_mahasiswa"]);

        $sql="delete from mahasiswa where id_mahasiswa='$id_mahasiswa' ";
        $hasil=mysqli_query($kon,$sql);

        if ($hasil) {
            header("Location:index.php");
            exit;
        }
        else {
            echo "<div class='alert alert-danger'> Data Gagal dihapus.</div>";
        }
    }
?>

<table class="my-3 table table-bordered">
    <thead class="table-primary">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Jurusan</th>
            <th>No Hp</th>
            <th>Alamat</th>
            <th colspan='2'>Aksi</th>
        </tr>
    </thead>
    <tbody>
<?php
    $sql="select * from mahasiswa order by id_mahasiswa desc";
    $hasil=mysqli_query($kon,$sql);
    $no=0;
    while ($data = mysqli_fetch_array($hasil)) {
        $no++;
?>
        <tr>
            <td><?php echo $no;?></td>
            <td><?php echo $data["nama"]; ?></td>
            <td><?php echo $data["nim"]; ?></td>
            <td><?php echo $data["jurusan"]; ?></td>
            <td><?php echo $data["no_hp"]; ?></td>
            <td><?php echo $data["alamat"]; ?></td>
            <td>
                <a href="update.php?id_mahasiswa=<?php echo htmlspecialchars($data['id_mahasiswa']); ?>" class="btn btn-warning" role="button">Update</a>
                <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?id_mahasiswa=<?php echo $data['id_mahasiswa']; ?>" class="btn btn-danger" role="button" onclick="return confirm('Yakin ingin menghapus data ini?')">Delete</a>
            </td>
        </tr>
<?php
    }
?>
    </tbody>
</table>

<a href="create.php" class="btn btn-primary" role="button">Tambah Data</a>
</div>
</body>
</html>

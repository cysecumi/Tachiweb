<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("location: ../login.php?pesan=akses_ditolak");
    exit;
}

$id_komik = $_POST['id_komik'];
$judul    = $_POST['judul'];
$author   = $_POST['author'];
$sumber   = $_POST['sumber'];
$status   = $_POST['status'];
$sinopsis = $_POST['sinopsis'];

// Cek apakah ada file cover baru yang diunggah
if(isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
    $nama_file     = $_FILES['cover']['name'];
    $tmp_file      = $_FILES['cover']['tmp_name'];
    $nama_unik     = time() . '_' . basename($nama_file);
    
    $folder_tujuan = "../assets/" . $nama_unik;
    $path_db       = "assets/" . $nama_unik;

    if(move_uploaded_file($tmp_file, $folder_tujuan)) {
        $query = mysqli_query($koneksi, "UPDATE komik SET judul='$judul', author='$author', sumber='$sumber', status='$status', cover='$path_db', sinopsis='$sinopsis' WHERE id_komik='$id_komik'");
    }
} else {
    // Jika cover tidak diubah
    $query = mysqli_query($koneksi, "UPDATE komik SET judul='$judul', author='$author', sumber='$sumber', status='$status', sinopsis='$sinopsis' WHERE id_komik='$id_komik'");
}

if($query) {
    header("location: ../admin.php?pesan=edit_sukses");
} else {
    echo "Gagal memperbarui data: " . mysqli_error($koneksi);
}
?>
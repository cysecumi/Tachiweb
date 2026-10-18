<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("location: ../login.php?pesan=akses_ditolak");
    exit;
}

$judul    = mysqli_real_escape_string($koneksi, $_POST['judul']);
$author   = mysqli_real_escape_string($koneksi, $_POST['author']);
$sumber   = mysqli_real_escape_string($koneksi, $_POST['sumber']);
$status   = mysqli_real_escape_string($koneksi, $_POST['status']);
$sinopsis = mysqli_real_escape_string($koneksi, $_POST['sinopsis']);

if(isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
    $nama_file  = $_FILES['cover']['name'];
    $tmp_file   = $_FILES['cover']['tmp_name'];
    $ukuran     = $_FILES['cover']['size'];

    $ekstensi_diizinkan = ['jpg', 'jpeg', 'png', 'webp'];
    $ekstensi           = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

    if(!in_array($ekstensi, $ekstensi_diizinkan) || $ukuran > 2097152) {
        echo "File tidak valid atau melebihi 2MB!";
        exit;
    }

    $nama_unik     = time() . '_' . basename($nama_file);
    $folder_tujuan = "../assets/" . $nama_unik;
    $path_db       = "assets/" . $nama_unik;

    if(move_uploaded_file($tmp_file, $folder_tujuan)) {
        $query = mysqli_query($koneksi, "INSERT INTO komik (judul, author, sumber, status, cover, sinopsis) VALUES ('$judul', '$author', '$sumber', '$status', '$path_db', '$sinopsis')");

        if($query) {
            header("location: ../admin.php?pesan=tambah_sukses");
            exit;
        }
    }
}
header("location: ../admin.php?pesan=gagal");
?>
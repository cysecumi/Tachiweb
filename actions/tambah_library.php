<?php
session_start();
include '../config/koneksi.php';

// Pastikan user sudah login
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location:../login.php?pesan=belum_login");
    exit;
}

$id_user = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : (isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0);

// Ambil data dari form detail.php
$id_komik    = isset($_POST['id_asli']) ? intval($_POST['id_asli']) : 0;
$judul_komik = mysqli_real_escape_string($koneksi, $_POST['judul_komik']);
$sumber_komik= mysqli_real_escape_string($koneksi, $_POST['sumber_komik']);
$cover_komik = mysqli_real_escape_string($koneksi, $_POST['cover_komik']);

// Cek apakah komik sudah ada di library user ini
$cek = mysqli_query($koneksi, "SELECT * FROM library WHERE id_user='$id_user' AND id_komik='$id_komik'");

if(mysqli_num_rows($cek) > 0){
    // Jika sudah ada, kembali dengan status sudah_ada
    header("location:../detail.php?id=$id_komik&status=sudah_ada");
} else {
    // Jika belum ada, simpan ke database
    mysqli_query($koneksi, "INSERT INTO library (id_user, id_komik, judul_komik, sumber, cover) 
                            VALUES ('$id_user', '$id_komik', '$judul_komik', '$sumber_komik', '$cover_komik')");
    header("location:../detail.php?id=$id_komik&status=sukses");
}
?>

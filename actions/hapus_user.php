<?php
session_start();
include '../config/koneksi.php';

// Validasi Keamanan: Hanya admin yang boleh menghapus user
if(!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("location: ../login.php?pesan=akses_ditolak");
    exit;
}

if(isset($_POST['id_user'])) {
    $id_user = mysqli_real_escape_string($koneksi, $_POST['id_user']);

    // 1. Hapus terlebih dahulu seluruh komik di library milik user ini
    mysqli_query($koneksi, "DELETE FROM library WHERE id_user = '$id_user'");

    // 2. Hapus akun user dari tabel users
    $query = mysqli_query($koneksi, "DELETE FROM users WHERE id_user = '$id_user'");

    if($query) {
        header("location: ../admin.php?pesan=hapus_user_sukses");
        exit;
    }
}

header("location: ../admin.php");
?>
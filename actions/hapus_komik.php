<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("location: ../login.php?pesan=akses_ditolak");
    exit;
}

if(isset($_POST['id_komik'])) {
    $id_komik = mysqli_real_escape_string($koneksi, $_POST['id_komik']);
    $query    = mysqli_query($koneksi, "DELETE FROM komik WHERE id_komik = '$id_komik'");

    if($query) {
        header("location: ../admin.php?pesan=hapus_sukses");
        exit;
    }
}
header("location: ../admin.php");
?>
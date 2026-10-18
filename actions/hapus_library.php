<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location: ../login.php?pesan=akses_ditolak");
    exit;
}

if(isset($_POST['id_library'])) {
    $id_library = mysqli_real_escape_string($koneksi, $_POST['id_library']);
    $id_user    = $_SESSION['id_user'];

    $query = mysqli_query($koneksi, "DELETE FROM library WHERE id_library = '$id_library' AND id_user = '$id_user'");

    if($query) {
        header("location: ../library.php?pesan=hapus_sukses");
        exit;
    }
}
header("location: ../library.php");
?>
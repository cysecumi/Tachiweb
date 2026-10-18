<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location: ../login.php?pesan=harus_login");
    exit;
}

$id_user  = $_SESSION['id_user'];
$judul    = mysqli_real_escape_string($koneksi, $_POST['judul_komik']);
$sumber   = mysqli_real_escape_string($koneksi, $_POST['sumber_komik']);
$cover    = mysqli_real_escape_string($koneksi, $_POST['cover_komik']);
$id_asli  = mysqli_real_escape_string($koneksi, $_POST['id_asli']);

$cek_duplikat = mysqli_query($koneksi, "SELECT * FROM library WHERE id_user='$id_user' AND judul_komik='$judul'");

if(mysqli_num_rows($cek_duplikat) > 0){
    header("location: ../detail.php?id=" . $id_asli . "&status=sudah_ada");
} else {
    $query = mysqli_query($koneksi, "INSERT INTO library (id_user, judul_komik, sumber, cover) VALUES ('$id_user', '$judul', '$sumber', '$cover')");
    
    if($query){
        header("location: ../detail.php?id=" . $id_asli . "&status=sukses");
    }
}
?>
<?php
session_start();
include '../config/koneksi.php';

// Pastikan yang mengakses adalah user yang sudah login (Tamu tidak bisa komen)
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location:../login.php");
    exit;
}

if(isset($_POST['id_komik']) && isset($_POST['isi_komentar'])){
    $id_komik = intval($_POST['id_komik']);
    $id_user = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : (isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0);
    $isi = mysqli_real_escape_string($koneksi, trim($_POST['isi_komentar']));
    
    // Jangan simpan kalau komentarnya kosong
    if($isi != '' && $id_user > 0){
        mysqli_query($koneksi, "INSERT INTO komentar (id_komik, id_user, isi_komentar) VALUES ('$id_komik', '$id_user', '$isi')");
    }
    
    // Kembalikan user ke halaman detail komik tersebut
    header("location:../detail.php?id=$id_komik");
    exit;
}
?>

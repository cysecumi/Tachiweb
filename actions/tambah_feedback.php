<?php
session_start();
include '../config/koneksi.php';

// Pastikan user sudah login
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location:../login.php");
    exit;
}

if(isset($_POST['isi_feedback'])){
    $id_user = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : (isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0);
    $isi = mysqli_real_escape_string($koneksi, trim($_POST['isi_feedback']));
    
    // Simpan ke database jika tidak kosong
    if($isi != '' && $id_user > 0){
        mysqli_query($koneksi, "INSERT INTO feedback (id_user, isi_feedback) VALUES ('$id_user', '$isi')");
    }
    
    // Kembalikan ke halaman feedback dengan pesan sukses
    header("location:../feedback.php?status=sukses");
    exit;
}
?>

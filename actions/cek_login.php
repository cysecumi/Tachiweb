<?php
session_start();
include '../config/koneksi.php';

$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = $_POST['password'];

// Cari data berdasarkan username
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
$user  = mysqli_fetch_assoc($query);

// Verifikasi password hash
if($user && password_verify($password, $user['password'])){
    $_SESSION['id_user']        = $user['id_user'];
    $_SESSION['username']       = $user['username'];
    $_SESSION['nama_panggilan'] = $user['nama_panggilan'];
    $_SESSION['status']         = "login";
    
    header("location: ../index.php");
} else {
    header("location: ../login.php?pesan=gagal");
}
?>
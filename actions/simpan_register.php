<?php
include '../config/koneksi.php';

$username       = mysqli_real_escape_string($koneksi, $_POST['username']);
$nama_panggilan = mysqli_real_escape_string($koneksi, $_POST['nama_panggilan']);
$password       = $_POST['password'];

// Cek ketersediaan username
$cek_username = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");

if(mysqli_num_rows($cek_username) > 0) {
    header("location: ../login.php?pesan=username_kembar");
    exit();
}

// Enkripsi password menggunakan BCRYPT
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Masukkan $password_hash ke database
$query = mysqli_query($koneksi, "INSERT INTO users (username, nama_panggilan, password) VALUES ('$username', '$nama_panggilan', '$password_hash')");

if($query) {
    header("location: ../login.php?pesan=daftar_berhasil");
} else {
    header("location: ../register.php?pesan=gagal");
}
?>
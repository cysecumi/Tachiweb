<?php
session_start();
include '../config/koneksi.php';

// Validasi Keamanan: Hanya admin yang boleh mereset password
if(!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("location: ../login.php?pesan=akses_ditolak");
    exit;
}

if(isset($_POST['id_user'])) {
    $id_user          = intval($_POST['id_user']);
    $password_default = "123456"; // Kata sandi bawaan setelah reset
    $password_hashed  = password_hash($password_default, PASSWORD_BCRYPT);
    
    // Menggunakan teks biasa tanpa emoji agar tidak crash di database
    $pesan_notif      = "[PERINGATAN] Kata sandi Anda telah di-reset oleh Admin menjadi '123456'. Harap segera ganti kata sandi Anda demi keamanan!";
    $pesan_notif_aman = mysqli_real_escape_string($koneksi, $pesan_notif);

    // Update password dan beri notifikasi ke pengguna
    $query = mysqli_query($koneksi, "UPDATE users SET password = '$password_hashed', pesan_notifikasi = '$pesan_notif_aman' WHERE id_user = $id_user");

    if($query) {
        header("location: ../admin.php?pesan=reset_sukses");
        exit;
    }
}

header("location: ../admin.php");
exit;
?>

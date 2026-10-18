<?php
session_start();
// Membuat sesi khusus untuk tamu
$_SESSION['id_user'] = 0;
$_SESSION['username'] = "guest";
$_SESSION['nama_panggilan'] = "Tamu";
$_SESSION['status'] = "guest";

// Langsung arahkan ke Halaman Utama
header("location:index.php");
?>
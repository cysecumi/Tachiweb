<?php
$host = "127.0.0.1";
$user = "root";
$pass = ""; // Kosongkan jika default XAMPP
$db   = "db_komik";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set karakter utf8mb4 agar mendukung aksara Hangul, Kanji, Chinese & Emoji
mysqli_set_charset($koneksi, "utf8mb4");
?>

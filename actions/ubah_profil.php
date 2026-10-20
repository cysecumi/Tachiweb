<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['id_user']) && !isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user        = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : intval($_SESSION['user_id']);
    $nama_panggilan = mysqli_real_escape_string($koneksi, trim($_POST['nama_panggilan']));

    if (empty($nama_panggilan)) {
        header("Location: ../pengaturan.php?pesan=nama_kosong");
        exit();
    }

    $query = "UPDATE users SET nama_panggilan = '$nama_panggilan' WHERE id_user = $id_user";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['nama_panggilan'] = $nama_panggilan;
        header("Location: ../pengaturan.php?pesan=nama_sukses");
    } else {
        header("Location: ../pengaturan.php?pesan=gagal");
    }
    exit();
}

header("Location: ../pengaturan.php");
exit();
?>

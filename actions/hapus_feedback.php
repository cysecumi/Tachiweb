<?php
session_start();
include '../config/koneksi.php';

// Validasi Keamanan: Hanya admin yang boleh menghapus feedback
if(!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("location: ../login.php?pesan=akses_ditolak");
    exit;
}

if(isset($_POST['id_feedback'])) {
    $id_feedback = intval($_POST['id_feedback']);
    $query = mysqli_query($koneksi, "DELETE FROM feedback WHERE id_feedback = $id_feedback");

    if($query) {
        header("location: ../admin.php?pesan=hapus_feedback_sukses");
        exit;
    }
}

header("location: ../admin.php");
exit;
?>

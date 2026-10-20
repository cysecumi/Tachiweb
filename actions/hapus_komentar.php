<?php
session_start();
include '../config/koneksi.php';

// Proteksi: Pengguna wajib login
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location: ../login.php?pesan=akses_ditolak");
    exit;
}

if(isset($_POST['id_komentar']) && isset($_POST['id_komik'])) {
    $id_komentar  = intval($_POST['id_komentar']);
    $id_komik     = intval($_POST['id_komik']);
    $id_user_sess = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : (isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0);
    $is_admin     = (isset($_SESSION['username']) && $_SESSION['username'] == 'admin');

    // Ambil pemilik komentar
    $cek_komentar  = mysqli_query($koneksi, "SELECT id_user FROM komentar WHERE id_komentar = $id_komentar");
    $data_komentar = mysqli_fetch_assoc($cek_komentar);

    if($data_komentar) {
        // Hapus jika pengakses adalah ADMIN atau PEMILIK KOMENTAR itu sendiri
        if($is_admin || $data_komentar['id_user'] == $id_user_sess) {
            mysqli_query($koneksi, "DELETE FROM komentar WHERE id_komentar = $id_komentar");
            header("location: ../detail.php?id=$id_komik&pesan=hapus_komentar_sukses");
            exit;
        }
    }
    header("location: ../detail.php?id=$id_komik&pesan=akses_ditolak");
    exit;
}

header("location: ../index.php");
exit;
?>

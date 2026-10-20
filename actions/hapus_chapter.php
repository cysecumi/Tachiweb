<?php
session_start();
include '../config/koneksi.php';

// cuma admin yang boleh
if(!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("location: ../login.php?pesan=akses_ditolak");
    exit;
}

$id_chapter = isset($_POST['id_chapter']) ? (int)$_POST['id_chapter'] : 0;
$id_komik   = isset($_POST['id_komik']) ? (int)$_POST['id_komik'] : 0;

$balik = "../kelola_chapter.php?id=" . $id_komik;

if($id_chapter > 0) {
    // hapus file gambar yang kita upload sendiri (url luar seperti https:// dilewati)
    $q_hal = mysqli_query($koneksi, "SELECT url_gambar FROM halaman_komik WHERE id_chapter = $id_chapter");
    while($hal = mysqli_fetch_assoc($q_hal)) {
        $url = $hal['url_gambar'];
        if(strpos($url, 'uploads/chapter/') === 0 && strpos($url, '..') === false && file_exists('../' . $url)) {
            unlink('../' . $url);
        }
    }
    // hapus folder chapter kalau sudah kosong
    @rmdir('../uploads/chapter/c' . $id_chapter);

    // hapus data di database (halaman dulu, baru chapter)
    mysqli_query($koneksi, "DELETE FROM halaman_komik WHERE id_chapter = $id_chapter");
    mysqli_query($koneksi, "DELETE FROM chapter WHERE id_chapter = $id_chapter AND id_komik = $id_komik");

    header("location: $balik&pesan=hapus_sukses");
    exit;
}

header("location: $balik");
?>
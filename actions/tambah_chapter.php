<?php
session_start();
include '../config/koneksi.php';

// cuma admin yang boleh
if(!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("location: ../login.php?pesan=akses_ditolak");
    exit;
}

// kalau total file kebesaran, PHP mengosongkan $_POST dan $_FILES
if(empty($_POST)) {
    echo "Ukuran total file terlalu besar (kena batas post_max_size). Upload lebih sedikit halaman sekali kirim, atau naikkan batas di server PHP.";
    exit;
}

$id_komik = (int)$_POST['id_komik'];
$nomor    = (int)$_POST['nomor_chapter'];
$judul    = mysqli_real_escape_string($koneksi, trim($_POST['judul_chapter']));

$balik = "../kelola_chapter.php?id=" . $id_komik;

// cek nomor chapter
if($nomor < 1) {
    header("location: $balik&pesan=nomor_salah");
    exit;
}

// cek komiknya ada
$cek_komik = mysqli_query($koneksi, "SELECT id_komik FROM komik WHERE id_komik = $id_komik");
if(mysqli_num_rows($cek_komik) == 0) {
    header("location: ../admin.php");
    exit;
}

// cek nomor chapter belum dipakai
$cek_nomor = mysqli_query($koneksi, "SELECT id_chapter FROM chapter WHERE id_komik = $id_komik AND nomor_chapter = $nomor");
if(mysqli_num_rows($cek_nomor) > 0) {
    header("location: $balik&pesan=nomor_kembar");
    exit;
}

// kumpulkan dan cek semua file dulu sebelum disimpan
$ekstensi_ok = ['jpg', 'jpeg', 'png', 'webp'];
$maks_ukuran = 5 * 1024 * 1024; // 5MB per file
$daftar_file = [];

if(!isset($_FILES['halaman']) || empty($_FILES['halaman']['name'][0])) {
    header("location: $balik&pesan=file_kosong");
    exit;
}

$jumlah = count($_FILES['halaman']['name']);
for($i = 0; $i < $jumlah; $i++) {
    $nama = $_FILES['halaman']['name'][$i];
    $tmp  = $_FILES['halaman']['tmp_name'][$i];
    $err  = $_FILES['halaman']['error'][$i];
    $size = $_FILES['halaman']['size'][$i];
    $ext  = strtolower(pathinfo($nama, PATHINFO_EXTENSION));

    // cek ekstensi, ukuran, dan benar-benar gambar
    if($err !== 0 || !in_array($ext, $ekstensi_ok) || $size > $maks_ukuran || getimagesize($tmp) === false) {
        header("location: $balik&pesan=file_tidak_valid");
        exit;
    }

    $daftar_file[] = ['nama' => $nama, 'tmp' => $tmp, 'ext' => $ext];
}

// urutkan berdasarkan nama file (1, 2, 10 bukan 1, 10, 2)
usort($daftar_file, function($a, $b) {
    return strnatcasecmp($a['nama'], $b['nama']);
});

// simpan chapter dulu supaya dapat id_chapter
$simpan = mysqli_query($koneksi, "INSERT INTO chapter (id_komik, nomor_chapter, judul_chapter) VALUES ($id_komik, $nomor, '$judul')");
if(!$simpan) {
    header("location: $balik&pesan=gagal");
    exit;
}
$id_chapter = mysqli_insert_id($koneksi);

// folder khusus per chapter
$folder = "../uploads/chapter/c" . $id_chapter . "/";
if(!is_dir($folder)) {
    mkdir($folder, 0755, true);
}

// pindahkan tiap gambar dan catat ke database
$berhasil = true;
$no_halaman = 1;
foreach($daftar_file as $f) {
    $nama_baru = sprintf("%03d", $no_halaman) . "." . $f['ext'];
    $path_db   = "uploads/chapter/c" . $id_chapter . "/" . $nama_baru;

    if(move_uploaded_file($f['tmp'], $folder . $nama_baru)) {
        $q = mysqli_query($koneksi, "INSERT INTO halaman_komik (id_chapter, nomor_halaman, url_gambar) VALUES ($id_chapter, $no_halaman, '$path_db')");
        if(!$q) { $berhasil = false; break; }
    } else {
        $berhasil = false;
        break;
    }
    $no_halaman++;
}

if($berhasil) {
    header("location: $balik&pesan=tambah_sukses");
} else {
    // gagal di tengah jalan: bersihkan lagi supaya tidak ada chapter setengah jadi
    mysqli_query($koneksi, "DELETE FROM halaman_komik WHERE id_chapter = $id_chapter");
    mysqli_query($koneksi, "DELETE FROM chapter WHERE id_chapter = $id_chapter");
    array_map('unlink', glob($folder . '*'));
    @rmdir($folder);
    header("location: $balik&pesan=gagal");
}
?>
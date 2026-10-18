<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("location: ../login.php?pesan=akses_ditolak");
    exit;
}

if(isset($_FILES['cover_baru']) && $_FILES['cover_baru']['error'] === 0) {
    $nama_file_asli = $_FILES['cover_baru']['name'];
    $lokasi_tmp     = $_FILES['cover_baru']['tmp_name'];
    $ukuran_file    = $_FILES['cover_baru']['size'];
    $id_asli        = mysqli_real_escape_string($koneksi, $_POST['id_asli']); 
    
    // Validasi Ekstensi Gambar
    $ekstensi_diizinkan = ['jpg', 'jpeg', 'png', 'webp'];
    $ekstensi_file      = strtolower(pathinfo($nama_file_asli, PATHINFO_EXTENSION));

    if(!in_array($ekstensi_file, $ekstensi_diizinkan)) {
        echo "Format file harus berupa JPG, JPEG, PNG, atau WEBP!";
        exit;
    }

    // Validasi Ukuran File (Max 2MB)
    if($ukuran_file > 2097152) {
        echo "Ukuran file terlalu besar! Maksimal 2MB.";
        exit;
    }

    $nama_unik     = time() . '_' . basename($nama_file_asli);
    $folder_tujuan = "../assets/" . $nama_unik;
    $path_db       = "assets/" . $nama_unik;

    if(move_uploaded_file($lokasi_tmp, $folder_tujuan)) {
        $update = mysqli_query($koneksi, "UPDATE komik SET cover = '$path_db' WHERE id_komik = '$id_asli'");
        
        if($update) {
            header("location: ../detail.php?id=" . $id_asli . "&status=upload_sukses");
            exit;
        }
    }
}
header("location: ../admin.php");
?>
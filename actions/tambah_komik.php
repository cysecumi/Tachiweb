<?php
session_start();
include '../config/koneksi.php';

// Validasi Keamanan: Hanya admin
if(!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("location: ../login.php?pesan=akses_ditolak");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul    = mysqli_real_escape_string($koneksi, trim($_POST['judul']));
    $author   = mysqli_real_escape_string($koneksi, trim($_POST['author']));
    $sumber   = mysqli_real_escape_string($koneksi, trim($_POST['sumber']));
    $status   = mysqli_real_escape_string($koneksi, trim($_POST['status']));
    $tipe     = mysqli_real_escape_string($koneksi, trim($_POST['tipe'] ?? 'Manga'));
    $genre    = mysqli_real_escape_string($koneksi, trim($_POST['genre'] ?? ''));
    $sinopsis = mysqli_real_escape_string($koneksi, trim($_POST['sinopsis']));

    // Validasi ketat untuk status
    if($status != 'Ongoing' && $status != 'Completed') {
        $status = 'Ongoing';
    }

    // Default cover jika admin tidak mengunggah gambar
    $path_db = "https://placehold.co/300x450/2c2c2c/bb86fc?text=No+Cover";

    // Penanganan unggah Cover
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
        $nama_file  = $_FILES['cover']['name'];
        $tmp_file   = $_FILES['cover']['tmp_name'];
        $ukuran     = $_FILES['cover']['size'];

        $ekstensi_diizinkan = ['jpg', 'jpeg', 'png', 'webp'];
        $ekstensi           = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        if (in_array($ekstensi, $ekstensi_diizinkan) && $ukuran <= 2097152) { // Maks 2MB
            $nama_bersih = preg_replace("/[^a-zA-Z0-9.]/", "_", basename($nama_file));
            $nama_unik   = time() . '_' . $nama_bersih;
            
            $folder_tujuan = "../assets/" . $nama_unik;

            if (move_uploaded_file($tmp_file, $folder_tujuan)) {
                $path_db = "assets/" . $nama_unik;
            }
        } else {
            header("location: ../admin.php?pesan=cover_tidak_valid");
            exit;
        }
    }

    // Simpan data ke database
    $query = "INSERT INTO komik (judul, author, sumber, status, tipe, genre, cover, sinopsis) 
              VALUES ('$judul', '$author', '$sumber', '$status', '$tipe', '$genre', '$path_db', '$sinopsis')";
    
    if (mysqli_query($koneksi, $query)) {
        header("location: ../admin.php?pesan=tambah_sukses");
    } else {
        header("location: ../admin.php?pesan=gagal");
    }
}
?>

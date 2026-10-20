<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("location: ../login.php?pesan=akses_ditolak");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_komik = intval($_POST['id_komik']);
    $judul    = mysqli_real_escape_string($koneksi, trim($_POST['judul']));
    $author   = mysqli_real_escape_string($koneksi, trim($_POST['author']));
    $sumber   = mysqli_real_escape_string($koneksi, trim($_POST['sumber']));
    $status   = mysqli_real_escape_string($koneksi, trim($_POST['status']));
    $tipe     = mysqli_real_escape_string($koneksi, trim($_POST['tipe']));
    $genre    = mysqli_real_escape_string($koneksi, trim($_POST['genre']));
    $sinopsis = mysqli_real_escape_string($koneksi, trim($_POST['sinopsis']));

    // Cek jika ada cover baru yang diunggah
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
        $nama_file  = $_FILES['cover']['name'];
        $tmp_file   = $_FILES['cover']['tmp_name'];
        $ukuran     = $_FILES['cover']['size'];

        $ekstensi_diizinkan = ['jpg', 'jpeg', 'png', 'webp'];
        $ekstensi           = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        if (in_array($ekstensi, $ekstensi_diizinkan) && $ukuran <= 2097152) {
            $nama_bersih = preg_replace("/[^a-zA-Z0-9.]/", "_", basename($nama_file));
            $nama_unik   = time() . '_' . $nama_bersih;
            
            $folder_tujuan = "../assets/" . $nama_unik;
            $path_db       = "assets/" . $nama_unik;

            if (move_uploaded_file($tmp_file, $folder_tujuan)) {
                // Hapus cover lama dari folder assets
                $q_lama = mysqli_query($koneksi, "SELECT cover FROM komik WHERE id_komik = $id_komik");
                $d_lama = mysqli_fetch_assoc($q_lama);
                
                if($d_lama && strpos($d_lama['cover'], 'assets/') === 0 && file_exists("../" . $d_lama['cover'])) {
                    unlink("../" . $d_lama['cover']);
                }

                $query = "UPDATE komik SET 
                            judul = '$judul', 
                            author = '$author', 
                            sumber = '$sumber', 
                            status = '$status', 
                            tipe = '$tipe', 
                            genre = '$genre', 
                            cover = '$path_db', 
                            sinopsis = '$sinopsis' 
                          WHERE id_komik = $id_komik";
                mysqli_query($koneksi, $query);
                header("location: ../admin.php?pesan=edit_sukses");
                exit;
            }
        }
    }

    // Update data tanpa mengganti cover
    $query = "UPDATE komik SET 
                judul = '$judul', 
                author = '$author', 
                sumber = '$sumber', 
                status = '$status', 
                tipe = '$tipe', 
                genre = '$genre', 
                sinopsis = '$sinopsis' 
              WHERE id_komik = $id_komik";

    if (mysqli_query($koneksi, $query)) {
        header("location: ../admin.php?pesan=edit_sukses");
        exit;
    }
}

header("location: ../admin.php?pesan=gagal");
exit;
?>

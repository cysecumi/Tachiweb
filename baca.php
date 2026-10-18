<?php
session_start();
include 'config/koneksi.php';

if(!isset($_SESSION['status']) || ($_SESSION['status'] != "login" && $_SESSION['status'] != "guest")){
    header("location:login.php?pesan=akses_ditolak");
    exit;
}

$id_komik = isset($_GET['id']) ? $_GET['id'] : 0;
$ch_num   = isset($_GET['ch']) ? $_GET['ch'] : 1;

// 1. Ambil data komik
$query_komik = mysqli_query($koneksi, "SELECT * FROM komik WHERE id_komik = '$id_komik'");
$komik       = mysqli_fetch_assoc($query_komik);
$judul_komik = $komik ? $komik['judul'] : "Komik";

// 2. Ambil data chapter aktif
$query_ch = mysqli_query($koneksi, "SELECT * FROM chapter WHERE id_komik = '$id_komik' AND nomor_chapter = '$ch_num'");
$chapter  = mysqli_fetch_assoc($query_ch);

$halaman_komik = [];
if($chapter) {
    $id_chapter = $chapter['id_chapter'];
    $query_hal  = mysqli_query($koneksi, "SELECT * FROM halaman_komik WHERE id_chapter = '$id_chapter' ORDER BY nomor_halaman ASC");
    while($hal = mysqli_fetch_assoc($query_hal)) {
        $halaman_komik[] = $hal['url_gambar'];
    }
}

// 3. Cek ketersediaan chapter prev & next
$cek_prev = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM chapter WHERE id_komik = '$id_komik' AND nomor_chapter = '" . ($ch_num - 1) . "'"));
$cek_next = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM chapter WHERE id_komik = '$id_komik' AND nomor_chapter = '" . ($ch_num + 1) . "'"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baca <?php echo $judul_komik; ?> - Ch. <?php echo $ch_num; ?> - TachiWeb</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body style="background-color: #0b0b0b; margin: 0; padding: 0;">

    <!-- HEADER READER -->
    <div class="reader-nav">
        <a href="detail.php?id=<?php echo $id_komik; ?>" class="back-btn">← Kembali</a>
        <div class="reader-title">
            <span><?php echo $judul_komik; ?></span>
            <span style="font-size: 0.8rem; color: #b3b3b3;">Chapter <?php echo $ch_num; ?></span>
        </div>
        <div style="width: 50px;"></div>
    </div>

    <!-- TAMPILAN GAMBAR HALAMAN -->
    <div class="reader-container">
        <?php 
        if(!empty($halaman_komik)) {
            foreach($halaman_komik as $index => $img) { 
        ?>
            <img src="<?php echo $img; ?>" alt="Halaman <?php echo $index + 1; ?>" class="reader-page">
        <?php 
            } 
        } else { 
        ?>
            <div style="text-align: center; padding: 100px 20px; color: #888;">
                <p style="font-size: 1.2rem; margin-bottom: 10px;">⚠️ Halaman komik belum tersedia untuk Chapter <?php echo $ch_num; ?>.</p>
                <a href="detail.php?id=<?php echo $id_komik; ?>" style="color: #bb86fc;">Kembali ke Daftar Chapter</a>
            </div>
        <?php } ?>
    </div>

    <!-- FOOTER READER (NAVIGASI PREV / NEXT) -->
    <div class="reader-bottom-nav">
        <?php if($cek_prev > 0) { ?>
            <a href="baca.php?id=<?php echo $id_komik; ?>&ch=<?php echo $ch_num - 1; ?>" class="nav-btn">← Prev Ch.</a>
        <?php } else { ?>
            <span class="nav-btn disabled">← Prev Ch.</span>
        <?php } ?>

        <a href="detail.php?id=<?php echo $id_komik; ?>" class="nav-btn-home">Daftar Chapter</a>

        <?php if($cek_next > 0) { ?>
            <a href="baca.php?id=<?php echo $id_komik; ?>&ch=<?php echo $ch_num + 1; ?>" class="nav-btn">Next Ch. →</a>
        <?php } else { ?>
            <span class="nav-btn disabled">Next Ch. →</span>
        <?php } ?>
    </div>

</body>
</html>
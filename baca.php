<?php
session_start();

// 1. Panggil koneksi database terlebih dahulu
include 'config/koneksi.php';

// Validasi akses sesi login atau tamu
if(!isset($_SESSION['status']) || ($_SESSION['status'] != "login" && $_SESSION['status'] != "guest")){
    header("location:login.php?pesan=akses_ditolak");
    exit;
}

// 2. Ambil parameter URL
$id_komik = isset($_GET['id']) ? intval($_GET['id']) : 0;
$ch_num   = isset($_GET['ch']) ? intval($_GET['ch']) : 1;

// 3. Simpan / Perbarui riwayat baca jika pengguna sudah login
if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    $id_user_riwayat  = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : (isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0);
    
    if ($id_user_riwayat > 0 && $id_komik > 0 && $ch_num > 0) {
        $q_riwayat = "INSERT INTO riwayat_baca (id_user, id_komik, last_chapter) 
                      VALUES ($id_user_riwayat, $id_komik, $ch_num) 
                      ON DUPLICATE KEY UPDATE last_chapter = $ch_num";
        mysqli_query($koneksi, $q_riwayat);
    }
}

// 4. Ambil data komik
$query_komik = mysqli_query($koneksi, "SELECT * FROM komik WHERE id_komik = '$id_komik'");
$komik       = mysqli_fetch_assoc($query_komik);
$judul_komik = $komik ? $komik['judul'] : "Komik";

// 5. Ambil data chapter aktif
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

// 6. Cek ketersediaan chapter prev & next
$cek_prev = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM chapter WHERE id_komik = '$id_komik' AND nomor_chapter = '" . ($ch_num - 1) . "'"));
$cek_next = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM chapter WHERE id_komik = '$id_komik' AND nomor_chapter = '" . ($ch_num + 1) . "'"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baca <?php echo htmlspecialchars($judul_komik); ?> - Ch. <?php echo $ch_num; ?> - TachiWeb</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* Transisi Mulus untuk Auto-Hide Header & Footer Reader */
        .reader-nav, .reader-bottom-nav {
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
            position: fixed;
            left: 0;
            width: 100%;
            z-index: 1000;
            background: rgba(18, 18, 18, 0.95);
            backdrop-filter: blur(5px);
        }
        .reader-nav { top: 0; }
        .reader-bottom-nav { bottom: 0; }

        /* Kelas Saat Disembunyikan */
        .reader-nav.hidden {
            transform: translateY(-100%);
            opacity: 0;
            pointer-events: none;
        }
        .reader-bottom-nav.hidden {
            transform: translateY(100%);
            opacity: 0;
            pointer-events: none;
        }

        /* Penyesuaian Ruang Gambar agar tidak tertutup header/footer tetap */
        .reader-container {
            padding-top: 60px;
            padding-bottom: 60px;
        }
    </style>
</head>
<body style="background-color: #0b0b0b; margin: 0; padding: 0;">

    <!-- HEADER READER -->
    <div class="reader-nav">
        <a href="detail.php?id=<?php echo $id_komik; ?>" class="back-btn">← Kembali</a>
        <div class="reader-title">
            <span><?php echo htmlspecialchars($judul_komik); ?></span>
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
            <img src="<?php echo htmlspecialchars($img); ?>" alt="Halaman <?php echo $index + 1; ?>" class="reader-page">
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

    <!-- SCRIPT INTERAKTIF TAP UNTUK MENYEMBUNYIKAN/MEMUNCULKAN MENU -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const header = document.querySelector('.reader-nav');
        const footer = document.querySelector('.reader-bottom-nav');
        
        document.addEventListener('click', function(e) {
            // Abaikan jika yang diklik adalah tombol navigasi itu sendiri
            if(e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || e.target.closest('a') || e.target.closest('button')) {
                return;
            }
            
            if (header && footer) {
                header.classList.toggle('hidden');
                footer.classList.toggle('hidden');
            }
        });
    });
    </script>

</body>
</html>

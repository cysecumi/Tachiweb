<?php
session_start();
include 'config/koneksi.php';

if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location:login.php?pesan=akses_ditolak");
    exit;
}

$id_user_sess = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : (isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0);

// Ambil data library milik user + cek riwayat bab terakhir dari riwayat_baca
$q_lib = "SELECT l.*, r.last_chapter 
          FROM library l 
          LEFT JOIN riwayat_baca r ON l.id_komik = r.id_komik AND r.id_user = $id_user_sess 
          WHERE l.id_user = $id_user_sess 
          ORDER BY l.id_library DESC";
$query_lib = mysqli_query($koneksi, $q_lib);
$jumlah_lib = mysqli_num_rows($query_lib);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Saya - TachiWeb</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <!-- Header -->
    <?php include 'components/header.php'; ?>

    <div class="container">
        <h3 class="section-title">Library Saya</h3>
        
        <?php if($jumlah_lib == 0) { ?>
            <div class="kosong">📚 Library kamu masih kosong. Yuk cari komik favoritmu di beranda!</div>
        <?php } else { ?>
            <div class="comic-grid">
                <?php 
                while($lib = mysqli_fetch_assoc($query_lib)) { 
                    $id_komik  = $lib['id_komik'];
                    $ch_lanjut = !empty($lib['last_chapter']) ? $lib['last_chapter'] : 1;
                    $teks_ch   = !empty($lib['last_chapter']) ? "📖 Lanjut Ch. " . $ch_lanjut : "📖 Mulai Ch. 1";
                ?>
                    <div class="comic-card">
                        <span class="comic-badge"><?php echo htmlspecialchars($lib['sumber']); ?></span>
                        <a href="detail.php?id=<?php echo $id_komik; ?>" style="text-decoration: none; color: inherit;">
                            <img src="<?php echo htmlspecialchars($lib['cover']); ?>" alt="Cover <?php echo htmlspecialchars($lib['judul_komik']); ?>">
                        </a>
                        <div class="comic-info">
                            <a href="detail.php?id=<?php echo $id_komik; ?>" style="text-decoration: none; color: inherit;">
                                <div class="comic-title"><?php echo htmlspecialchars($lib['judul_komik']); ?></div>
                            </a>
                            <div class="comic-meta" style="margin-top: 8px; display: flex; flex-direction: column; gap: 6px;">
                                <a href="baca.php?id=<?php echo $id_komik; ?>&ch=<?php echo $ch_lanjut; ?>" 
                                   style="display: block; text-align: center; background: #bb86fc; color: #121212; text-decoration: none; padding: 6px 10px; font-size: 0.8rem; font-weight: bold; border-radius: 4px;">
                                   <?php echo $teks_ch; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <!-- Footer -->
    <?php include 'components/footer.php'; ?>

</body>
</html>

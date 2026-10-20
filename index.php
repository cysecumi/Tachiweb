<?php
session_start();
// 1. Panggil koneksi database yang ada di folder config/
include 'config/koneksi.php';

// Otomatis atur sesi sebagai Guest jika pengunjung belum login (mencegah redirect ke login.php)
if(!isset($_SESSION['status'])){
    $_SESSION['status'] = "guest";
    $_SESSION['nama_panggilan'] = "Guest";
    $_SESSION['id_user'] = 0;
}

// 2. Ambil data komik, bisa difilter lewat kotak cari (Judul/Author/Genre), status, dan sumber
$f_cari   = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$f_status = isset($_GET['status']) ? $_GET['status'] : '';
$f_sumber = isset($_GET['sumber']) ? $_GET['sumber'] : '';

$syarat = [];
if($f_cari != '') {
    $cari_aman = mysqli_real_escape_string($koneksi, $f_cari);
    // PERBAIKAN: Pencarian terhubung ke Judul, Author, DAN Genre
    $syarat[] = "(judul LIKE '%$cari_aman%' OR author LIKE '%$cari_aman%' OR genre LIKE '%$cari_aman%')";
}
if($f_status != '') {
    $status_aman = mysqli_real_escape_string($koneksi, $f_status);
    $syarat[] = "status = '$status_aman'";
}
if($f_sumber != '') {
    $sumber_aman = mysqli_real_escape_string($koneksi, $f_sumber);
    $syarat[] = "sumber = '$sumber_aman'";
}

$sql = "SELECT * FROM komik";
if(count($syarat) > 0) {
    $sql .= " WHERE " . implode(" AND ", $syarat);
}
$sql .= " ORDER BY id_komik DESC";

$result_komik = mysqli_query($koneksi, $sql);
$jumlah_komik = mysqli_num_rows($result_komik);

// Daftar sumber untuk pilihan filter (diambil dari database)
$daftar_sumber = mysqli_query($koneksi, "SELECT DISTINCT sumber FROM komik ORDER BY sumber");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama - TachiWeb</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <!-- Memanggil Header dari folder components -->
    <?php include 'components/header.php'; ?>

    <div class="container">
        <h3 class="section-title">Latest Updates</h3>

        <!-- Form pencarian dan filter -->
        <form method="GET" action="index.php" class="filter-bar">
            <!-- Placeholder disesuaikan agar pengguna tahu bisa cari genre -->
            <input type="text" name="cari" placeholder="Cari judul, author, atau genre..." value="<?php echo htmlspecialchars($f_cari); ?>">
            <select name="status">
                <option value="">Semua Status</option>
                <option value="Ongoing" <?php if($f_status == 'Ongoing') echo 'selected'; ?>>Ongoing</option>
                <option value="Completed" <?php if($f_status == 'Completed') echo 'selected'; ?>>Completed</option>
            </select>
            <select name="sumber">
                <option value="">Semua Sumber</option>
                <?php while($s = mysqli_fetch_assoc($daftar_sumber)) { ?>
                    <option value="<?php echo htmlspecialchars($s['sumber']); ?>" <?php if($f_sumber == $s['sumber']) echo 'selected'; ?>><?php echo htmlspecialchars($s['sumber']); ?></option>
                <?php } ?>
            </select>
            <button type="submit">🔍 Cari</button>
            <?php if($f_cari != '' || $f_status != '' || $f_sumber != '') { ?>
                <a href="index.php" class="filter-reset">✖ Reset</a>
            <?php } ?>
        </form>

        <?php if($f_cari != '' || $f_status != '' || $f_sumber != '') { ?>
            <p class="filter-info">Menampilkan <?php echo $jumlah_komik; ?> komik</p>
        <?php } ?>

        <?php if($jumlah_komik == 0) { ?>
            <div class="kosong">😕 Komik tidak ditemukan. Coba kata kunci atau filter lain.</div>
        <?php } ?>

        <div class="comic-grid">
            <?php
            // Ambil data komik dari database (while loop)
            while($komik = mysqli_fetch_assoc($result_komik)) {
                $id = $komik['id_komik'];

                // Cek apakah ada cover kustom dari sesi upload admin
                $cover_tampil = $komik['cover'];
                if(isset($_SESSION['custom_cover_' . $id])) {
                    $cover_tampil = $_SESSION['custom_cover_' . $id];
                }

                // Tentukan Bendera dan Style berdasarkan Tipe Komik
                $tipe       = isset($komik['tipe']) ? $komik['tipe'] : 'Manga';
                $bendera    = '🇯🇵';
                $class_tipe = 'type-manga';

                if($tipe == 'Manhwa') {
                    $bendera    = '🇰🇷';
                    $class_tipe = 'type-manhwa';
                } elseif($tipe == 'Manhua') {
                    $bendera    = '🇨🇳';
                    $class_tipe = 'type-manhua';
                }
            ?>
                <!-- Membawa id_komik ke halaman detail.php -->
                <a href="detail.php?id=<?php echo $id; ?>" style="text-decoration: none; color: inherit;">
                    <div class="comic-card">
                        <!-- Badge Kiri Atas: Sumber Aggregator -->
                        <span class="comic-badge"><?php echo htmlspecialchars($komik['sumber']); ?></span>
                        
                        <!-- Badge Kanan Atas: Tipe & Bendera Negara -->
                        <span class="comic-type-badge <?php echo $class_tipe; ?>">
                            <?php echo $bendera . ' ' . htmlspecialchars($tipe); ?>
                        </span>

                        <img src="<?php echo htmlspecialchars($cover_tampil); ?>" alt="Cover <?php echo htmlspecialchars($komik['judul']); ?>">
                        <div class="comic-info">
                            <div class="comic-title"><?php echo htmlspecialchars($komik['judul']); ?></div>
                            <div class="comic-meta">
                                <span><?php echo htmlspecialchars($komik['status']); ?></span>
                            </div>
                        </div>
                    </div>
                </a>
            <?php } ?>
        </div>
    </div>

    <!-- Memanggil Footer dari folder components -->
    <?php include 'components/footer.php'; ?>

</body>
</html>

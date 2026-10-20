<?php
session_start();
include 'config/koneksi.php';

if(!isset($_SESSION['status']) || ($_SESSION['status'] != "login" && $_SESSION['status'] != "guest")){
    header("location:login.php?pesan=akses_ditolak");
    exit;
}

$id_komik = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data detail komik
$query = mysqli_query($koneksi, "SELECT * FROM komik WHERE id_komik = '$id_komik'");
$data  = mysqli_fetch_assoc($query);

if(!$data) {
    $data = [
        "judul" => "Komik Tidak Ditemukan", 
        "author" => "Unknown", 
        "sumber" => "Asura",
        "tipe" => "Manga",
        "genre" => "",
        "cover" => "https://placehold.co/300x450/2c2c2c/bb86fc?text=Not+Found", 
        "sinopsis" => "Maaf, data komik ini tidak tersedia di database."
    ];
}

// Tentukan bendera dan tipe
$tipe    = isset($data['tipe']) ? $data['tipe'] : 'Manga';
$bendera = '🇯🇵';
if($tipe == 'Manhwa') $bendera = '🇰🇷';
elseif($tipe == 'Manhua') $bendera = '🇨🇳';

// Ambil daftar chapter dari database MySQL
$query_chapter = mysqli_query($koneksi, "SELECT * FROM chapter WHERE id_komik = '$id_komik' ORDER BY nomor_chapter DESC");
$total_chapter = mysqli_num_rows($query_chapter);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['judul']); ?> - TachiWeb</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    
    <!-- NAVIGASI: Kembali ke Beranda -->
    <div class="detail-header-nav">
        <a href="index.php">← Kembali ke Beranda</a>
    </div>

    <div class="detail-content">
        <div class="detail-top">
            <div class="detail-cover">
                <img src="<?php echo htmlspecialchars($data['cover']); ?>" alt="Cover <?php echo htmlspecialchars($data['judul']); ?>">
            </div>
            <div class="detail-info">
                <h2><?php echo htmlspecialchars($data['judul']); ?></h2>
                <p><strong>Author:</strong> <?php echo htmlspecialchars($data['author']); ?></p>
                <p><strong>Tipe:</strong> <?php echo $bendera . ' ' . htmlspecialchars($tipe); ?></p>
                <p><strong>Status:</strong> <?php echo isset($data['status']) ? htmlspecialchars($data['status']) : 'Ongoing'; ?></p>
                
                <!-- Tag Genre -->
                <p><strong>Genre:</strong> 
                    <?php 
                    if(!empty($data['genre'])) {
                        $genres = explode(',', $data['genre']);
                        foreach($genres as $g) {
                            $g = trim($g);
                            if(!empty($g)) {
                                echo '<span style="background: #2c2c2c; border: 1px solid #444; color: #bb86fc; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; margin-right: 5px; display: inline-block; margin-top: 4px;">' . htmlspecialchars($g) . '</span>';
                            }
                        }
                    } else {
                        echo '<span style="color: #888; font-size: 0.85rem;">-</span>';
                    }
                    ?>
                </p>

                <?php if($total_chapter > 0) { ?>
                    <a href="baca.php?id=<?php echo $id_komik; ?>&ch=1" class="btn-read">
                        Mulai Baca Ch. 1
                    </a>
                <?php } else { ?>
                    <button class="btn-read" style="background: #555; cursor: not-allowed;" disabled>Belum Ada Chapter</button>
                <?php } ?>

                <!-- Tombol Tambah ke Library -->
                <form action="actions/tambah_library.php" method="POST" style="margin-top: 10px;">
                    <input type="hidden" name="judul_komik" value="<?php echo htmlspecialchars($data['judul']); ?>">
                    <input type="hidden" name="sumber_komik" value="<?php echo htmlspecialchars($data['sumber']); ?>"> 
                    <input type="hidden" name="cover_komik" value="<?php echo htmlspecialchars($data['cover']); ?>">
                    <input type="hidden" name="id_asli" value="<?php echo $id_komik; ?>">
                    
                    <button type="submit" class="btn-library">
                        ❤️ Tambah ke Library
                    </button>
                </form>

                <?php 
                if(isset($_GET['status'])) {
                    if($_GET['status'] == 'sukses') {
                        echo '<p style="color: #4caf50; font-size: 0.85rem; margin-top: 8px;">Berhasil disimpan ke Library!</p>';
                    } elseif($_GET['status'] == 'sudah_ada') {
                        echo '<p style="color: #ff9800; font-size: 0.85rem; margin-top: 8px;">Komik ini sudah ada di Library Anda.</p>';
                    }
                }
                ?>
            </div>
        </div>

        <div class="detail-section">
            <h3>Sinopsis</h3>
            <p style="color: #b3b3b3; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($data['sinopsis'])); ?></p>
        </div>

        <!-- ================= DAFTAR CHAPTER (MODERN CARD) ================= -->
        <div class="detail-section">
            <h3 style="display: flex; justify-content: space-between; align-items: center;">
                <span>📚 Daftar Chapter (<?php echo $total_chapter; ?>)</span>
            </h3>
            <div class="chapter-list-container">
                <?php 
                if($total_chapter > 0) {
                    while($ch = mysqli_fetch_assoc($query_chapter)) { 
                        $judul_ch  = !empty($ch['judul_chapter']) ? " - " . htmlspecialchars($ch['judul_chapter']) : "";
                        $tgl_rilis = date('d M Y', strtotime($ch['tanggal_rilis']));
                ?>
                    <a href="baca.php?id=<?php echo $id_komik; ?>&ch=<?php echo $ch['nomor_chapter']; ?>" class="chapter-card-item">
                        <div class="chapter-info-left">
                            <span class="chapter-title-text">Chapter <?php echo $ch['nomor_chapter']; ?><?php echo $judul_ch; ?></span>
                            <span class="chapter-date-text"><?php echo $tgl_rilis; ?></span>
                        </div>
                        <div class="chapter-arrow-btn">➔</div>
                    </a>
                <?php 
                    } 
                } else {
                    echo '<p style="color: #888; font-size: 0.9rem; text-align: center; padding: 20px 0;">Belum ada chapter yang diunggah untuk komik ini.</p>';
                }
                ?>
            </div>
        </div>

        <!-- ================= KOMENTAR SECTION ================= -->
        <?php
        $id_komik_saat_ini = $id_komik;
        $query_komentar   = mysqli_query($koneksi, "SELECT komentar.*, users.username, users.nama_panggilan FROM komentar JOIN users ON komentar.id_user = users.id_user WHERE komentar.id_komik = '$id_komik_saat_ini' ORDER BY komentar.id_komentar DESC");
        $is_admin         = (isset($_SESSION['username']) && $_SESSION['username'] == 'admin');
        $id_user_sess     = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : (isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0);
        ?>
        <div class="komentar-section" style="margin-top: 40px; padding: 20px; background: #1e1e1e; border-radius: 8px;">
            <h3 style="margin-bottom: 15px;">💬 Komentar Pembaca</h3>
            
            <?php if(isset($_SESSION['status']) && $_SESSION['status'] == 'login') { ?>
                <form action="actions/tambah_komentar.php" method="POST" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 25px;">
                    <input type="hidden" name="id_komik" value="<?php echo $id_komik_saat_ini; ?>">
                    <textarea name="isi_komentar" rows="3" placeholder="Tulis pendapatmu tentang komik ini..." required style="padding: 12px; border-radius: 6px; background: #2c2c2c; color: white; border: 1px solid #444; width: 100%; font-family: inherit; resize: vertical;"></textarea>
                    <button type="submit" style="align-self: flex-end; padding: 10px 20px; background: #bb86fc; color: #121212; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">Kirim Komentar</button>
                </form>
            <?php } else { ?>
                <div style="background: #2c2c2c; padding: 15px; border-radius: 6px; margin-bottom: 25px; text-align: center; color: #aaa;">
                    Silakan <a href="login.php" style="color: #bb86fc; font-weight:bold; text-decoration:none;">Login</a> terlebih dahulu untuk ikut berdiskusi.
                </div>
            <?php } ?>

            <div class="komentar-list" style="display: flex; flex-direction: column; gap: 15px;">
                <?php 
                if($query_komentar && mysqli_num_rows($query_komentar) > 0) {
                    while($kom = mysqli_fetch_assoc($query_komentar)){ 
                        $nama_tampil = !empty($kom['nama_panggilan']) ? $kom['nama_panggilan'] : $kom['username'];
                        $is_owner   = ($kom['id_user'] == $id_user_sess);
                ?>
                    <div style="background: #2c2c2c; padding: 15px; border-radius: 8px; border-left: 4px solid #bb86fc;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <div style="font-size: 0.9rem; color: #bb86fc; font-weight: bold;">
                                <?php echo htmlspecialchars($nama_tampil); ?> 
                                <span style="color: #888; font-weight: normal; font-size: 0.75rem; margin-left: 5px;">• <?php echo date('d M Y H:i', strtotime($kom['waktu'])); ?></span>
                            </div>

                            <?php if($is_admin || $is_owner) { ?>
                                <form action="actions/hapus_komentar.php" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus komentar ini?')" style="margin: 0;">
                                    <input type="hidden" name="id_komentar" value="<?php echo $kom['id_komentar']; ?>">
                                    <input type="hidden" name="id_komik" value="<?php echo $id_komik_saat_ini; ?>">
                                    <button type="submit" style="background: transparent; color: #cf6679; border: none; font-size: 0.75rem; cursor: pointer; font-weight: bold; text-decoration: underline;">🗑️ Hapus</button>
                                </form>
                            <?php } ?>
                        </div>
                        <div style="color: #e0e0e0; font-size: 0.95rem; line-height: 1.5;">
                            <?php echo nl2br(htmlspecialchars($kom['isi_komentar'])); ?>
                        </div>
                    </div>
                <?php 
                    } 
                } else { 
                    echo "<div style='color: #888; text-align: center; padding: 20px 0;'>Belum ada komentar. Jadilah yang pertama!</div>";
                } 
                ?>
            </div>
        </div>

    </div>

</body>
</html>

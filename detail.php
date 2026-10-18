<?php
session_start();
include 'config/koneksi.php';

if(!isset($_SESSION['status']) || ($_SESSION['status'] != "login" && $_SESSION['status'] != "guest")){
    header("location:login.php?pesan=akses_ditolak");
    exit;
}

$id_komik = isset($_GET['id']) ? $_GET['id'] : 0;

// Ambil data detail komik
$query = mysqli_query($koneksi, "SELECT * FROM komik WHERE id_komik = '$id_komik'");
$data  = mysqli_fetch_assoc($query);

if(!$data) {
    $data = [
        "judul" => "Komik Tidak Ditemukan", 
        "author" => "Unknown", 
        "sumber" => "Asura",
        "cover" => "https://placehold.co/300x450/2c2c2c/bb86fc?text=Not+Found", 
        "sinopsis" => "Maaf, data komik ini tidak tersedia di database."
    ];
}

// Ambil daftar chapter dari database MySQL
$query_chapter = mysqli_query($koneksi, "SELECT * FROM chapter WHERE id_komik = '$id_komik' ORDER BY nomor_chapter DESC");
$total_chapter = mysqli_num_rows($query_chapter);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['judul']; ?> - TachiWeb</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    
    <!-- FIX NAVIGASI: Kembali ke Beranda -->
    <div class="detail-header-nav">
        <a href="index.php">← Kembali ke Beranda</a>
    </div>

    <div class="detail-content">
        <div class="detail-top">
            <div class="detail-cover">
                <img src="<?php echo $data['cover']; ?>" alt="Cover <?php echo $data['judul']; ?>">
            </div>
            <div class="detail-info">
                <h2><?php echo $data['judul']; ?></h2>
                <p><strong>Author:</strong> <?php echo $data['author']; ?></p>
                <p><strong>Status:</strong> <?php echo isset($data['status']) ? $data['status'] : 'Ongoing'; ?></p>
                
                <?php if($total_chapter > 0) { ?>
                    <a href="baca.php?id=<?php echo $id_komik; ?>&ch=1" class="btn-read">
                        Mulai Baca Ch. 1
                    </a>
                <?php } else { ?>
                    <button class="btn-read" style="background: #555; cursor: not-allowed;" disabled>Belum Ada Chapter</button>
                <?php } ?>

                <!-- Tombol Tambah ke Library -->
                <form action="actions/tambah_library.php" method="POST" style="margin-top: 10px;">
                    <input type="hidden" name="judul_komik" value="<?php echo $data['judul']; ?>">
                    <input type="hidden" name="sumber_komik" value="<?php echo $data['sumber']; ?>"> 
                    <input type="hidden" name="cover_komik" value="<?php echo $data['cover']; ?>">
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
            <p style="color: #b3b3b3; line-height: 1.6;"><?php echo $data['sinopsis']; ?></p>
        </div>

        <div class="detail-section">
            <h3>Daftar Chapter</h3>
            <div class="chapter-list-container">
                <?php 
                if($total_chapter > 0) {
                    while($ch = mysqli_fetch_assoc($query_chapter)) { 
                ?>
                    <a href="baca.php?id=<?php echo $id_komik; ?>&ch=<?php echo $ch['nomor_chapter']; ?>" class="chapter-item">
                        <span>Chapter <?php echo $ch['nomor_chapter']; ?> <?php if(!empty($ch['judul_chapter'])) echo "- " . $ch['judul_chapter']; ?></span>
                        <span style="color: #888; font-size: 0.8rem;"><?php echo date('d M Y', strtotime($ch['tanggal_rilis'])); ?></span>
                    </a>
                <?php 
                    } 
                } else {
                    echo '<p style="color: #888; font-size: 0.9rem;">Belum ada chapter yang diunggah untuk komik ini.</p>';
                }
                ?>
            </div>
        </div>
    </div>

</body>
</html>
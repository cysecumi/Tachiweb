<?php
session_start();
include 'config/koneksi.php';

// Validasi keamanan sesi
if(!isset($_SESSION['status']) || ($_SESSION['status'] != "login" && $_SESSION['status'] != "guest")){
    header("location:login.php?pesan=akses_ditolak");
    exit;
}

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
$result_library = mysqli_query($koneksi, "SELECT * FROM library WHERE id_user='$id_user'");
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

    <?php include 'components/header.php'; ?>
    
    <div class="container">
        
        <!-- TOMBOL NAVIGASI KEMBALI KE BERANDA -->
        <div style="margin-bottom: 15px;">
            <a href="index.php" style="color: #bb86fc; text-decoration: none; font-size: 0.9rem; font-weight: bold; display: inline-flex; align-items: center; gap: 5px;">
                ← Kembali ke Beranda
            </a>
        </div>

        <h3 class="section-title">Library / Komik Disimpan</h3>
        
        <!-- NOTIFIKASI SUKSES SETELAH MENGHAPUS KOMIK -->
        <?php 
        if(isset($_GET['pesan']) && $_GET['pesan'] == 'hapus_sukses') {
        ?>
            <div style="background-color: #1e1e1e; border-left: 4px solid #4caf50; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <span style="color: #4caf50; font-size: 0.9rem; font-weight: bold;">
                    ✅ Komik berhasil dihapus dari Library Anda!
                </span>
                <a href="index.php" style="background: #bb86fc; color: #121212; padding: 6px 14px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 0.8rem;">
                    🏠 Kembali ke Beranda
                </a>
            </div>
        <?php 
        }
        ?>

        <?php if(mysqli_num_rows($result_library) > 0) { ?>
            <div class="comic-grid">
                <?php while($komik = mysqli_fetch_assoc($result_library)) { ?>
                    <div class="comic-card">
                        <span class="comic-badge"><?php echo $komik['sumber']; ?></span>
                        <img src="<?php echo $komik['cover']; ?>" alt="Cover <?php echo $komik['judul_komik']; ?>">
                        <div class="comic-info">
                            <div class="comic-title"><?php echo $komik['judul_komik']; ?></div>
                            
                            <!-- FORM TOMBOL HAPUS DARI LIBRARY -->
                            <form action="actions/hapus_library.php" method="POST" style="margin-top: 8px;">
                                <input type="hidden" name="id_library" value="<?php echo $komik['id_library']; ?>">
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus komik ini dari Library?')" style="background: #cf6679; border: none; color: white; padding: 6px 10px; border-radius: 4px; font-size: 0.75rem; cursor: pointer; width: 100%; font-weight: bold;">
                                    🗑️ Hapus dari Library
                                </button>
                            </form>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <!-- TAMPILAN JIKA RAK BUKU KOSONG -->
            <div style="text-align: center; padding: 50px 20px; background: #1e1e1e; border-radius: 8px; margin-top: 10px;">
                <p style="font-size: 1.1rem; color: #fff; margin-bottom: 8px;">Rak buku Anda masih kosong.</p>
                <p style="font-size: 0.9rem; color: #888; margin-bottom: 20px;">Belum ada komik yang ditambahkan ke library.</p>
                <a href="index.php" style="display: inline-block; text-decoration: none; background: #bb86fc; color: #121212; font-weight: bold; padding: 10px 20px; border-radius: 6px; font-size: 0.9rem;">
                    🏠 Kembali ke Beranda / Cari Komik
                </a>
            </div>
        <?php } ?>
    </div>

    <?php include 'components/footer.php'; ?>
</body>
</html>
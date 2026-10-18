<?php
session_start();
// 1. Panggil koneksi database yang ada di folder config/
include 'config/koneksi.php';

// Cek apakah pengguna sudah login ATAU sebagai tamu
if(!isset($_SESSION['status']) || ($_SESSION['status'] != "login" && $_SESSION['status'] != "guest")){
    header("location:login.php?pesan=akses_ditolak");
    exit;
}

// 2. Ambil data langsung dari tabel komik di database MySQL
$result_komik = mysqli_query($koneksi, "SELECT * FROM komik");
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
        <h3 class="section-title">Latest Updates (Database MySQL)</h3>
        
        <div class="comic-grid">
            <?php 
            // 3. Ubah perulangan menjadi mengambil data dari database (while loop)
            while($komik = mysqli_fetch_assoc($result_komik)) { 
                $id = $komik['id_komik']; // Mengambil ID unik dari database
                
                // Cek apakah ada cover kustom dari sesi upload admin
                $cover_tampil = $komik['cover'];
                if(isset($_SESSION['custom_cover_' . $id])) {
                    $cover_tampil = $_SESSION['custom_cover_' . $id];
                }
            ?>
                <!-- Membawa id_komik ke halaman detail.php -->
                <a href="detail.php?id=<?php echo $id; ?>" style="text-decoration: none; color: inherit;">
                    <div class="comic-card">
                        <span class="comic-badge"><?php echo $komik['sumber']; ?></span>
                        <img src="<?php echo $cover_tampil; ?>" alt="Cover <?php echo $komik['judul']; ?>">
                        <div class="comic-info">
                            <div class="comic-title"><?php echo $komik['judul']; ?></div>
                            <div class="comic-meta">
                                <span><?php echo $komik['status']; ?></span>
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
<?php
session_start();
include 'config/koneksi.php';

// cuma admin yang boleh masuk
if(!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("location: login.php?pesan=akses_ditolak");
    exit;
}

$id_komik = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// ambil data komik, kalau tidak ada balik ke dashboard
$q_komik = mysqli_query($koneksi, "SELECT * FROM komik WHERE id_komik = $id_komik");
$komik   = mysqli_fetch_assoc($q_komik);
if(!$komik) {
    header("location: admin.php");
    exit;
}

// ambil daftar chapter beserta jumlah halamannya
$q_chapter = mysqli_query($koneksi, "SELECT c.*, 
    (SELECT COUNT(*) FROM halaman_komik h WHERE h.id_chapter = c.id_chapter) AS jumlah_halaman 
    FROM chapter c WHERE c.id_komik = $id_komik ORDER BY c.nomor_chapter DESC");

// saran nomor chapter berikutnya
$q_max = mysqli_query($koneksi, "SELECT MAX(nomor_chapter) AS terakhir FROM chapter WHERE id_komik = $id_komik");
$max   = mysqli_fetch_assoc($q_max);
$nomor_saran = (int)$max['terakhir'] + 1;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Chapter - TachiWeb</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .admin-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-top: 20px; }
        .admin-card { background: #1e1e1e; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .admin-card h4 { color: #bb86fc; margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid #333; padding-bottom: 10px; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; font-size: 0.85rem; color: #b3b3b3; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 8px 10px; background: #2c2c2c; border: 1px solid #444; color: #fff; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { background: #bb86fc; color: #121212; border: none; padding: 10px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%; margin-top: 5px; }
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 0.85rem; }
        .admin-table th, .admin-table td { padding: 10px; text-align: left; border-bottom: 1px solid #333; }
        .admin-table th { background: #2c2c2c; color: #bb86fc; }
        .btn-act-edit { background: #03dac6; color: #000; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.75rem; }
        .btn-act-del { background: #cf6679; color: #fff; border: none; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 0.75rem; cursor: pointer; }
        @media (max-width: 768px) { .admin-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <?php include 'components/header.php'; ?>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 class="section-title" style="margin: 0;">📖 Kelola Chapter: <?php echo htmlspecialchars($komik['judul']); ?></h3>
            <a href="admin.php" style="color: #bb86fc; text-decoration: none; font-size: 0.85rem; font-weight: bold;">← Kembali ke Dashboard</a>
        </div>

        <!-- Notifikasi pesan -->
        <?php if(isset($_GET['pesan'])) { ?>
            <div style="background: #2c2c2c; border-left: 4px solid #bb86fc; padding: 10px 15px; border-radius: 4px; margin-bottom: 15px; font-size: 0.85rem; color: #fff;">
                <?php
                $pesan = $_GET['pesan'];
                if($pesan == 'tambah_sukses') echo "✅ Chapter baru berhasil ditambahkan!";
                elseif($pesan == 'hapus_sukses') echo "✅ Chapter berhasil dihapus!";
                elseif($pesan == 'nomor_salah') echo "⚠️ Nomor chapter harus angka 1 atau lebih.";
                elseif($pesan == 'nomor_kembar') echo "⚠️ Nomor chapter itu sudah ada. Pakai nomor lain.";
                elseif($pesan == 'file_kosong') echo "⚠️ Pilih minimal satu gambar halaman.";
                elseif($pesan == 'file_tidak_valid') echo "⚠️ Ada file yang bukan gambar (jpg/png/webp) atau lebih dari 5MB per file.";
                else echo "⚠️ Gagal menyimpan chapter, coba lagi.";
                ?>
            </div>
        <?php } ?>

        <div class="admin-grid">
            <!-- Form tambah chapter -->
            <div class="admin-card">
                <h4>➕ Tambah Chapter Baru</h4>
                <form action="actions/tambah_chapter.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_komik" value="<?php echo $id_komik; ?>">
                    <div class="form-group">
                        <label>Nomor Chapter</label>
                        <input type="number" name="nomor_chapter" min="1" value="<?php echo $nomor_saran; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Judul Chapter (boleh kosong)</label>
                        <input type="text" name="judul_chapter" placeholder="Contoh: Awal Pertemuan">
                    </div>
                    <div class="form-group">
                        <label>Gambar Halaman (pilih banyak, maks 5MB per file)</label>
                        <input type="file" name="halaman[]" accept="image/*" multiple required>
                    </div>
                    <p style="font-size: 0.75rem; color: #888; margin: 0 0 10px;">Halaman diurutkan otomatis dari nama file (1.jpg, 2.jpg, 10.jpg, dst).</p>
                    <button type="submit" class="btn-submit">💾 Simpan Chapter</button>
                </form>
            </div>

            <!-- Tabel daftar chapter -->
            <div class="admin-card" style="overflow-x: auto;">
                <h4>📚 Daftar Chapter (<?php echo mysqli_num_rows($q_chapter); ?>)</h4>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Ch.</th>
                            <th>Judul</th>
                            <th>Halaman</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($q_chapter) > 0) { ?>
                            <?php while($ch = mysqli_fetch_assoc($q_chapter)) { ?>
                                <tr>
                                    <td><strong><?php echo $ch['nomor_chapter']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($ch['judul_chapter']); ?></td>
                                    <td><?php echo $ch['jumlah_halaman']; ?></td>
                                    <td>
                                        <div style="display: flex; gap: 5px; align-items: center;">
                                            <a href="baca.php?id=<?php echo $id_komik; ?>&ch=<?php echo $ch['nomor_chapter']; ?>" class="btn-act-edit">Baca</a>
                                            <form action="actions/hapus_chapter.php" method="POST" onsubmit="return confirm('Hapus chapter ini beserta semua halamannya?')">
                                                <input type="hidden" name="id_chapter" value="<?php echo $ch['id_chapter']; ?>">
                                                <input type="hidden" name="id_komik" value="<?php echo $id_komik; ?>">
                                                <button type="submit" class="btn-act-del">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr><td colspan="4" style="color: #888; text-align: center;">Belum ada chapter. Tambahkan lewat form di samping.</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>
</body>
</html>
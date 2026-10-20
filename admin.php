<?php
session_start();
include 'config/koneksi.php';

// Validasi Keamanan Strict: Hanya Akun Admin yang Boleh Mengakses
if(!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("location: login.php?pesan=akses_ditolak");
    exit;
}

// Ambil data komik dari database
$result_komik = mysqli_query($koneksi, "SELECT * FROM komik ORDER BY id_komik DESC");

// Ambil data pengguna dari database (Kecuali akun admin)
$result_users = mysqli_query($koneksi, "SELECT * FROM users WHERE username != 'admin' ORDER BY id_user DESC");

// Ambil data feedback
$query_feedback = mysqli_query($koneksi, "SELECT feedback.*, users.username, users.nama_panggilan FROM feedback JOIN users ON feedback.id_user = users.id_user ORDER BY feedback.id_feedback DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - TachiWeb</title>
    
    <link rel="stylesheet" href="assets/style.css">
    
    <style>
        .admin-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-top: 20px; }
        .admin-card { background: #1e1e1e; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .admin-card h4 { color: #bb86fc; margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid #333; padding-bottom: 10px; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; font-size: 0.85rem; color: #b3b3b3; margin-bottom: 5px; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 8px 10px; background: #2c2c2c; border: 1px solid #444; color: #fff; border-radius: 4px; box-sizing: border-box;
        }
        .btn-submit { background: #bb86fc; color: #121212; border: none; padding: 10px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%; margin-top: 5px; }
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 0.85rem; }
        .admin-table th, .admin-table td { padding: 10px; text-align: left; border-bottom: 1px solid #333; }
        .admin-table th { background: #2c2c2c; color: #bb86fc; }
        .admin-table img { width: 45px; height: 60px; object-fit: cover; border-radius: 4px; }
        .btn-act-edit { background: #03dac6; color: #000; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.75rem; }
        .btn-act-del { background: #cf6679; color: #fff; border: none; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 0.75rem; cursor: pointer; }
        .btn-act-reset { background: #ffb74d; color: #000; border: none; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 0.75rem; cursor: pointer; }
        @media (max-width: 768px) { .admin-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <?php include 'components/header.php'; ?>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 class="section-title" style="margin: 0;">⚙️ Dashboard Kelola Data Admin</h3>
            <a href="index.php" style="color: #bb86fc; text-decoration: none; font-size: 0.85rem; font-weight: bold;">← Kembali ke Beranda</a>
        </div>

        <!-- Notifikasi Pesan -->
        <?php if(isset($_GET['pesan'])) { ?>
            <div style="background: #2c2c2c; border-left: 4px solid #bb86fc; padding: 10px 15px; border-radius: 4px; margin-bottom: 15px; font-size: 0.85rem; color: #fff;">
                <?php 
                if($_GET['pesan'] == 'tambah_sukses') echo "✅ Komik baru berhasil ditambahkan!";
                elseif($_GET['pesan'] == 'edit_sukses') echo "✅ Data komik berhasil diperbarui!";
                elseif($_GET['pesan'] == 'hapus_sukses') echo "✅ Komik berhasil dihapus dari database!";
                elseif($_GET['pesan'] == 'hapus_user_sukses') echo "✅ Akun pembaca berhasil dihapus dari sistem!";
                elseif($_GET['pesan'] == 'reset_sukses') echo "🔑 Kata sandi pengguna berhasil di-reset menjadi '123456'!";
                elseif($_GET['pesan'] == 'hapus_feedback_sukses') echo "🗑️ Feedback pengguna berhasil dihapus!";
                ?>
            </div>
        <?php } ?>

        <div class="admin-grid">
            <!-- Form Tambah Komik Baru -->
            <div class="admin-card">
                <h4>➕ Tambah Komik Baru</h4>
                <form action="actions/tambah_komik.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Judul Komik</label>
                        <input type="text" name="judul" required placeholder="Contoh: Solo Leveling">
                    </div>
                    <div class="form-group">
                        <label>Author / Penulis</label>
                        <input type="text" name="author" required placeholder="Contoh: Chugong">
                    </div>
                    <div class="form-group">
                        <label>Sumber Aggregator</label>
                        <input type="text" name="sumber" required placeholder="Contoh: Asura, Kiryuu, Komikcast">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="Ongoing">Ongoing</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tipe Komik</label>
                        <select name="tipe">
                            <option value="Manga">🇯🇵 Manga (Jepang)</option>
                            <option value="Manhwa">🇰🇷 Manhwa (Korea)</option>
                            <option value="Manhua">🇨🇳 Manhua (China)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Genre (Pisahkan dengan koma)</label>
                        <input type="text" name="genre" placeholder="Contoh: Action, Adventure, Fantasy">
                    </div>
                    <div class="form-group">
                        <label>File Cover Gambar (Maks 2MB)</label>
                        <input type="file" name="cover" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label>Sinopsis</label>
                        <textarea name="sinopsis" rows="4" required placeholder="Tuliskan sinopsis ringkas komik..."></textarea>
                    </div>
                    <button type="submit" class="btn-submit">💾 Simpan Komik Baru</button>
                </form>
            </div>

            <!-- Tabel Daftar Komik & Pengguna -->
            <div>
                <!-- Tabel Kelola Komik -->
                <div class="admin-card" style="overflow-x: auto;">
                    <h4>📚 Kelola Data Komik (<?php echo mysqli_num_rows($result_komik); ?>)</h4>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Cover</th>
                                <th>Judul & Author</th>
                                <th>Sumber</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result_komik)) { ?>
                                <tr>
                                    <td><img src="<?php echo $row['cover']; ?>" alt="Cover"></td>
                                    <td>
                                        <strong><?php echo $row['judul']; ?></strong><br>
                                        <span style="color: #888; font-size: 0.75rem;"><?php echo $row['author']; ?></span>
                                    </td>
                                    <td><span class="comic-badge" style="position: static; font-size: 0.7rem;"><?php echo $row['sumber']; ?></span></td>
                                    <td>
                                        <div style="display: flex; gap: 5px; align-items: center;">
                                            <a href="edit_komik.php?id=<?php echo $row['id_komik']; ?>" class="btn-act-edit">Edit</a>
                                            <a href="kelola_chapter.php?id=<?php echo $row['id_komik']; ?>" class="btn-act-edit" style="background:#bb86fc;">Chapter</a>

                                            <form action="actions/hapus_komik.php" method="POST" onsubmit="return confirm('Hapus komik ini dari database?')">
                                                <input type="hidden" name="id_komik" value="<?php echo $row['id_komik']; ?>">
                                                <button type="submit" class="btn-act-del">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Tabel Kelola Akun Pembaca -->
                <div class="admin-card" style="overflow-x: auto;">
                    <h4>👥 Kelola Akun Pembaca (<?php echo mysqli_num_rows($result_users); ?>)</h4>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Nama Panggilan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(mysqli_num_rows($result_users) > 0) {
                                while($user = mysqli_fetch_assoc($result_users)) { 
                            ?>
                                <tr>
                                    <td>#<?php echo $user['id_user']; ?></td>
                                    <td><strong><?php echo $user['username']; ?></strong></td>
                                    <td><?php echo $user['nama_panggilan']; ?></td>
                                    <td>
                                        <div style="display: flex; gap: 5px; align-items: center;">
                                            <form action="actions/reset_password_user.php" method="POST" onsubmit="return confirm('Reset kata sandi akun ini menjadi 123456?')">
                                                <input type="hidden" name="id_user" value="<?php echo $user['id_user']; ?>">
                                                <button type="submit" class="btn-act-reset">🔑 Reset Pass</button>
                                            </form>

                                            <form action="actions/hapus_user.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pembaca ini?')">
                                                <input type="hidden" name="id_user" value="<?php echo $user['id_user']; ?>">
                                                <button type="submit" class="btn-act-del">🗑️ Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php 
                                } 
                            } else {
                                echo '<tr><td colspan="4" style="color: #888; text-align: center;">Belum ada akun pembaca terdaftar.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= FEEDBACK SECTION ================= -->
        <div class="admin-card" style="margin-top: 20px; border-left: 4px solid #cf6679;">
            <h4 style="color: #cf6679;">📥 Kotak Masuk Feedback & Laporan Pengguna</h4>
            
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <?php 
                if($query_feedback && mysqli_num_rows($query_feedback) > 0) {
                    while($fb = mysqli_fetch_assoc($query_feedback)) { 
                        $nama_pengirim = !empty($fb['nama_panggilan']) ? $fb['nama_panggilan'] : $fb['username'];
                ?>
                    <div style="background: #2c2c2c; padding: 15px; border-radius: 8px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                            <div style="font-size: 0.9rem; color: #cf6679; font-weight: bold;">
                                👤 <?php echo htmlspecialchars($nama_pengirim); ?> 
                                <span style="color: #888; font-weight: normal; font-size: 0.75rem; margin-left: 5px;">• <?php echo date('d M Y H:i', strtotime($fb['waktu'])); ?></span>
                            </div>
                            <form action="actions/hapus_feedback.php" method="POST" onsubmit="return confirm('Hapus feedback ini?')">
                                <input type="hidden" name="id_feedback" value="<?php echo $fb['id_feedback']; ?>">
                                <button type="submit" class="btn-act-del" style="padding: 2px 6px; font-size: 0.7rem;">🗑️ Hapus</button>
                            </form>
                        </div>
                        <div style="color: #e0e0e0; font-size: 0.95rem; line-height: 1.5;">
                            <?php echo nl2br(htmlspecialchars($fb['isi_feedback'])); ?>
                        </div>
                    </div>
                <?php 
                    } 
                } else { 
                    echo "<div style='color: #888; padding: 10px 0;'>Belum ada feedback yang masuk dari pengguna.</div>";
                } 
                ?>
            </div>
        </div>

    </div>

    <?php include 'components/footer.php'; ?>
</body>
</html>

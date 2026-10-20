<?php
session_start();
include 'config/koneksi.php';

if(!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("location: login.php?pesan=akses_ditolak");
    exit;
}

$id_komik = isset($_GET['id']) ? $_GET['id'] : 0;
$query    = mysqli_query($koneksi, "SELECT * FROM komik WHERE id_komik = '$id_komik'");
$data     = mysqli_fetch_assoc($query);

if(!$data) {
    header("location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Komik - TachiWeb</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .edit-box { max-width: 600px; margin: 30px auto; background: #1e1e1e; padding: 25px; border-radius: 8px; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; font-size: 0.85rem; color: #b3b3b3; margin-bottom: 5px; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 8px 10px; background: #2c2c2c; border: 1px solid #444; color: #fff; border-radius: 4px; box-sizing: border-box;
        }
    </style>
</head>
<body>

    <?php include 'components/header.php'; ?>

    <div class="container">
        <div class="edit-box">
            <h3 style="color: #bb86fc; margin-top: 0;">✏️ Edit Data Komik</h3>
            <form action="actions/edit_komik.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_komik" value="<?php echo $data['id_komik']; ?>">

                <div class="form-group">
                    <label>Judul Komik</label>
                    <input type="text" name="judul" value="<?php echo htmlspecialchars($data['judul']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Author / Penulis</label>
                    <input type="text" name="author" value="<?php echo htmlspecialchars($data['author']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Sumber Aggregator</label>
                    <input type="text" name="sumber" value="<?php echo htmlspecialchars($data['sumber']); ?>" required placeholder="Contoh: Asura, Kiryuu, Komikcast">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="Ongoing" <?php if($data['status'] == 'Ongoing') echo 'selected'; ?>>Ongoing</option>
                        <option value="Completed" <?php if($data['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tipe Komik</label>
                    <select name="tipe">
                        <option value="Manga" <?php if(isset($data['tipe']) && $data['tipe'] == 'Manga') echo 'selected'; ?>>🇯🇵 Manga (Jepang)</option>
                        <option value="Manhwa" <?php if(isset($data['tipe']) && $data['tipe'] == 'Manhwa') echo 'selected'; ?>>🇰🇷 Manhwa (Korea)</option>
                        <option value="Manhua" <?php if(isset($data['tipe']) && $data['tipe'] == 'Manhua') echo 'selected'; ?>>🇨🇳 Manhua (China)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Genre (Pisahkan dengan koma)</label>
                    <input type="text" name="genre" value="<?php echo htmlspecialchars($data['genre'] ?? ''); ?>" placeholder="Contoh: Action, Adventure, Fantasy">
                </div>
                <div class="form-group">
                    <label>Cover Saat Ini</label><br>
                    <img src="<?php echo $data['cover']; ?>" width="80" style="border-radius: 4px; margin-bottom: 5px;"><br>
                    <label style="font-size: 0.75rem; color: #888;">Ganti Cover (Biarkan kosong jika tidak diubah):</label>
                    <input type="file" name="cover" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Sinopsis</label>
                    <textarea name="sinopsis" rows="5" required><?php echo htmlspecialchars($data['sinopsis']); ?></textarea>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 15px;">
                    <button type="submit" style="background: #bb86fc; border: none; color: #000; font-weight: bold; padding: 10px; border-radius: 4px; cursor: pointer; flex: 1;">💾 Simpan Perubahan</button>
                    <a href="admin.php" style="background: #444; color: #fff; text-decoration: none; font-weight: bold; padding: 10px; border-radius: 4px; text-align: center; flex: 1;">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>
</body>
</html>

<?php
session_start();
require_once 'config/koneksi.php';

// Proteksi: Pengguna wajib login
if (!isset($_SESSION['id_user']) && !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id_user = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : intval($_SESSION['user_id']);

// Ambil data pengguna berdasarkan id_user
$query = "SELECT * FROM users WHERE id_user = $id_user";
$result = mysqli_query($koneksi, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil - TachiWeb</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <?php include 'components/header.php'; ?>

    <div class="container margin-top" style="max-width: 600px;">
        <h2 style="margin-bottom: 20px; color: #bb86fc;">⚙️ Pengaturan Profil</h2>

        <!-- NOTIFIKASI KHUSUS RESET PASSWORD DARI ADMIN -->
        <?php if (!empty($user['pesan_notifikasi'])): ?>
            <div style="background: #2c2c2c; border-left: 4px solid #ffb74d; padding: 12px 15px; border-radius: 4px; margin-bottom: 20px; font-size: 0.9rem; color: #ffb74d; font-weight: bold;">
                <?= htmlspecialchars($user['pesan_notifikasi']); ?>
            </div>
        <?php endif; ?>

        <!-- Notifikasi Pesan Status -->
        <?php if (isset($_GET['pesan'])): ?>
            <div style="background: #2c2c2c; border-left: 4px solid #bb86fc; padding: 12px 15px; border-radius: 4px; margin-bottom: 20px; font-size: 0.9rem; color: #fff;">
                <?php
                $pesan = $_GET['pesan'];
                if ($pesan == 'nama_sukses') echo "✅ Nama panggilan berhasil diperbarui!";
                elseif ($pesan == 'password_sukses') echo "✅ Kata sandi berhasil diubah!";
                elseif ($pesan == 'password_salah') echo "⚠️ Kata sandi lama yang Anda masukkan salah.";
                elseif ($pesan == 'konfirmasi_salah') echo "⚠️ Konfirmasi kata sandi baru tidak cocok.";
                elseif ($pesan == 'password_pendek') echo "⚠️ Kata sandi baru minimal harus 6 karakter.";
                elseif ($pesan == 'nama_kosong') echo "⚠️ Nama panggilan tidak boleh kosong.";
                else echo "⚠️ Terjadi kesalahan. Silakan coba lagi.";
                ?>
            </div>
        <?php endif; ?>

        <!-- Form 1: Ubah Nama Panggilan -->
        <div style="background: #1e1e1e; padding: 20px; border-radius: 8px; margin-bottom: 25px; border: 1px solid #333;">
            <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 1.1rem; color: #03dac6;">👤 Ubah Profil</h3>
            <form action="actions/ubah_profil.php" method="POST">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 0.85rem; color: #b3b3b3; margin-bottom: 5px;">Username (Tidak dapat diubah)</label>
                    <input type="text" value="<?= htmlspecialchars($user['username']); ?>" disabled 
                           style="width: 100%; padding: 10px; background: #121212; border: 1px solid #444; color: #888; border-radius: 4px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="nama_panggilan" style="display: block; font-size: 0.85rem; color: #b3b3b3; margin-bottom: 5px;">Nama Panggilan</label>
                    <input type="text" name="nama_panggilan" id="nama_panggilan" value="<?= htmlspecialchars($user['nama_panggilan']); ?>" required 
                           style="width: 100%; padding: 10px; background: #2c2c2c; border: 1px solid #444; color: #fff; border-radius: 4px; box-sizing: border-box;">
                </div>

                <button type="submit" style="background: #03dac6; color: #000; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%;">Simpan Perubahan Nama</button>
            </form>
        </div>

        <!-- Form 2: Ganti Password -->
        <div style="background: #1e1e1e; padding: 20px; border-radius: 8px; margin-bottom: 25px; border: 1px solid #333;">
            <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 1.1rem; color: #cf6679;">🔒 Ganti Kata Sandi</h3>
            <form action="actions/ubah_password.php" method="POST">
                <div style="margin-bottom: 15px;">
                    <label for="password_lama" style="display: block; font-size: 0.85rem; color: #b3b3b3; margin-bottom: 5px;">Kata Sandi Saat Ini</label>
                    <input type="password" name="password_lama" id="password_lama" required 
                           style="width: 100%; padding: 10px; background: #2c2c2c; border: 1px solid #444; color: #fff; border-radius: 4px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="password_baru" style="display: block; font-size: 0.85rem; color: #b3b3b3; margin-bottom: 5px;">Kata Sandi Baru (Min. 6 karakter)</label>
                    <input type="password" name="password_baru" id="password_baru" minlength="6" required 
                           style="width: 100%; padding: 10px; background: #2c2c2c; border: 1px solid #444; color: #fff; border-radius: 4px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="konfirmasi_password" style="display: block; font-size: 0.85rem; color: #b3b3b3; margin-bottom: 5px;">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="konfirmasi_password" id="konfirmasi_password" minlength="6" required 
                           style="width: 100%; padding: 10px; background: #2c2c2c; border: 1px solid #444; color: #fff; border-radius: 4px; box-sizing: border-box;">
                </div>

                <button type="submit" style="background: #cf6679; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%;">Ganti Kata Sandi</button>
            </form>
        </div>

        <!-- Section 3: Tombol Pintas Feedback -->
        <div style="background: #1e1e1e; padding: 20px; border-radius: 8px; border: 1px solid #333; text-align: center;">
            <h3 style="margin-top: 0; margin-bottom: 10px; font-size: 1.1rem; color: #bb86fc;">💬 Masukan & Laporan Bug</h3>
            <p style="color: #b3b3b3; font-size: 0.85rem; margin-bottom: 15px;">Punya saran, kendala, atau menemukan bug saat menggunakan TachiWeb?</p>
            <a href="feedback.php" style="display: block; background: #2c2c2c; color: #bb86fc; border: 1px solid #bb86fc; padding: 12px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.9rem; box-sizing: border-box;">
                💬 Kirim Feedback ke Admin
            </a>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>
</body>
</html>

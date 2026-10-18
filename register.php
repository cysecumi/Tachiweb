<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun - TachiWeb</title>
    <style>
        body { font-family: sans-serif; background-color: #121212; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background-color: #1e1e1e; padding: 30px; border-radius: 8px; width: 300px; box-shadow: 0 4px 10px rgba(0,0,0,0.5); }
        .login-box h2 { text-align: center; color: #bb86fc; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: none; border-radius: 4px; box-sizing: border-box; background: #2c2c2c; color: white; }
        button { width: 100%; padding: 10px; background-color: #bb86fc; border: none; color: black; font-weight: bold; border-radius: 4px; cursor: pointer; margin-top: 10px;}
        button:hover { background-color: #9965f4; }
        .alert-error { background-color: #ffebee; color: #c62828; padding: 10px; border-radius: 4px; font-size: 0.85rem; margin-bottom: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Daftar Akun Baru</h2>

        <!-- KODE UNTUK MEMUNCULKAN PERINGATAN / POP-UP ERROR -->
        <?php 
        if(isset($_GET['pesan'])) {
            if($_GET['pesan'] == "username_kembar") {
                echo '<div class="alert-error">Username sudah digunakan! Gunakan yang lain.</div>';
            } elseif($_GET['pesan'] == "gagal") {
                echo '<div class="alert-error">Pendaftaran gagal, coba lagi.</div>';
            }
        }
        ?>

<form action="actions/simpan_register.php" method="POST">
            <input type="text" name="username" placeholder="Username Baru" required>
            <input type="text" name="nama_panggilan" placeholder="Nama Panggilan" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Daftar Sekarang</button>
        </form>
        <div style="text-align: center; margin-top: 15px;">
            <a href="login.php" style="color: #b3b3b3; text-decoration: none; font-size: 0.8rem;">Sudah punya akun? Kembali ke Login</a>
        </div>
    </div>
</body>
</html>
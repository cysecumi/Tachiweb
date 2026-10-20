<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - TachiWeb</title>
    <style>
        body { 
            font-family: sans-serif; 
            background-color: #121212; 
            color: white; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0; 
            padding: 20px;
            box-sizing: border-box;
        }
        .login-box { 
            background-color: #1e1e1e; 
            padding: 30px 20px; 
            border-radius: 12px; 
            width: 100%; 
            max-width: 380px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.5); 
            box-sizing: border-box;
        }
        .login-box h2 { text-align: center; color: #bb86fc; margin-bottom: 20px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: none; border-radius: 6px; box-sizing: border-box; background: #2c2c2c; color: white; font-size: 1rem; }
        button { width: 100%; padding: 12px; background-color: #bb86fc; border: none; color: black; font-weight: bold; border-radius: 6px; cursor: pointer; margin-top: 10px; font-size: 1rem; }
        button:hover { background-color: #9965f4; }
        .alert-error { background-color: #ffebee; color: #c62828; padding: 10px; border-radius: 4px; font-size: 0.85rem; margin-bottom: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Daftar Akun Baru</h2>

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
            <a href="login.php" style="color: #b3b3b3; text-decoration: none; font-size: 0.85rem;">Sudah punya akun? Kembali ke Login</a>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - TachiWeb</title>
    <style>
        body { font-family: sans-serif; background-color: #121212; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background-color: #1e1e1e; padding: 30px; border-radius: 8px; width: 300px; box-shadow: 0 4px 10px rgba(0,0,0,0.5); }
        .login-box h2 { text-align: center; color: #bb86fc; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: none; border-radius: 4px; box-sizing: border-box; background: #2c2c2c; color: white; }
        button { width: 100%; padding: 10px; background-color: #bb86fc; border: none; color: black; font-weight: bold; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #9965f4; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>TachiWeb</h2>
        
        <!-- KODE BERHASIL DIBUNGKUS TAG PHP -->
        <?php 
        if(isset($_GET['pesan']) && $_GET['pesan'] == "daftar_berhasil") {
            echo '<div style="background-color: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 4px; font-size: 0.85rem; margin-bottom: 10px; text-align: center;">Pendaftaran berhasil! Silakan masuk.</div>';
        }
        ?>

      <form action="actions/cek_login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Masuk ke Library</button>
            
            <div style="text-align: center; margin-top: 15px; display: flex; flex-direction: column; gap: 10px;">
                <a href="register.php" style="color: #bb86fc; text-decoration: none; font-size: 0.9rem;">Belum punya akun? Daftar di sini</a>
                <a href="masuk_guest.php" style="color: #b3b3b3; text-decoration: none; font-size: 0.8rem;">Masuk ke Halaman Utama tanpa login</a>
            </div>
        </form>
    </div>
</body>
</html>
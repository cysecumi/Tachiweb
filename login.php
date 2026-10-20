<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TachiWeb</title>
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
        button { width: 100%; padding: 12px; background-color: #bb86fc; border: none; color: black; font-weight: bold; border-radius: 6px; cursor: pointer; font-size: 1rem; margin-top: 5px; }
        button:hover { background-color: #9965f4; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>TachiWeb</h2>
        
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

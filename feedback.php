<?php
session_start();
include 'config/koneksi.php';

if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location:login.php?pesan=akses_ditolak");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Feedback - TachiWeb</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    
    <?php include 'components/header.php'; ?>

    <div class="container" style="max-width: 600px; margin: 40px auto; background: #1e1e1e; padding: 30px; border-radius: 8px;">
        <h2 style="margin-bottom: 10px;">Kirim Feedback</h2>
        <p style="color: #b3b3b3; margin-bottom: 25px;">Ada bug, request fitur, atau saran untuk TachiWeb? Sampaikan langsung ke Admin di sini.</p>

        <?php if(isset($_GET['status']) && $_GET['status'] == 'sukses') { ?>
            <div style="background: #4caf50; color: white; padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: bold;">
                ✓ Terima kasih! Feedback kamu telah terkirim ke Admin.
            </div>
        <?php } ?>

        <form action="actions/tambah_feedback.php" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
            <textarea name="isi_feedback" rows="6" placeholder="Ketik masukanmu di sini..." required style="padding: 15px; border-radius: 6px; background: #2c2c2c; color: white; border: 1px solid #444; width: 100%; font-family: inherit; resize: vertical;"></textarea>
            <button type="submit" style="padding: 12px; background: #bb86fc; color: #121212; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 1rem;">Kirim Feedback</button>
        </form>
    </div>

    <?php include 'components/footer.php'; ?>

</body>
</html>

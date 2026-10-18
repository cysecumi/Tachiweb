<?php
// Pastikan sesi aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil data sesi secara aman
$nama_user = isset($_SESSION['nama_panggilan']) ? $_SESSION['nama_panggilan'] : 'Guest';
$inisial   = substr($nama_user, 0, 1);
$status    = isset($_SESSION['status']) ? $_SESSION['status'] : '';
$username  = isset($_SESSION['username']) ? $_SESSION['username'] : '';
?>
<div class="header">
    <h2><a href="index.php" style="color: inherit; text-decoration: none;">TachiWeb</a></h2>
    
    <!-- BAGIAN DROPDOWN MENU PROFIL -->
    <div class="profile-menu">
        <button class="profile-btn" onclick="toggleMenu()">
            <div class="avatar"><?php echo strtoupper($inisial); ?></div>
            <span class="user-name"><?php echo $nama_user; ?></span>
            <span class="arrow">▼</span>
        </button>
        
        <div class="dropdown-content" id="dropdownProfil">
            <?php if($status == "login") { ?>
                <a href="library.php">Library Saya</a>
                
                <?php if($username == 'admin') { ?>
                    <a href="admin.php" style="color: #bb86fc !important; font-weight: bold;">⚙️ Dashboard Admin</a>
                <?php } ?>
                
                <a href="#">Pengaturan</a>
                <hr>
                <a href="logout.php" class="dropdown-logout">Keluar</a>
            <?php } else { ?>
                <a href="library.php">Library Saya</a>
                <hr>
                <a href="logout.php" class="dropdown-logout" style="color:#bb86fc !important;">Buat Akun / Login</a>
            <?php } ?>
        </div>
    </div>
</div>
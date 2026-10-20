<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['id_user']) && !isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user             = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : intval($_SESSION['user_id']);
    $password_lama       = $_POST['password_lama'];
    $password_baru       = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    if (strlen($password_baru) < 6) {
        header("Location: ../pengaturan.php?pesan=password_pendek");
        exit();
    }

    if ($password_baru !== $konfirmasi_password) {
        header("Location: ../pengaturan.php?pesan=konfirmasi_salah");
        exit();
    }

    // Ambil kata sandi berdasarkan id_user
    $query_user = "SELECT password FROM users WHERE id_user = $id_user";
    $result     = mysqli_query($koneksi, $query_user);
    $user       = mysqli_fetch_assoc($result);

    if ($user) {
        $password_db = $user['password'];
        $is_valid    = false;

        if (password_verify($password_lama, $password_db) || $password_lama === $password_db) {
            $is_valid = true;
        }

        if ($is_valid) {
            $password_hashed = password_hash($password_baru, PASSWORD_BCRYPT);
            // Update password & hapus pesan notifikasi jika ada
            $query_update    = "UPDATE users SET password = '$password_hashed', pesan_notifikasi = NULL WHERE id_user = $id_user";

            if (mysqli_query($koneksi, $query_update)) {
                header("Location: ../pengaturan.php?pesan=password_sukses");
                exit();
            }
        } else {
            header("Location: ../pengaturan.php?pesan=password_salah");
            exit();
        }
    }

    header("Location: ../pengaturan.php?pesan=gagal");
    exit();
}

header("Location: ../pengaturan.php");
exit();
?>

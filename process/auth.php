<?php session_start();

// Pastikan sesi 'users' ada
if (!isset($_SESSION['users']) || empty($_SESSION['users'])) {
    header("Location: ../index.php");
    exit;
}

// Pastikan sesi 'active_nik' ada
if (!isset($_SESSION['active_nik']) || !isset($_SESSION['users'][$_SESSION['active_nik']])) {
    header("Location: ../index.php");
    exit;
}

// Ambil data user aktif
$user = $_SESSION['users'][$_SESSION['active_nik']];
$nik = $_SESSION['users'][$_SESSION['active_nik']]['nik'];
// Fungsi untuk mengecek role
function checkRole($allowedRole) {
    global $user;
    if ($user['role'] !== $allowedRole) {
        header("Location: ../index.php");
        exit;
    }
}
?>
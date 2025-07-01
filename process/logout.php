<?php
session_start();

// Pastikan metode POST dan NIK tersedia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nik'])) {
    $nik = $_POST['nik'];

    // Hapus user berdasarkan NIK dari session
    if (isset($_SESSION['users'][$nik])) {
        unset($_SESSION['users'][$nik]);
    }

    // Jika user yang logout adalah user aktif, pindahkan ke user lain jika masih ada
    if (isset($_SESSION['active_nik']) && $_SESSION['active_nik'] == $nik) {
        $_SESSION['active_nik'] = !empty($_SESSION['users']) ? array_keys($_SESSION['users'])[0] : null;
    }

    // Jika tidak ada user yang tersisa, hapus seluruh sesi
    if (empty($_SESSION['users'])) {
        session_unset();  // Hapus semua variabel sesi
        session_destroy(); // Hancurkan sesi

        // Hapus cookie sesi
        if (ini_get("session.use_cookies")) {
            setcookie(session_name(), '', time() - 42000, '/');
        }

        // Mencegah cache halaman logout
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        header("Pragma: no-cache");

        // Tambahkan skrip untuk menghapus localStorage sebelum redirect
        echo "<script>
                localStorage.clear();
                sessionStorage.clear();
                window.location.href = '../index.php';
              </script>";
        exit;
    }

    // Redirect ke halaman utama setelah logout user tertentu
    echo "<script>
            localStorage.clear();
            sessionStorage.clear();
            window.location.href = '../index.php';
          </script>";
    exit;
} else {
    // Jika request tidak valid, tetap redirect ke login
    echo "<script>
            localStorage.clear();
            sessionStorage.clear();
            window.location.href = '../index.php';
          </script>";
    exit;
}
?>

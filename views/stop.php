<?php
include '../process/auth.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simpan session tertentu
    $users = $_SESSION['users'] ?? null;
    $active_nik = $_SESSION['active_nik'] ?? null;

    $_SESSION = [];

    if ($users !== null) $_SESSION['users'] = $users;
    if ($active_nik !== null) $_SESSION['active_nik'] = $active_nik;

    // Redirect via JavaScript + clear sessionStorage
    echo "
    <script>
        sessionStorage.clear();
        localStorage.clear(); 
        window.location.href = 'system.php?status=stopped';
    </script>";
    exit;
}

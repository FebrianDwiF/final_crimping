<?php
session_start();
include '../db/connection.php';

$role = trim($_POST['role']);
$name = trim($_POST['name']);
$nik = trim($_POST['nik']);

// Validasi input
if (empty($role) || empty($name) || empty($nik)) {
    echo "All fields are required!";
    exit;
}

// Cek user di database
$query = $conn->prepare("SELECT * FROM users WHERE nik = ? AND name = ? AND role = ?");
$query->bind_param("sss", $nik, $name, $role);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    date_default_timezone_set("Asia/Jakarta");

    // Regenerasi session ID untuk keamanan
    session_regenerate_id(true);

    // Jika session 'users' belum ada, buat array baru
    if (!isset($_SESSION['users'])) {
        $_SESSION['users'] = [];
    }

    // Simpan informasi user dalam array berdasarkan NIK
    $_SESSION['users'][$nik] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'nik' => $user['nik'],
        'role' => $user['role'],
        'loginTime' => date("l, d F Y, H:i:s")
    ];

    // Simpan nik terakhir yang login
    $_SESSION['active_nik'] = $nik;

    // Redirect sesuai role tanpa menggunakan GET
    if ($role === 'operator') {
        header("Location: ../views/operator.php");
    } elseif ($role === 'teknisi') {
        header("Location: ../views/teknisi.php");
    }
    exit;
} else {
    $_SESSION['error'] = "Invalid credentials!.";
    header("Location: ../index.php");
    exit;
}
?>
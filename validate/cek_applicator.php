<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();
include '../db/connection.php';

// Set header as JSON
header('Content-Type: application/json');

// Debugging: Cek session dan POST
// Pastikan var_dump tidak mengganggu pengiriman JSON
error_log('Session mesin: ' . var_export($_SESSION['mesin'], true)); // log session
error_log('Session applicators: ' . var_export($_SESSION['applicators'], true)); // log applicators
error_log('POST data: ' . var_export($_POST, true)); // log POST data

$man = $_SESSION['mesin'] ?? ''; // Mesin dari session
$applicators = $_SESSION['applicators'] ?? []; // Applicators dari session
$app = $_POST['applicator'] ?? ''; // Applicator dari POST

// Validasi data session dan POST
if (!$app || !$man) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Data tidak lengkap atau session mesin tidak ditemukan.'
    ]);
    exit;
}

// Persiapkan statement SQL untuk pengecekan applicator dan mesin
$stmt = $mysqli->prepare("SELECT * FROM data_stroke WHERE applicator = ? AND mesin = ?");
$stmt->bind_param("ss", $app, $man);
$stmt->execute();
$result = $stmt->get_result();

// Cek hasil query
if ($result->num_rows === 0) {
    echo json_encode([
        'status' => 'error',
        'message' => "Applicator '$app' tidak cocok dengan mesin '$man'."
    ]);
} else {
    echo json_encode([
        'status' => 'ok'
    ]);
}

// Tutup statement
$stmt->close();
?>
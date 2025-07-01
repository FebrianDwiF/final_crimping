<?php
session_start();
include '../db/connection.php';
header('Content-Type: application/json');

// Ambil data dari request
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data)) {
    // Simpan data ke session agar tidak hilang saat redirect
    if (!isset($_SESSION['saved_data'])) {
        $_SESSION['saved_data'] = [];
    }
    $_SESSION['saved_data'][] = $data;

    // Pastikan koneksi database tersedia
    if (!$conn) {
        echo json_encode(["message" => "Koneksi database gagal"]);
        exit();
    }

    // Persiapkan query SQL dengan parameterized query untuk keamanan
    $stmt = $conn->prepare("INSERT INTO data_lko 
        (user,carline, mesin, time, shift,ctrl_no, noIssue, scanKanban, qty, kind, size, col, terminal, lotTerminal, f_c_h, r_c_h, f_c_w, r_c_w, c_l, kodeDefect, qtyM,code_error, downtime) 
        VALUES (?,?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?)");

    if (!$stmt) {
        echo json_encode(["message" => "Gagal menyiapkan statement SQL"]);
        exit();
    }

    $stmt->bind_param("ssssssssissssssssssssss",
        $data['name'],
        $data['carline'],
        $data['mesin'],
        $data['time'],
        $data['shift'],
        $data['ctrl_no'],
        $data['noIssue'],
        $data['scanKanban'],
        $data['qty'],
        $data['kind'],
        $data['size'],
        $data['col'],
        $data['terminal'],
        $data['lotTerminal'],
        $data['f_c_h'],
        $data['r_c_h'],
        $data['f_c_w'],
        $data['r_c_w'],
        $data['c_l'],
        $data['kodeDefect'],
        $data['qtyM'],
        $data['codeError'],
        $data['downtime']
    );

    if ($stmt->execute()) {
        echo json_encode(["message" => "Data berhasil disimpan"]);
    } else {
        echo json_encode(["message" => "Gagal menyimpan data: " . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
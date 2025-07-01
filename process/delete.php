<?php
include '../db/connection.php';

// Pastikan parameter dikirim
if (isset($_GET['table'], $_GET['key'], $_GET['value'])) {
    $table = $_GET['table'];    // Nama tabel
    $key = $_GET['key'];        // Primary Key ('id' atau 'no')
    $value = $_GET['value'];    // Nilai primary key (angka atau string)

    // Daftar tabel dan kunci yang diperbolehkan
    $allowed_tables = [
        'data_cfm' => 'id',
        'data_crimping' => 'no',
        'data_kanban' => 'id', // Contoh tabel yang menggunakan 'no' sebagai primary key
        'data_stroke' => 'no', 
        'users' => 'id',
        'defect' => 'no',
        'downtime' => 'id',
    ];

    // Validasi: Pastikan tabel dan key sesuai dengan daftar yang diperbolehkan
    if (!array_key_exists($table, $allowed_tables) || $allowed_tables[$table] !== $key) {
        echo "<script>alert('Unauthorized table or key!'); window.location.href = '../index.php';</script>";
        exit;
    }


    // Siapkan query DELETE
    $stmt = $conn->prepare("DELETE FROM $table WHERE $key = ?");
    $stmt->bind_param("s", $value); 

    if ($stmt->execute()) {
        $stmt->close(); // 🔥 Pindahkan sebelum redirect
        $conn->close(); // 🔥 Tutup koneksi sebelum exit
        header("Location: ../views/teknisi.php?success=deleted");
        exit;
    } else {
        $stmt->close();
        $conn->close();
        header("Location: ../views/teknisi.php?error=failed");
        exit;
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>

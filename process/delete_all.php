<?php
include '../db/connection.php'; // pastikan file koneksi database sesuai
if (isset($_GET['table'])) {
    $table = $_GET['table'];
    // Hapus semua data dari tabel
    $sql = "TRUNCATE TABLE `$table`";
    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/teknisi.php?message=All data deleted successfully");
        exit;
    } else {
        echo "Error deleting all data: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
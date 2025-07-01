<?php
include '../db/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $allowed_tables = ['downtime', 'users', 'defect']; // whitelist tabel yang boleh diisi
    $table = $_POST['table'] ?? '';

    if (!in_array($table, $allowed_tables)) {
        die("Tabel tidak diizinkan!");
    }

    // Ambil semua field selain "table" dan "aut"
    $fields = [];
    $values = [];
    $types = ''; // untuk bind_param
    
    foreach ($_POST as $key => $value) {
        if (in_array($key, ['table', 'aut'])) continue;
        $fields[] = $key;
        $values[] = $value; 
        $types .= 's'; // asumsikan string
    }

    if (count($fields) > 0) {
        $columns = implode(', ', $fields);
        $placeholders = implode(', ', array_fill(0, count($fields), '?'));
        $sql = "INSERT INTO `$table` ($columns) VALUES ($placeholders)";
        
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param($types, ...$values);
            if ($stmt->execute()) {
                header("Location: ../views/teknisi.php?success=1");
                exit();
            } else {
                echo "Gagal menyimpan: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Query error: " . $conn->error;
        }
    } else {
        echo "Tidak ada data yang dikirim!";
    }

    $conn->close();
}
?>

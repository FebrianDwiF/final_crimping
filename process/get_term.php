<?php

include '../db/connection.php'; // Pastikan path ini sesuai struktur proyek Anda
session_start();
$response = [];

// Header untuk JSON
header('Content-Type: application/json');
ob_start(); // Menangkap output yang tidak diinginkan
$man = $_SESSION['mesin'];
try {
    // Periksa apakah parameter applicator atau term ada
    if (empty($_GET['applicator']) && empty($_GET['term'])) {
        $response['error'] = "Parameter applicator atau term harus diisi.";
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    $applicator = trim($_GET['applicator'] ?? '');
    $term = trim($_GET['term'] ?? '');
    $man = $_SESSION['mesin'] ?? ''; // Ambil mesin dari session

    // Validasi mesin
    if (empty($man)) {
        $response['error'] = "Nomor mesin tidak ditemukan di session.";
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Daftar tabel dan kolom yang relevan
    $tables = [
        'data_kanban' => ['machine', 'npg', 'noproc', 'ctrl_no', 'kind', 'size', 'col', 'c_l', 'term_b', 'strip_b', 'half_strip_b', 'man_b', 'acc_b1', 'term_a', 'strip_a', 'half_strip_a', 'man_a', 'acc_a1', 'qty'],
        'data_cfm' => ['carline', 'mesin', 'no', 'applicator', 'man_no', 'kind', 'size', 'knop_spacer', 'dial', 'no_prog'],
        'data_crimping' => ['mesin', 'term', 'wire', 'size', 'acc', 'f_c_h', 'toleransi1', 'r_c_h', 'toleransi2', 'f_c_w_min', 'r_c_w_min', 'f_c_w_max', 'r_c_w_max'],
        'data_stroke' => ['no', 'carline', 'mesin', 'applicator', 'max_stroke', 'current_stroke'],
    ];

    // Inisialisasi variabel untuk mengecek apakah ada data yang ditemukan
    $foundData = false;

    foreach ($tables as $table => $columns) {
        // Pilih kolom yang akan diambil dari tabel
        $columnsList = implode(", ", $columns);
        $query = "SELECT $columnsList FROM $table WHERE 1=1";
        $params = [];
        $types = "";

        // Tambahkan kondisi applicator jika kolom tersebut ada di tabel
        if (in_array('applicator', $columns) && !empty($applicator)) {
            $query .= " AND applicator = ?";
            $params[] = $applicator;
            $types .= "s";
        }

        // Tambahkan kondisi term jika kolom tersebut ada di tabel
        if (in_array('term', $columns) && !empty($term)) {
            $query .= " AND term = ?";
            $params[] = $term;
            $types .= "s";
        }

        // Tambahkan kondisi mesin jika kolom tersebut ada di tabel
        if (in_array('mesin', $columns) && !empty($man)) {
            $query .= " AND mesin = ?";
            $params[] = $man;
            $types .= "s";
        }

        // Logging untuk debugging
        error_log("Query: $query");
        error_log("Parameters: " . json_encode($params));

        // Persiapkan statement
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            throw new Exception("Error preparing query: " . $conn->error);
        }

        // Bind parameter jika ada
        if (!empty($params)) {
            if (!$stmt->bind_param($types, ...$params)) {
                throw new Exception("Error binding parameters: " . $stmt->error);
            }
        }

        // Eksekusi query
        if (!$stmt->execute()) {
            throw new Exception("Error executing query: " . $stmt->error);
        }

        // Ambil hasil
        $result = $stmt->get_result();
        $tableData = [];
        while ($row = $result->fetch_assoc()) {
            $tableData[] = $row;
            $foundData = true; // Jika ada data, tandai sebagai ditemukan
        }
        
        // Simpan hasil query
        $response[$table] = $tableData;

        // Jika tabel bukan data_kanban, simpan di session
        if ($table !== 'data_kanban' && $foundData) {
            $_SESSION['filtered_applicator'][$table] = $tableData;
        }

        // Tutup statement
        $stmt->close();
    }

    // Jika tidak ada data yang ditemukan di semua tabel, kirim pesan error
    if (!$foundData) {
        $response = ['error' => "Data tidak ditemukan."];
    }
} catch (Exception $e) {
    // Tangkap error dan tambahkan ke respons
    $response['error'] = $e->getMessage();
} finally {
    // Tangkap output yang tidak diinginkan
    $output = ob_get_clean();
    if (!empty($output)) {
        error_log("Unexpected output: " . $output); // Log output yang tidak diinginkan
    }

    // Kembalikan respons sebagai JSON
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}
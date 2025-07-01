<?php
// Ambil parameter 'term'
session_start();
include '../db/connection.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Periksa koneksi database
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Gagal terhubung ke database']);
    exit;
}


$term = trim($_GET['term'] ?? $_SESSION['term'] ?? '');

if (empty($term)) {
    echo json_encode(['success' => false, 'message' => 'Parameter term harus diisi']);
    exit;
}

try {
    $query = "SELECT 
                f_c_h, toleransi1, 
                r_c_h, toleransi2, 
                f_c_w_min, f_c_w_max, 
                r_c_w_min, r_c_w_max
              FROM data_crimping
              WHERE term = ?";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Gagal mempersiapkan query: " . $conn->error);
    }

    $stmt->bind_param("s", $term);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        // Pastikan semua nilai tidak NULL sebelum dikonversi
        foreach ($row as $key => $value) {
            if (is_null($value)) {
                echo json_encode(['success' => false, 'message' => "Nilai $key dalam database NULL"]);
                exit;
            }
        }

        // Debugging tambahan
        error_log("Query Result: " . json_encode($row));

        echo json_encode([
            'success' => true,
            'ranges' => [
                'f_c_h' => [
                    'min' => (float)$row['f_c_h'] - (float)$row['toleransi1'],
                    'max' => (float)$row['f_c_h'] + (float)$row['toleransi1']
                ],
                'f_c_w' => [
                    'min' => (float)$row['f_c_w_min'],
                    'max' => (float)$row['f_c_w_max']
                ],
                'r_c_w' => [
                    'min' => (float)$row['r_c_w_min'],
                    'max' => (float)$row['r_c_w_max']
                ],
                'r_c_h' => [
                    'min' => (float)$row['r_c_h'] - (float)$row['toleransi2'],
                    'max' => (float)$row['r_c_h'] + (float)$row['toleransi2']
                ]
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan untuk term yang diberikan']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
} finally {
    if ($stmt) {
        $stmt->close();
    }
    $conn->close();
}
?>
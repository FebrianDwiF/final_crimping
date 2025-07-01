<?php
session_start();

// Validasi apakah metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diperbolehkan']);
    exit;
}

// Ambil data dari reques
$data = json_decode($_POST['results'], true);

$results = json_decode($_POST['results'], true);

$type = $_POST['type'] ?? null; // Ambil jenis pencarian



// Validasi data
if (empty($results) || empty($type)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Data atau type tidak lengkap']);
    exit;
}

// Buat array penyimpanan sesi jika belum ada
if (!isset($_SESSION['filtered_data'])) {
    $_SESSION['filtered_data'] = [];
}
if (!isset($_SESSION['search_results'])) {
    $_SESSION['search_results'] = [];
}

// Simpan data ke dalam sesi berdasarkan jenis
if (!isset($_SESSION['filtered_data'][$type])) {
    $_SESSION['filtered_data'][$type] = [];
    echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan di sesi']);
}
if (!isset($_SESSION['search_results'][$type])) {
    $_SESSION['search_results'][$type] = [];
    echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan di sesi']);
}
if ($type === 'form-input') {
    $_SESSION['form_input_data'] = $data;
    echo json_encode(['success' => true, 'message' => 'Data berhasil disimpan.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Jenis data tidak valid.']);
}
// Simpan data apapun ke session berdasarkan type
$_SESSION[$type] = $data;

echo json_encode(['success' => true, 'message' => "Data bertipe '$type' berhasil disimpan ke session."]);


// Tambahkan hasil pencarian baru
$_SESSION['filtered_data'][$type] = array_merge($_SESSION['filtered_data'][$type], $results);
$_SESSION['search_results'][$type] = array_merge($_SESSION['search_results'][$type], $results);

// Kembalikan respons sukses

?>
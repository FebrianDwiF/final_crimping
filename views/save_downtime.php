<?php
session_start();

// Ambil data dari request body
$data = json_decode(file_get_contents("php://input"), true);

// Simpan data ke dalam session
if (!empty($data['downtimeRecords'])) {
    $_SESSION['saved_data'] = $data['downtimeRecords'];
    echo json_encode(["status" => "success", "message" => "Data berhasil disimpan"]);
} else {
    echo json_encode(["status" => "error", "message" => "Tidak ada data yang dikirim"]);
}
?>

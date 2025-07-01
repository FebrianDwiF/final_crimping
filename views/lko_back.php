<?php
include '../db/connection.php';
include '../process/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $no_stroke = $_POST['no_stroke'] ?? '';
    $new_stroke = $_POST['current_stroke'] ?? 0;

    // Debugging: Cek apakah data dikirim dengan benar
    if ($no_stroke === '' || $new_stroke === 0) {
        echo json_encode(["success" => false, "message" => "Invalid data received", "no_stroke" => $no_stroke, "new_stroke" => $new_stroke]);
        exit;
    }

    // Update current stroke di database
    $stmt = $conn->prepare("UPDATE data_stroke SET current_stroke = ? WHERE no = ?");
    $stmt->bind_param("ii", $new_stroke, $no_stroke);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Stroke updated!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update stroke", "error" => $stmt->error]);
    }
}

?>
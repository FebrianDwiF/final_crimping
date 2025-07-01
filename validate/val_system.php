<?php
include '../db/connection.php'; // Sesuaikan dengan file koneksi database Anda

if (isset($_POST['mesin'])) {
    $mesin = $_POST['mesin'];
    $query = "SELECT COUNT(*) AS total FROM data_crimping WHERE mesin = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $mesin);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    echo json_encode(["valid" => $result['total'] > 0]);
    exit;
}

if (isset($_POST['carline'])) {
    $carline = $_POST['carline'];
    $query = "SELECT COUNT(*) AS total FROM data_stroke WHERE carline = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $carline);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    echo json_encode(["valid" => $result['total'] > 0]);
    exit;
}
?>
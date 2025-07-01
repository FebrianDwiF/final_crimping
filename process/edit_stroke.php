<?php
include '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no = intval($_POST['no']);
    $carline = $_POST['carline'];
    $mesin = $_POST['mesin'];
    $applicator = $_POST['applicator'];
    $max_stroke = $_POST['max_stroke'];
    $current_stroke = $_POST['current_stroke'];

    $stmt = $conn->prepare("UPDATE data_stroke SET carline = ?, mesin = ?, applicator = ?, max_stroke = ?, current_stroke = ? WHERE no = ?");
    $stmt->bind_param("sssiii", $carline, $mesin, $applicator, $max_stroke, $current_stroke, $no);

    if ($stmt->execute()) {
        echo "<script>alert('Data updated successfully!'); window.location.href = '../views/teknisi.php';</script>";
    } else {
        echo "<script>alert('Failed to update data.'); window.location.href = '../views/teknisi.php';</script>";
    }
    $stmt->close();
    $conn->close();
}
?>

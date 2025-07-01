<?php
include '../db/connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $carline = $_POST['carline'];
    $mesin = $_POST['mesin'];
    $no = $_POST['no'];
    $applicator = $_POST['applicator'];
    $man_no = $_POST['man_no'];
    $kind = $_POST['kind'];
    $size = $_POST['size'];
    $knop_spacer = $_POST['knop_spacer'];
    $dial = $_POST['dial'];
    $no_prog = $_POST['no_prog'];

    $stmt = $conn->prepare("UPDATE data_cfm 
        SET carline = ?, mesin = ?, no = ?, applicator = ?, man_no = ?, kind = ?, size = ?, knop_spacer = ?, dial = ?, no_prog = ? WHERE id = ?");
    $stmt->bind_param(
        "ssisisdsiii",
        $carline,
        $mesin,
        $no,
        $applicator,
        $man_no,
        $kind,
        $size,
        $knop_spacer,
        $dial,
        $no_prog,
        $id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Data updated successfully!'); window.location.href = '../views/teknisi.php';</script>";
    } else {
        echo "<script>alert('Failed to update data.'); window.location.href = '../views/teknisi.php';</script>";
    }
    $stmt->close();
    $conn->close();
}
?>

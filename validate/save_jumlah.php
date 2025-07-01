<?php
session_start();

if (isset($_POST['jumlah'])) {
    $_SESSION['jumlahInput'] = (int) $_POST['jumlah']; // Simpan jumlah ke session
    echo json_encode(["success" => true, "jumlah" => $_SESSION['jumlahInput']]);
} else {
    echo json_encode(["success" => false]);
}
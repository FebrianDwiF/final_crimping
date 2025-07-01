<?php
session_start();
include '../db/connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['username']); // dari input username
    $nik = mysqli_real_escape_string($conn, $_POST['password']);  // dari input password
    $role = "teknisi"; // hanya izinkan teknisi

    // Cek user di database berdasarkan name, nik, dan role
    $query = $conn->prepare("SELECT * FROM users WHERE name = ? AND nik = ? AND role = ?");
    $query->bind_param("sss", $name, $nik, $role);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['name'] = $name;
        $_SESSION['role'] = $role;
        echo json_encode(["success" => true, "role" => $role]);
    } else {
        echo json_encode(["success" => false, "message" => "Nama, NIK, atau role tidak sesuai!"]);
    }
}
?>
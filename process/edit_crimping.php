<?php 
include '../db/connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no = intval($_POST['no']);
    $mesin = $_POST['mesin'];
    $term = $_POST['term'];
    $wire = $_POST['wire'];
    $size = $_POST['size'];
    $acc = $_POST['acc'];
    $f_c_h = $_POST['f_c_h'];
    $toleransi1 = $_POST['toleransi1'];
    $one_two_f_c_h = $_POST['1_2_f_c_h'];
    $r_c_h = $_POST['r_c_h'];
    $toleransi2 = $_POST['toleransi2'];
    $one_two_r_c_h = $_POST['1_2_r_c_h'];
    $f_c_w_min = $_POST['f_c_w_min'];
    $f_c_w_max = $_POST['f_c_w_max'];
    $r_c_w_min = $_POST['r_c_w_min'];
    $r_c_w_max = $_POST['r_c_w_max'];

    $stmt = $conn->prepare("UPDATE data_crimping 
                            SET mesin = ?, term = ?, wire = ?, size = ?, acc = ?, f_c_h = ?, toleransi1 = ?, 1_2_f_c_h = ?, r_c_h = ?, toleransi2 = ?, 1_2_r_c_h = ?, f_c_w_min = ?, f_c_w_max = ?, r_c_w_min = ?, r_c_w_max = ? 
                            WHERE no = ?");
    $stmt->bind_param("sssdsddddddddddi", 
        $mesin, $term, $wire, $size, $acc, $f_c_h, $toleransi1, $one_two_f_c_h, $r_c_h, $toleransi2, $one_two_r_c_h, 
        $f_c_w_min, $f_c_w_max, $r_c_w_min, $r_c_w_max, $no);

    if ($stmt->execute()) {
        echo "<script>alert('Data updated successfully!'); window.location.href = '../views/teknisi.php';</script>";
    } else {
        echo "<script>alert('Failed to update data.'); window.location.href = '../views/teknisi.php';</script>";
    }
    $stmt->close();
    $conn->close();
}


?>

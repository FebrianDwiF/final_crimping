<?php
include '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari POST, dan amankan
    $no = mysqli_real_escape_string($conn, $_POST['no']);
    $carline = mysqli_real_escape_string($conn, $_POST['carline']);
    $mesin = mysqli_real_escape_string($conn, $_POST['mesin']);
    $applicator = mysqli_real_escape_string($conn, $_POST['applicator']);
    $man_no = mysqli_real_escape_string($conn, $_POST['man_no']);
    $kind = mysqli_real_escape_string($conn, $_POST['kind']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $knop_spacer = mysqli_real_escape_string($conn, $_POST['knop_spacer']);
    $dial = mysqli_real_escape_string($conn, $_POST['dial']);
    $no_prog = mysqli_real_escape_string($conn, $_POST['no_prog']);

    // Query update
    $query = "UPDATE data_cfm SET 
        carline='$carline', 
        mesin='$mesin', 
        applicator='$applicator', 
        man_no='$man_no', 
        kind='$kind', 
        size='$size', 
        knop_spacer='$knop_spacer', 
        dial='$dial', 
        no_prog='$no_prog' 
        WHERE no='$no'";

    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: pengukuran.php?applicator=" . urlencode($applicator));
        exit;
    } else {
        echo "Gagal update: " . mysqli_error($conn);
    }
} else {
    echo "Metode tidak diizinkan.";
}
?>
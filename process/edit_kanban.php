<?php
include '../db/connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $machine = $_POST['machine'];
    $npg = $_POST['npg'];
    $noproc = $_POST['noproc'];
    $ctrl_no = $_POST['ctrl_no'];
    $kind = $_POST['kind'];
    $size = $_POST['size'];
    $col = $_POST['col'];
    $c_l = $_POST['c_l'];
    $term_b = $_POST['term_b'];
    $strip_b = $_POST['strip_b'];
    $half_strip_b = $_POST['half_strip_b'];
    $man_b = $_POST['man_b'];
    $acc_b1 = $_POST['acc_b1'];
    $term_a = $_POST['term_a'];
    $strip_a = $_POST['strip_a'];
    $half_strip_a = $_POST['half_strip_a'];
    $man_a = $_POST['man_a'];
    $acc_a1 = $_POST['acc_a1'];
    $qty = $_POST['qty'];

    $stmt = $conn->prepare("UPDATE data_kanban 
        SET machine = ?, npg = ?, noproc = ?, ctrl_no = ?, kind = ?, size = ?, col = ?, c_l = ?, term_b = ?, strip_b = ?, 
        half_strip_b = ?, man_b = ?, acc_b1 = ?, term_a = ?, strip_a = ?, half_strip_a = ?, man_a = ?, acc_a1 = ?, qty = ? 
        WHERE id = ?");
    $stmt->bind_param(
        "ssissssisissssisssii",
        $machine,
        $npg,
        $noproc,
        $ctrl_no,
        $kind,
        $size,
        $col,
        $c_l,
        $term_b,
        $strip_b,
        $half_strip_b,
        $man_b,
        $acc_b1,
        $term_a,
        $strip_a,
        $half_strip_a,
        $man_a,
        $acc_a1,
        $qty,
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
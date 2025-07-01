<?php 
session_start();

if (!isset($_SESSION['data_kanban']) || empty($_SESSION['data_kanban'])) {
    echo "<p style='color:red;'>No selection made.</p>";
    exit();
}

// Jika session filtered_data sudah ada, jangan kosongkan
if (!isset($_SESSION['filtered_data'])) {
    $_SESSION['filtered_data'] = [];
}

$data = $_SESSION['data_kanban'];
$selectedData = [];

foreach ($data as $row) {
    $npg = $row['npg'];

    // Cek apakah pengguna sudah memilih side berdasarkan npg
    if (isset($_POST['side'][$npg])) {
        $side = $_POST['side'][$npg];
    } else {
        // Pilih otomatis jika hanya satu opsi yang tersedia
        if (!empty($row['man_a']) && empty($row['man_b'])) {
            $side = "A";
        } elseif (!empty($row['man_b']) && empty($row['man_a'])) {
            $side = "B";
        } else {
            $side = "NONE"; // Tidak ada pilihan yang bisa dipilih otomatis
        }
    }

    // Simpan data berdasarkan pilihan side (A atau B)
    if ($side == "A") {
        $selectedData[] = [
            'machine' => $row['machine'],
            'npg' => $row['npg'],
            'noproc' => $row['noproc'],
            'ctrl_no' => $row['ctrl_no'],
            'kind' => $row['kind'],
            'size' => $row['size'],
            'col' => $row['col'],
            'c_l' => $row['c_l'],
            'Terminal' => $row['term_a'],
            'strip' => $row['strip_a'],
            'half_strip' => $row['half_strip_a'],
            'man' => $row['man_a'],
            'acc' => $row['acc_a1'],
            'qty' => $row['qty']
        ];
    } elseif ($side == "B") {
        $selectedData[] = [
            'machine' => $row['machine'],
            'npg' => $row['npg'],
            'noproc' => $row['noproc'],
            'ctrl_no' => $row['ctrl_no'],
            'kind' => $row['kind'],
            'size' => $row['size'],
            'col' => $row['col'],
            'c_l' => $row['c_l'],
            'Terminal' => $row['term_b'],
            'strip' => $row['strip_b'],
            'half_strip' => $row['half_strip_b'],
            'man' => $row['man_b'],
            'acc' => $row['acc_b1'],
            'qty' => $row['qty']
        ];
    } elseif ($side == "NONE") {
        // Jika tidak ada pilihan Side A atau Side B, tetap tampilkan data tapi kosongkan Man & Acc
        $selectedData[] = [
            'machine' => "-",
            'npg' => "-",
            'noproc' => "-",
            'ctrl_no' => "-",
            'kind' => "-",
            'size' => "-",
            'col' => "-",
            'c_l' => "-",
            'Terminal' => "-", // Kosong karena tidak ada pilihan
            'strip' => "-",
            'half_strip' => "-",
            'man' => "-", // Tidak ada manufacturer
            'acc' => "-",
            'qty' => "-" // Tetap tampilkan Quantity
        ];
    }
}

$_SESSION['filtered_data'] = $selectedData;

// Tampilkan tabel hasil seleksi
echo "<h3>Hasil Seleksi</h3>
      <table border='1'>
        <tr>
            <th>Machine</th>
            <th>NPG</th>
            <th>No Process</th>
            <th>No Control</th>
            <th>Kind</th>
            <th>Size</th>
            <th>Col</th>
            <th>Terminal</th>
            <th>Strip</th>
            <th>Half Strip</th>
            <th>Man</th>
            <th>Acc</th>
            <th>Quantity</th>
        </tr>";

foreach ($selectedData as $row) {
    echo "<tr>
            <td>{$row['machine']}</td>
            <td>{$row['npg']}</td>
            <td>{$row['noproc']}</td>
            <td>{$row['ctrl_no']}</td>
            <td>{$row['kind']}</td>
            <td>{$row['size']}</td>
            <td>{$row['col']}</td>
            <td>{$row['Terminal']}</td>
            <td>{$row['strip']}</td>
            <td>{$row['half_strip']}</td>
            <td>{$row['man']}</td>
            <td>{$row['acc']}</td>
            <td>{$row['qty']}</td>
        </tr>";
}
echo "</table>";

echo "success";

// echo "<pre>";
// var_dump($_SESSION['filtered_data']);
// echo "</pre>";
?>
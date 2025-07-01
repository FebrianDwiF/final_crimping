<?php
session_start();
include '../db/connection.php';

header('Content-Type: text/html; charset=UTF-8');

$man = $_SESSION['mesin']; // Mesin yang digunakan untuk filter

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil hanya npg yang valid (tidak kosong)
    $npgList = array_values($_POST["firstFive"] ?? []);
    $npgList = array_filter($npgList); // Hapus nilai kosong

    // Simpan ke session
    $_SESSION['valid_npg'] = $npgList;

    // Ambil dan simpan juga 'original' (yang kamu kirim lewat AJAX)
    $originalList = array_values($_POST["original"] ?? []);
    $originalList = array_filter($originalList); // Hapus kosong kalau perlu

    $_SESSION['original_noproc'] = $originalList;

}

if (empty($npgList)) {
    echo "<p style='color:red;'>No NPG provided.</p>";
    exit();
}

// Query database berdasarkan npg
$placeholders = implode(',', array_fill(0, count($npgList), '?'));
$query = "SELECT machine, npg, noproc, ctrl_no, kind, size, col, c_l,
                 term_b, strip_b, half_strip_b, man_b, acc_b1,
                 term_a, strip_a, half_strip_a, man_a, acc_a1, qty
          FROM data_kanban
          WHERE npg IN ($placeholders)
          ORDER BY FIELD(npg, " . implode(',', array_map(fn($v) => "'$v'", $npgList)) . ")";

$stmt = $conn->prepare($query);
$types = str_repeat('s', count($npgList));
$stmt->bind_param($types, ...$npgList);
$stmt->execute();
$result = $stmt->get_result();

// Simpan hasil ke session dengan filter man_a/man_b sesuai $man
$_SESSION['data_kanban'] = [];
while ($row = $result->fetch_assoc()) {
    if ($row['man_a'] === $man || $row['man_b'] === $man) {
        $_SESSION['data_kanban'][] = $row;
    }
}

if (empty($_SESSION['data_kanban'])) {
    echo "<p style='color:red;'>Tidak ada data ditemukan untuk NPG yang sesuai dengan mesin.</p>";
    exit();
}

// Tampilkan tabel hanya jika ada data
$pilihanDibutuhkan = false;
$setidaknyaSatuDipilih = false;
$output = "<form id='side-selection-form' method='POST' action='save_selection.php'>
            <table border='1'>
                <tr>
                    <th>NPG</th>
                    <th>No Process</th>
                    <th>Side A</th>
                    <th>Side B</th>
                </tr>";

foreach ($_SESSION['data_kanban'] as $row) {
    $adaManA = (!empty($row['man_a']) && $row['man_a'] === $man);
    $adaManB = (!empty($row['man_b']) && $row['man_b'] === $man);

    $output .= "<tr><td>{$row['npg']}</td><td>{$row['noproc']}</td>";

    if ($adaManA || $adaManB) {
        if ($adaManA && $adaManB) {
            // Jika dua sisi tersedia, user harus memilih
            $output .= "<td><input type='radio' name='side[{$row['npg']}]' value='A' required> Pilih A</td>
                        <td><input type='radio' name='side[{$row['npg']}]' value='B' required> Pilih B</td>";
            $pilihanDibutuhkan = true;
        } else {
            // Pilih otomatis jika hanya satu opsi tersedia
            if ($adaManA) {
                $output .= "<td>✅ <input type='hidden' name='side[{$row['npg']}]' value='A'></td><td>-</td>";
            } elseif ($adaManB) {
                $output .= "<td>-</td><td>✅ <input type='hidden' name='side[{$row['npg']}]' value='B'></td>";
            }
            $setidaknyaSatuDipilih = true;
        }
    } else {
        $output .= "<td>-</td><td>-</td>";
    }

    $output .= "</tr>";
}

$output .= "</table>";

// Tombol submit hanya muncul jika ada pilihan yang perlu disimpan
if ($pilihanDibutuhkan || $setidaknyaSatuDipilih) {
    $output .= "<button type='submit'>Simpan Pilihan</button>";
}

$output .= "</form>";

// Jika semua opsi otomatis dipilih, langsung simpan ke session
if (!$pilihanDibutuhkan && $setidaknyaSatuDipilih) {
    $_SESSION['filtered_data'] = [];

    foreach ($_SESSION['data_kanban'] as $row) {
        $side = "-";

        if (!empty($row['man_a']) && $row['man_a'] === $man && empty($row['man_b'])) {
            $side = "A";
        } elseif (!empty($row['man_b']) && $row['man_b'] === $man && empty($row['man_a'])) {
            $side = "B";
        }

        $_SESSION['filtered_data'][] = [
            'machine' => $row['machine'],
            'npg' => $row['npg'],
            'noproc' => $row['noproc'],
            'ctrl_no' => $row['ctrl_no'],
            'kind' => $row['kind'],
            'size' => $row['size'],
            'col' => $row['col'],
            'c_l' => $row['c_l'],
            'terminal' => $side == "A" ? $row['term_a'] : ($side == "B" ? $row['term_b'] : "-"),
            'strip' => $side == "A" ? $row['strip_a'] : ($side == "B" ? $row['strip_b'] : "-"),
            'half_strip' => $side == "A" ? $row['half_strip_a'] : ($side == "B" ? $row['half_strip_b'] : "-"),
            'man' => $side == "A" ? $row['man_a'] : ($side == "B" ? $row['man_b'] : "-"),
            'acc' => $side == "A" ? $row['acc_a1'] : ($side == "B" ? $row['acc_b1'] : "-"),
            'qty' => $row['qty']
        ];
    }
}

echo $output;
// var_dump($_SESSION['filtered_data'])
?>
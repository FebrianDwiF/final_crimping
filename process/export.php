<?php
require '../vendor/autoload.php'; // Load PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include '../db/connection.php'; // Koneksi ke database

if (!isset($_GET['dataType']) || empty($_GET['dataType'])) {
    die("Jenis data tidak ditemukan.");
}

$dataType = $_GET['dataType']; // terminal, crimping, cfm, stroke
$format = $_GET['format'] ?? 'xlsx'; // Default format: Excel (bisa diubah ke csv)

switch ($dataType) {
    case 'terminal':
        $query = "SELECT * FROM data_kanban";
        $columns = ['id','Machine', 'NPG', 'NoProc', 'Ctrl No', 'Kind', 'Size', 'Col', 'C_L', 'Term_B', 'Strip_B', 'Half_Strip_B', 'Man_B', 'Acc_B1', 'Term_A', 'Strip_A', 'Half_Strip_A', 'Man_A', 'Acc_A1', 'Qty'];
        break;
    
    case 'crimping':
        $query = "SELECT * FROM data_crimping";
        $columns = ['no','Mesin', 'Term', 'Wire', 'Size', 'Acc', 'F_C_H', 'Toleransi1', '1_2_F_C_H', 'R_C_H', 'Toleransi2', '1_2_R_C_H', 'F_C_W_Min', 'F_C_W_Max', 'R_C_W_Min', 'R_C_W_Max'];
        break;

    case 'cfm':
        $query = "SELECT * FROM data_cfm";
        $columns = ['id','Carline', 'Mesin', 'No', 'Applicator', 'Man No', 'Kind', 'Size', 'Knop Spacer', 'Dial', 'No Prog'];
        break;

    case 'stroke':
        $query = "SELECT * FROM data_stroke";
        $columns = ['no','Carline', 'Mesin', 'Applicator', 'Max Stroke', 'Current Stroke'];
        break;

    case 'lko':
        $query = "SELECT * FROM data_lko";
        $columns = ['id','User','Carline', 'Mesin', 'Time', 'Shift','No Control', 'No Issue', 'Scan Kanban', 'Qty',
         'Kind', 'Size', 'Col', 'Terminal', 'Lot Terminal', 'F_C_H', 'R_C_H', 'F_C_W', 'R_C_W', 'C_L', 'Kode Defect', 'Qty M', 'Code Error', 'Downtime','created_at'];
        break;
    default:
        die("Data type tidak valid.");
}

// Eksekusi query
$result = $conn->query($query);

if ($format === 'csv') {
    // Export ke CSV
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="'.$dataType.'.csv"');
    $output = fopen('php://output', 'w');

    // Tambahkan header
    fputcsv($output, $columns, ';'); // Gunakan ";" agar lebih kompatibel dengan Excel

    // Tambahkan data
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row, ';');
    }

    fclose($output);
} else {
    // Export ke Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Tambahkan header ke Excel
    $colIndex = 1;
    foreach ($columns as $column) {
        $sheet->setCellValue(chr(64 + $colIndex) . '1', $column);

        $colIndex++;
    }

    // Tambahkan data ke Excel
    $rowIndex = 2;
    while ($row = $result->fetch_assoc()) {
        $colIndex = 1;
        foreach ($row as $cell) {
            $sheet->setCellValue(chr(64 + $colIndex) . $rowIndex, $cell);

            $colIndex++;
        }
        $rowIndex++;
    }

    // Header untuk download Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'.$dataType.'.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
}

$conn->close();
exit;
?>

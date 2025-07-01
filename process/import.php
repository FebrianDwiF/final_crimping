<?php
require '../vendor/autoload.php'; // Load PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

include '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['file'];
    $dataType = $_POST['dataType']; // Expecting 'terminal', 'crimping', 'cfm', or 'stroke'

    if ($file['error'] === UPLOAD_ERR_OK) {
        $filePath = $file['tmp_name'];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (in_array($fileExtension, ['csv', 'xls', 'xlsx'])) {
            try {
                if ($fileExtension === 'csv') {
                    // Handle CSV files
                    $fileHandle = fopen($filePath, 'r');
                    $isFirstRow = true;
                    if ($fileHandle) {
                        while (($data = fgetcsv($fileHandle, 1000, ",")) !== false) {
                            if ($isFirstRow) {
                                $isFirstRow = false; // Lewati baris pertama
                                continue;
                            }
                            processRow($dataType, $data, $conn);
                        }
                        fclose($fileHandle);
                    } else {
                        throw new Exception("Unable to open CSV file.");
                    }
                } else {
                    // Handle XLS and XLSX files
                    $spreadsheet = IOFactory::load($filePath);
                    $sheet = $spreadsheet->getActiveSheet();
                    $data = $sheet->toArray();

                    foreach ($data as $index => $row) {
                        if ($index === 0) {
                            // Skip the header row
                            continue;
                        }
                        processRow($dataType, $row, $conn);
                    }
                }

                echo "<script>alert('File imported successfully!'); window.location.href = '../views/teknisi.php';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href = '../views/teknisi.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid file type. Please upload a CSV, XLS, or XLSX file.'); window.location.href = '../views/teknisi.php';</script>";
        }
    } else {
        echo "<script>alert('File upload error!'); window.location.href = '../views/teknisi.php';</script>";
    }
}

function processRow($dataType, $row, $conn) {
    switch ($dataType) {
        case 'terminal':
            if (count($row) >= 19) {
                list($machine, $npg, $noproc, $ctrl_no, $kind, $size, $col, $c_l, $term_b, 
                     $strip_b, $half_strip_b, $man_b, $acc_b1, $term_a, $strip_a, 
                     $half_strip_a, $man_a, $acc_a1, $qty) = array_slice($row,  1);
                     

                $stmt = $conn->prepare("INSERT INTO data_kanban (machine, npg, noproc, ctrl_no, kind, size, col, c_l, term_b, strip_b, half_strip_b, man_b, acc_b1, term_a, strip_a, half_strip_a, man_a, acc_a1, qty) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssissdsisdssssdsssi", $machine, $npg, $noproc, $ctrl_no, $kind, $size, $col, $c_l, $term_b, $strip_b, $half_strip_b, $man_b, $acc_b1, $term_a, $strip_a, $half_strip_a, $man_a, $acc_a1, $qty);
                $stmt->execute();
            }
            break;

        case 'crimping':
            if (count($row) >= 15) {
                list( $mch, $term, $wire, $size1, $acc, $f_c_h, $toleransi1, $f_c_h_1_2, $r_c_h, $toleransi2, $r_c_h_2, $f_c_w_min, $f_c_w_max, $r_c_w_min, $r_c_w_max) = array_slice($row, 1);


                $stmt = $conn->prepare("INSERT INTO data_crimping (mesin, term, wire, size, acc, f_c_h, toleransi1, 1_2_f_c_h, r_c_h, toleransi2, 1_2_r_c_h, f_c_w_min, f_c_w_max, r_c_w_min, r_c_w_max) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssdsdddddddddd", $mch, $term, $wire, $size1, $acc, $f_c_h, $toleransi1, $f_c_h_1_2, $r_c_h, $toleransi2, $r_c_h_2, $f_c_w_min, $f_c_w_max, $r_c_w_min, $r_c_w_max);
                $stmt->execute();
            }
            break;

        case 'cfm':
            if (count($row) >= 10) {
                list($carline, $mesin, $no, $applicator, $man_no, $kind, $size, 
                     $knop_spacer, $dial, $no_prog) = array_slice($row, 1);

                $stmt = $conn->prepare("INSERT INTO data_cfm (carline, mesin, no, applicator, man_no, kind, size, knop_spacer, dial, no_prog) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssisssssdi", $carline, $mesin, $no, $applicator, $man_no, $kind, $size, $knop_spacer, $dial, $no_prog);
                $stmt->execute();
            }
            break;

        case 'stroke':
            if (count($row) >= 5) {
                list( $carline, $mesin, $applicator, $max_stroke, $current_stroke) = array_slice($row, 1);

                $stmt = $conn->prepare("INSERT INTO data_stroke (carline, mesin, applicator, max_stroke, current_stroke) VALUES ( ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssii", $carline, $mesin, $applicator, $max_stroke, $current_stroke);
                $stmt->execute();
            }
            break;

        default:
            throw new Exception("Invalid data type specified.");
    }
}
?>
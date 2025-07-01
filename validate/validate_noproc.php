<?php
include '../db/connection.php';

header('Content-Type: application/json');

$response = ["valid" => true, "error" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["firstFive"]) && isset($_POST["extracted"])) {
    $firstFiveDigits = $_POST["firstFive"]; // Data firstFive dari AJAX (NPG)
    $extractedValues = $_POST["extracted"]; // Data extracted (digit 2-5 dari noproc)

    $invalidNoproc = [];

    foreach ($firstFiveDigits as $key => $firstFive) {
        $extracted = $extractedValues[$key] ?? "";

        // Pastikan firstFive memiliki panjang 5 karakter
        if (strlen($firstFive) !== 5) {
            $invalidNoproc[] = "NPG ke-$key ('$firstFive') tidak valid (harus 5 digit).";
            $response["valid"] = false;
            continue;
        }

        // Cek apakah firstFive (NPG) ada di database
        $stmt = $conn->prepare("SELECT noproc FROM data_kanban WHERE npg = ?");
        $stmt->bind_param("s", $firstFive);
        $stmt->execute();
        $result = $stmt->get_result();

        $validNoproc = false;
        while ($row = $result->fetch_assoc()) {
            // Ambil digit ke-2 sampai ke-5 dari noproc
            $noprocExtracted = substr($row['noproc'], 1, 4);
            if ($noprocExtracted === $extracted) {
                $validNoproc = true;
                break;
            }
        }
        $stmt->close();

        // Jika extracted tidak cocok dengan noproc di database
        if (!$validNoproc) {
            $invalidNoproc[] = "Nomor Proses ke-$key dengan extracted '$extracted' tidak ditemukan di database untuk NPG '$firstFive'.";
            $response["valid"] = false;
        }
    }

    if (!$response["valid"]) {
        $response["error"] = implode("<br>", $invalidNoproc);
    }
}

echo json_encode($response);
?>
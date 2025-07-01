<?php
include '../db/connection.php';

header('Content-Type: application/json');

$response = ["valid" => true, "error" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["firstFive"])) {
    $firstFiveDigits = $_POST["firstFive"]; // Data firstFive dari AJAX
    

    $invalidNpg = [];

    foreach ($firstFiveDigits as $key => $npg) {
        // Cek apakah npg ada di database
        $stmt = $conn->prepare("SELECT COUNT(*) FROM data_kanban WHERE npg = ?");
        $stmt->bind_param("s", $npg);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count == 0) {
            $invalidNpg[] = "NPG ke-$key dengan nilai '$npg' tidak ditemukan di database.";
            $response["valid"] = false;
        }
    }

    if (!$response["valid"]) {
        $response["error"] = implode("<br>", $invalidNpg);
    }
}

echo json_encode($response);
?>
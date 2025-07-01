<?php
include '../db/connection.php';
include '../process/auth.php';

if (isset($_GET['status']) && $_GET['status'] === 'stopped') {
    echo "<div class='alert alert-warning'>Proses telah dihentikan.</div>";
}
// Simpan jumlah input ke session jika ada perubahan
if (isset($_GET['jumlah'])) {
    $_SESSION['jumlahInput'] = intval($_GET['jumlah']);
}

// Ambil nilai session jika ada, atau default 0
$jumlahInput = $_SESSION['jumlahInput'] ?? 0;
// var_dump($jumlahInput);
$mesin = isset($_SESSION['mesin']) ? $_SESSION['mesin'] : '';
// var_dump($mesin);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Montserrat:wght@400;600&display=swap"
        rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../public/css/system.css">
    <title>Search Data by Keyword</title>
    <!-- <link rel="stylesheet" href="../public/css/system.css"> -->
</head>


<body>
    <div class="sidebar">
        <h4><?= "Production"; ?></h4>

        <hr>
        <br>
        <?php date_default_timezone_set("Asia/Jakarta");

                $formatter = new IntlDateFormatter(
                    'id_ID', 
                    IntlDateFormatter::FULL, 
                    IntlDateFormatter::SHORT,
                    'Asia/Jakarta',
                    IntlDateFormatter::GREGORIAN
                );

                $loginTime = new DateTime($user['loginTime']);
                $formattedDate = $formatter->format($loginTime);
                ?>

        <h4><?= "Login Time: " . $formattedDate; ?></h4>

        <br>
        <a href="./operator.php?nik=<?= $nik ?>" class="operator">Operator Page</a>

        <form action="../process/logout.php" method="POST" class="sidebar-form">
            <input type="hidden" name="nik" value="<?php echo htmlspecialchars($nik); ?>">
            <button type="submit" class="logout">🚪 Logout</button>
        </form>
    </div>
    <h1>Search Data</h1>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMesin">
        Pilih Mesin
    </button>
    <div class="modal fade" id="modalMesin" tabindex="-1" aria-labelledby="modalMesinLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMesinLabel">Masukkan Mesin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-mesin" method="POST">
                        <!-- Shift -->
                        <div class="mb-3">
                            <label class="form-label">Shift:</label>
                            <select name="shift" id="shift" class="form-select">
                                <option value="A">A</option>
                                <option value="B">B</option>
                            </select>
                        </div>

                        <!-- Carline -->
                        <div class="mb-3">
                            <label for="carline" class="form-label">Carline:</label>
                            <input type="text" name="carline" id="carline" class="form-control"
                                placeholder="Enter Carline">
                            <div class="invalid-feedback" id="carlineError">Carline harus diisi!</div>
                        </div>

                        <!-- mesin -->
                        <div class="mb-3">
                            <label for="mesin" class="form-label">Mesin:</label>
                            <input type="text" name="mesin" id="mesin" class="form-control" placeholder="Enter Mesin">
                            <div class="invalid-feedback" id="mesinError">Nomor Mesin harus diisi!</div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="submit-mesin" disabled>Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="container mt-5">
        <div class="row">
            <!-- Form Nomor Proses -->
            <div class="col-md-4">
                <form id="form-noproc" method="GET">
                    <input type="hidden" name="nik" value="<?= htmlspecialchars($nik) ?>">
                    <input type="hidden" name="mesin" id="hidden-mesin">
                    <!-- Pilihan jumlah input -->
                    <label for="jumlahInput">Jumlah Nomor Proses:</label>
                    <select id="jumlahInput" name="jumlah" class="form-control">
                        <option value="0">Pilih jumlah</option>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                        <option value="<?= $i ?>" <?= ($jumlahInput == $i) ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>

                    <div id="input-container">
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                        <div class="input-group mt-2 process-input" id="input-<?= $i ?>"
                            style="<?= ($i <= $jumlahInput) ? 'display: block;' : 'display: none;' ?>">
                            <label for="noproc<?= $i ?>">Nomor Proses <?= $i ?>:</label>
                            <input type="text" name="noproc<?= $i ?>" id="noproc<?= $i ?>"
                                class="form-control form-input" autofocus>

                        </div>
                        <?php endfor; ?>
                    </div>

                    <button type="submit" id="submitBtn" class="btn btn-primary mt-2">Search</button>
                </form>
            </div>

            <!-- Hasil Pencarian -->
            <div class="col-md-4">
                <div id="error-message" style="color: red; display: none;"></div>
                <div id="result"></div>
            </div>
        </div>
    </div>
    <script src="../public/js/script.js"></script>
</body>

</html>
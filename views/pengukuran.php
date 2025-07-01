<?php
include '../db/connection.php';
include '../process/auth.php';

$man = $_SESSION['mesin'];

//var_dump($nik);
// include '../views/system_backup2.php';

    // // Ambil data dari URL
    // $applicator = $_GET['applicator'] ?? 'Tidak ada data';
    // $term = $_GET['term'] ?? 'Tidak ada data';
    // echo "<p>Applicator: " . htmlspecialchars($applicator) . "</p>";
    // echo "<p>Terminal: " . htmlspecialchars($term) . "</p>";


$_SESSION['search_results'] = $_SESSION['search_results'] ?? [] ;
$searchResults = $_SESSION['search_results'];
// var_dump($searchResults);

// $data_cfm = mysqli_query($conn, "SELECT * FROM data_cfm ORDER BY no ASC");
// $filteredApplicator = [];
// while ($row = mysqli_fetch_assoc($data_cfm)) {
//     $filteredApplicator[] = $row;
// }

// // Jika ada stroke data, ambil juga dari tabel stroke (jika punya)
// $strokeResults = mysqli_query($conn, "SELECT * FROM data_stroke"); // Sesuaikan dengan tabelmu
// $filteredStroke = [];
// while ($row = mysqli_fetch_assoc($strokeResults)) {
//     $filteredStroke[] = $row;
// }

$filteredApplicator = $searchResults['applicator-term']['data_cfm'] ?? [];

// var_dump($filteredApplicator);

$filteredTerm = array_slice($searchResults['applicator-term']['data_crimping'] ?? [], 0, 3);
// var_dump($filteredTerm);

$filteredStroke = array_slice($searchResults['applicator-term']['data_stroke'] ?? [],  0, 3);

// var_dump($filteredStroke);

$filteredNoproc = array_slice($_SESSION['filtered_data'], 0, 10);
// var_dump($filteredNoproc);
// var_dump($filteredNoproc);
// var_dump($filteredStroke);
// var_dump($filteredTerm);
// var_dump($filteredStroke);
$term = $filteredTerm[0]['term'] ?? 'N/A';
// var_dump($term);
// echo $term;

$man = $_SESSION['mesin']; // Ambil mesin dari session

if (isset($_GET['applicator'])) {
    $applicatorCode = $_GET['applicator'];
    $filteredApplicator = [];

    // Query database dengan filter applicator dan mesin ($man)
    $query = "SELECT * FROM data_cfm WHERE applicator = ? AND mesin = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $applicatorCode, $man);
} else {
    // Jika applicator tidak di-set, ambil semua data sesuai mesin ($man)
    $filteredApplicator = [];
    $query = "SELECT * FROM data_cfm WHERE mesin = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $man);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $filteredApplicator[] = $row;
}

$stmt->close();

$lotArray = $_SESSION['lot'] ?? [];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <title>Data Pengukuran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/pengukuran.css">

</head>

<body>
    <div id="notification" style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%);
            background: rgba(6, 181, 229, 0.9); color: white; padding: 15px 20px;
            border-radius: 8px; display: none; font-size: 16px; text-align: center;">
    </div>
    <div class="main-container">
        <h2 class="text-center mb-4">Data CFM</h2>

        <!-- First Table -->
        <div class="table-section">
            <table class="table compact-table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 10%;">Carline</th>
                        <th style="width: 10%;">Mesin</th>
                        <th style="width: 10%;">Applicator</th>
                        <th style="width: 8%;">Man No</th>
                        <th style="width: 8%;">Kind</th>
                        <th style="width: 8%;">Size</th>
                        <th style="width: 10%;">Knop Spacer</th>
                        <th style="width: 8%;">Dial</th>
                        <th style="width: 8%;">No Prog</th>
                        <th style="width: 10%;">Max Stroke</th>
                        <th style="width: 10%;">Current Stroke</th>
                        <th style="width: 8%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
if (empty($filteredApplicator)) {
    echo "<tr><td colspan='13' class='text-center'>Data tidak ditemukan.</td></tr>";
} else {
    foreach ($filteredApplicator as $applicator) {
        $stroke = array_filter($filteredStroke, function ($s) use ($applicator) {
            return $s['applicator'] == $applicator['applicator'];
        });
        $stroke = reset($stroke) ?: null;

        echo "<tr>";
        echo "<td>{$applicator['no']}</td>";
        echo "<td>{$applicator['carline']}</td>";
        echo "<td>{$applicator['mesin']}</td>";
        echo "<td>{$applicator['applicator']}</td>";
        echo "<td>{$applicator['man_no']}</td>";
        echo "<td>{$applicator['kind']}</td>";
        echo "<td>{$applicator['size']}</td>";
        echo "<td>{$applicator['knop_spacer']}</td>";
        echo "<td>{$applicator['dial']}</td>";
        echo "<td>{$applicator['no_prog']}</td>";

        if ($stroke) {
            echo "<td>{$stroke['max_stroke']}</td>";
            echo "<td>{$stroke['current_stroke']}</td>";
        } else {
            echo "<td>-</td><td>-</td>";
        }
        echo "<td>
        <button class='btn btn-sm btn-primary' onclick=\"openLoginModal('{$applicator['no']}')\">Edit</button>
    </td>";
    
    

        // Modal edit
        echo "
        <div class='modal fade' id='editModal{$applicator['no']}' tabindex='-1' aria-labelledby='editModalLabel{$applicator['no']}' aria-hidden='true'>
          <div class='modal-dialog'>
            <div class='modal-content'>
              <div class='modal-header'>
                <h5 class='modal-title'>Edit Data Applicator</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
              </div>
              <form action='update_cfm.php' method='post'>
                <div class='modal-body'>
                    <input type='hidden' name='no' value='{$applicator['no']}'>
                    <div class='mb-3'>
                        <label>Carline</label>
                        <input type='text' class='form-control' name='carline' value='{$applicator['carline']}' required>
                    </div>
                    <div class='mb-3'>
                        <label>Mesin</label>
                        <input type='text' class='form-control' name='mesin' value='{$applicator['mesin']}' required>
                    </div>
                    <div class='mb-3'>
                        <label>Applicator</label>
                        <input type='text' class='form-control' name='applicator' value='{$applicator['applicator']}' required>
                    </div>
                    <div class='mb-3'>
                        <label>Man No</label>
                        <input type='text' class='form-control' name='man_no' value='{$applicator['man_no']}' required>
                    </div>
                    <div class='mb-3'>
                        <label>Kind</label>
                        <input type='text' class='form-control' name='kind' value='{$applicator['kind']}' required>
                    </div>
                    <div class='mb-3'>
                        <label>Size</label>
                        <input type='text' class='form-control' name='size' value='{$applicator['size']}' required>
                    </div>
                    <div class='mb-3'>
                        <label>Knop Spacer</label>
                        <input type='text' class='form-control' name='knop_spacer' value='{$applicator['knop_spacer']}' required>
                    </div>
                    <div class='mb-3'>
                        <label>Dial</label>
                        <input type='text' class='form-control' name='dial' value='{$applicator['dial']}' required>
                    </div>
                    <div class='mb-3'>
                        <label>No Prog</label>
                        <input type='text' class='form-control' name='no_prog' value='{$applicator['no_prog']}' required>
                    </div>
                </div>
                <div class='modal-footer'>
                  <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                  <button type='submit' class='btn btn-primary'>Save changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        ";
        //login
    }
}
?>
                    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Login untuk Edit</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="loginError" class="alert alert-danger" style="display: none;"></div>
                                    <form id="loginForm">
                                        <input type="hidden" id="editNoHidden" name="editNoHidden">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Role</label>
                                            <select class="form-control" id="role" required>
                                                <option value="teknisi">Teknisi</option>

                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Login</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </tbody>


                <?php
            if (!isset($_SESSION['refreshed'])) {
                $_SESSION['refreshed'] = true; // Tandai bahwa halaman sudah direfresh
                echo "<script>location.reload();</script>"; // Reload halaman
            }
            ?>

            </table>
        </div>

        <script>
        function openLoginModal(no) {
            document.getElementById('editNoHidden').value = no;
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const role = document.getElementById('role').value;
            const no = document.getElementById('editNoHidden').value;

            fetch('../process/login_check.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var loginModalEl = document.getElementById('loginModal');
                        var loginModal = bootstrap.Modal.getInstance(loginModalEl);
                        loginModal.hide();

                        if (data.role === 'teknisi') {
                            var editModal = new bootstrap.Modal(document.getElementById('editModal' + no));
                            editModal.show();
                        } else {
                            alert('Anda login sebagai ' + data.role + '. Tidak dapat mengedit data.');
                        }

                        document.getElementById('username').value = '';
                        document.getElementById('password').value = '';
                        document.getElementById('role').value = '';
                        document.getElementById('loginError').style.display = 'none';
                    } else {
                        document.getElementById('loginError').innerText = data.message;
                        document.getElementById('loginError').style.display = 'block';
                    }
                })
                .catch(err => {
                    console.error('Login error: ', err);
                    document.getElementById('loginError').innerText = 'Terjadi kesalahan koneksi.';
                    document.getElementById('loginError').style.display = 'block';
                });
        });
        </script>

        <?php
if (!isset($_SESSION['filtered_data']) || empty($_SESSION['filtered_data'])) {
    echo "<p class='text-danger text-center'>Tidak ada data yang dipilih.</p>";
    exit();
}

$selectedData = array_filter($_SESSION['filtered_data']);

?>
        <!-- Second Table -->
        <div class="table-section">
            <table class="table compact-table table-bordered table-striped">
                <h2 class="text-center mb-4">Data Terminal</h2>
                <thead class="table-primary">
                    <tr>
                        <th style="width: 8%;">Machine</th>
                        <th style="width: 8%;">No Process</th>
                        <th style="width: 8%;">No Control</th>
                        <th style="width: 8%;">Kind</th>
                        <th style="width: 5%;">Size</th>
                        <th style="width: 5%;">Col</th>
                        <th style="width: 8%;">Terminal</th>
                        <th style="width: 6%;">Strip</th>
                        <th style="width: 7%;">Half Strip</th>
                        <th style="width: 8%;">Man</th>
                        <th style="width: 8%;">Acc</th>
                        <th style="width: 10%;">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                  if (!empty($selectedData)) {
                    foreach ($selectedData as $row) {
                        // Cek apakah elemen adalah array dan memiliki kunci 'machine' (data utama)
                        if (!is_array($row) || !isset($row['machine'])) {
                            continue; // Lewati data yang tidak sesuai
                        }
                    
                        echo "<tr>
                                <td>" . (!empty($row['machine']) ? $row['machine'] : '-') . "</td>
                                <td>" . (!empty($row['noproc']) ? $row['noproc'] : '-') . "</td>
                                <td>" . (!empty($row['ctrl_no']) ? $row['ctrl_no'] : '-') . "</td>
                                <td>" . (!empty($row['kind']) ? $row['kind'] : '-') . "</td>
                                <td>" . (!empty($row['size']) ? $row['size'] : '-') . "</td>
                                <td>" . (!empty($row['col']) ? $row['col'] : '-') . "</td>
                                <td>" . (!empty($row['Terminal']) ? $row['Terminal'] : '-') . "</td>
                                <td>" . (!empty($row['strip']) ? $row['strip'] : '-') . "</td>
                                <td>" . (!empty($row['half_strip']) ? $row['half_strip'] : '-') . "</td>
                                <td>" . (!empty($row['man']) ? $row['man'] : '-') . "</td>
                                <td>" . (!empty($row['acc']) ? $row['acc'] : '-') . "</td>
                                <td>" . (!empty($row['qty']) ? $row['qty'] : '-') . "</td>
                            </tr>";
                    }
                }                    
                             
                
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Form -->
        <div class="form-container">
            <form id="form-id">
                <h4 class="form-title">Form Pengukuran</h4>
                <div class="form-group">
                    <label for="nilai_f_c_h">Nilai F C/H: <span id="range_f_c_h" class="range-info"></span></label>
                    <input type="text" id="nilai_f_c_h" name="nilai_f_c_h" required autofocus>
                    <small id="error_f_c_h" class="error-message"></small>
                </div>
                <div class="form-group">
                    <label for="nilai_f_c_w">Nilai F C/W: <span id="range_f_c_w" class="range-info"></span></label>
                    <input type="text" id="nilai_f_c_w" name="nilai_f_c_w" required>
                    <small id="error_f_c_w" class="error-message"></small>
                </div>
                <div class="form-group">
                    <label for="nilai_r_c_w">Nilai R C/W: <span id="range_r_c_w" class="range-info"></span></label>
                    <input type="text" id="nilai_r_c_w" name="nilai_r_c_w" required>
                    <small id="error_r_c_w" class="error-message"></small>
                </div>
                <div class="form-group">
                    <label for="nilai_r_c_h">Nilai R C/H: <span id="range_r_c_h" class="range-info"></span></label>
                    <input type="text" id="nilai_r_c_h" name="nilai_r_c_h" required>
                    <small id="error_r_c_h" class="error-message"></small>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let inputs = document.querySelectorAll(".form-input");
        let submitButton = document.querySelector("button[type='submit']");

        inputs.forEach((input, index) => {
            input.addEventListener("keypress", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();

                    let nextInput = inputs[index + 1];
                    if (nextInput) {
                        nextInput.focus();
                    } else {
                        submitButton.click(); // Jika input terakhir, klik submit
                    }
                }
            });
        });
    });
    $(document).ready(function() {
        let ranges = {};

        function fetchRangeData() {
            const termValue = <?php echo json_encode($term = $filteredTerm[0]['term'] ?? 'N/A'); ?>;
            console.log("Term Value:", termValue);

            if (!termValue || termValue === 'N/A') {
                $('#error-message').text("Parameter term harus diisi").show();
                return;
            }

            $.ajax({
                url: '../process/get_range.php',
                method: 'GET',
                data: {
                    term: termValue
                },
                dataType: 'json',
                success: function(response) {
                    console.log("✅ Sukses ambil data range:", response);
                    console.log("📦 Isi ranges:", response.ranges);
                    if (response.success && response.ranges) {
                        ranges = response.ranges;
                        $('#error-message').hide();

                        Object.keys(ranges).forEach(key => {
                            $(`#range_${key}`).text(
                                `(${parseFloat(ranges[key].min).toFixed(2)} - ${parseFloat(ranges[key].max).toFixed(2)})`
                            );
                        });
                    } else {
                        $('#error-message').text("Rentang data tidak valid.").show();
                    }
                },
                error: function() {
                    $('#error-message').text("Terjadi kesalahan saat memuat rentang data.")
                        .show();
                }
            });
        }

        function validateInput(field, value) {
            if (!ranges[field]) return `Rentang untuk ${field} tidak ditemukan.`;

            const min = Number(ranges[field].min).toFixed(2);
            const max = Number(ranges[field].max).toFixed(2);
            const val = Number(value).toFixed(2);

            if (val < min || val > max) {
                return `Nilai harus antara ${min} dan ${max}.`;
            }

            return null;
        }


        $('#form-id input[type="text"]').on('input', function() {
            const field = this.id.replace('nilai_', '');
            const numericValue = parseFloat(this.value.trim());
            const errorElement = $(`#error_${this.id}`);

            if (isNaN(numericValue)) {
                $(this).css('border', '2px solid red');
                errorElement.text("Nilai harus angka dan tidak boleh kosong.");
            } else {
                const errorMessage = validateInput(field, numericValue);
                $(this).css('border', errorMessage || '2px solid lightgreen');
                errorElement.text(errorMessage || "");
            }
        });

        $('#form-id').on('submit', function(event) {
            event.preventDefault();

            let isValid = true;
            let validationResults = [];
            let inputData = {};

            $('#form-id input[type="text"]').each(function() {
                const field = this.id.replace('nilai_', '');
                const numericValue = parseFloat(this.value.trim());
                const errorElement = $(`#error_${this.id}`);

                if (isNaN(numericValue)) {
                    isValid = false;
                    validationResults.push(
                        `${field}: Nilai harus angka dan tidak boleh kosong.`);
                    $(this).css('border', '2px solid red');
                    errorElement.text("Nilai harus angka dan tidak boleh kosong.");
                } else {
                    const errorMessage = validateInput(field, numericValue);
                    if (errorMessage) {
                        isValid = false;
                        validationResults.push(`${field}: ${errorMessage}`);
                        $(this).css('border', '2px solid red');
                        errorElement.text(errorMessage);
                    } else {
                        $(this).css('border', '2px solid lightgreen');
                        errorElement.text("");
                        inputData[field] = numericValue;
                    }
                }
            });

            function showNotification(message) {
                let notification = document.getElementById("notification");
                notification.innerHTML = message;
                notification.style.display = "block";

                setTimeout(() => {
                    notification.style.display = "none";
                }, 500); // Hilang setelah 2 detik
            }

            if (isValid) {
                saveSearchResults(inputData, 'form-input');

                showNotification("✅ Form submitted successfully!");
                setTimeout(() => {
                    window.location.href = "data_lko2.php";
                }, 500);
            } else {
                showNotification("❌ Validation failed!");
            }

        });

        function saveSearchResults(data, type) {
            if (!data || Object.keys(data).length === 0 || !type) {
                console.error("Data kosong atau tipe tidak valid sebelum dikirim.");
                return;
            }

            $.ajax({
                url: '../process/save_search_results.php',
                method: 'POST',
                data: {
                    results: JSON.stringify(data),
                    type: type,
                },
                success: function(response) {
                    console.log("Response dari server:", response);
                },
                error: function(xhr, status, error) {
                    console.error("Gagal menyimpan data:", error);
                }
            });
        }

        fetchRangeData();
    });
    const ws = new WebSocket("ws://localhost:8765");

    ws.onopen = () => {
        console.log("🔌 WebSocket connected");

        // Kirim sinyal ke ESP32 untuk menyalakan LED saat halaman ini dibuka
        ws.send("true");


        ws.onclose = () => {
            console.log("⚠️ WebSocket closed");
        };

    };

    function adjustTableHeights() {
        const availableHeight = window.innerHeight - 300;
        document.querySelectorAll('.table-responsive').forEach(el => {
            el.style.maxHeight = `${availableHeight / 2}px`;
        });
    }

    window.addEventListener('resize', adjustTableHeights);
    adjustTableHeights();
    </script>

</body>

</html>
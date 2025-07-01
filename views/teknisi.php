<?php 
include '../db/connection.php'; 
include '../process/auth.php'; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Montserrat:wght@400;600&display=swap"
        rel="stylesheet">
    <link href="../public/css/teknisi.css?v=<?php echo time(); ?>" rel="stylesheet">


    <title>Teknisi Page</title>
</head>

<body>
    <div class="wrapper">
        <div class="sidebar">
            <h4><?= "Welcome Teknisi"; ?></h4>

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




            <form action="../process/logout.php" method="POST">
                <input type="hidden" name="nik" value="<?php echo htmlspecialchars($user['nik']); ?>">
                <button type="submit" class="logout"> 🚪 Logout</button>
            </form>

        </div>

        <div class="content">
            <div class="card">
                <h2>Import File</h2>
                <form action="../process/import.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="file" class="form-label">Import File (CSV, XLS, XLSX):</label>
                        <input type="file" class="form-control" name="file" id="file" accept=".csv, .xls, .xlsx"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="dataType" class="form-label">Select Data Type:</label>
                        <select class="form-select" name="dataType" id="dataType" required>
                            <option value="">-- Select Data Type --</option>
                            <option value="terminal">Terminal</option>
                            <option value="crimping">Crimping</option>
                            <option value="cfm">CFM</option>
                            <option value="stroke">Stroke</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Import Data</button>
                </form>
            </div>
            <hr>

            <div class="card">
                <h2>Data Terminal</h2>
                <div class="download-buttons">
                    <a href="../process/export.php?dataType=terminal&format=xlsx" class="btn btn-primary">Download
                        (Excel)</a>
                    <a href="../process/export.php?dataType=terminal&format=csv"
                        class="btn btn-outline-primary">Download (CSV)</a>
                    <a href="../process/delete_all.php?table=data_kanban" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete all data?');">
                        Delete All Data
                    </a>
                </div>
                <div class="search-container mb-3">
                    <input type="text" id="terminalSearch" class="form-control" placeholder="Search all fields...">
                </div>
                <div class="table-container">

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Machine</th>
                                <th>npg</th>
                                <th>Nomor Proses</th>
                                <th>Nomor Control</th>
                                <th>kind</th>
                                <th>size</th>
                                <th>col</th>
                                <th>c_l</th>
                                <th>term_b</th>
                                <th>strip_b</th>
                                <th>half_strip_b</th>
                                <th>man_b</th>
                                <th>acc_b1</th>
                                <th>term_a</th>
                                <th>strip_a</th>
                                <th>half_strip_a</th>
                                <th>man_a</th>
                                <th>acc_a1</th>
                                <th>qty</th>
                                <th>Status</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php
                     $result = $conn->query("SELECT id, machine, npg, noproc, ctrl_no, kind, size, col, c_l, term_b, strip_b, half_strip_b, man_b, acc_b1, term_a, strip_a, half_strip_a, man_a, acc_a1, qty FROM data_kanban ORDER BY id ASC");
                     if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    
                                    <td>" . htmlspecialchars($row['machine']) . "</td>
                                    <td>" . htmlspecialchars($row['npg']) . "</td>
                                    <td>" . htmlspecialchars($row['noproc']) . "</td>
                                    <td>" . htmlspecialchars($row['ctrl_no']) . "</td>
                                    <td>" . htmlspecialchars($row['kind']) . "</td>
                                    <td>" . htmlspecialchars($row['size']) . "</td>
                                    <td>" . htmlspecialchars($row['col']) . "</td>
                                    <td>" . htmlspecialchars($row['c_l']) . "</td>
                                    <td>" . htmlspecialchars($row['term_b']) . "</td>
                                    <td>" . htmlspecialchars($row['strip_b']) . "</td>
                                    <td>" . htmlspecialchars($row['half_strip_b']) . "</td>
                                    <td>" . htmlspecialchars($row['man_b']) . "</td>
                                    <td>" . htmlspecialchars($row['acc_b1']) . "</td>
                                    <td>" . htmlspecialchars($row['term_a']) . "</td>
                                    <td>" . htmlspecialchars($row['strip_a']) . "</td>
                                    <td>" . htmlspecialchars($row['half_strip_a']) . "</td>
                                    <td>" . htmlspecialchars($row['man_a']) . "</td>
                                    <td>" . htmlspecialchars($row['acc_a1']) . "</td>
                                    <td>" . htmlspecialchars($row['qty']) . "</td>
                                    <td class='action-buttons'>
                                        <button class='edit-btn' 
                                        data-id='" . htmlspecialchars($row['id']) . "' 
                                        data-machine='" . htmlspecialchars($row['machine']) . "' 
                                        data-npg='" . htmlspecialchars($row['npg']) . "' 
                                        data-noproc='" . htmlspecialchars($row['noproc']) . "' 
                                        data-ctrl_no='" . htmlspecialchars($row['ctrl_no']) . "' 
                                        data-kind='" . htmlspecialchars($row['kind']) . "' 
                                        data-size='" . htmlspecialchars($row['size']) . "'
                                        data-col='" . htmlspecialchars($row['col']) . "'
                                        data-c_l='" . htmlspecialchars($row['c_l']) . "'
                                        data-term_b='" . htmlspecialchars($row['term_b']) . "'
                                        data-strip_b='" . htmlspecialchars($row['strip_b']) . "'
                                        data-half_strip_b='" . htmlspecialchars($row['half_strip_b']) . "'
                                        data-man_b='" . htmlspecialchars($row["man_b"]) . "'
                                        data-acc_b1='" . htmlspecialchars($row['acc_b1']) . "'
                                        data-term_a='" . htmlspecialchars($row['term_a']) . "'
                                        data-strip_a='" . htmlspecialchars($row['strip_a']) . "'
                                        data-half_strip_a='" . htmlspecialchars($row['half_strip_a']) . "'
                                        data-man_a='" . htmlspecialchars($row["man_a"]) . "'
                                        data-acc_a1='" . htmlspecialchars($row['acc_a1']) . "'    
                                        data-qty='" . htmlspecialchars($row['qty']) . "'
                                        data-bs-toggle='modal' data-bs-target='#editModalkanban'>Edit</button>
                                        
                                             <button class='delete' 
                                            onclick='return confirm(\"Are you sure you want to delete this data?\")'>
                                            <a href='../process/delete.php?table=data_kanban&key=id&value=" . $row['id'] . "' style='color: white; text-decoration: none;'>Delete</a>
                                        </button>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr>          
                                <td colspan='20'>No data available.</td>
                              </tr>";
                    }
                    ?>
                            <!-- Modal -->
                            <div class="modal fade" id="editModalkanban" tabindex="-1" aria-labelledby="editModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">DATA TERMINAL</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="../process/edit_kanban.php" method="POST">
                                                <input type="hidden" name="id" id="edit-id">

                                                <div class="mb-3">
                                                    <label for="edit-machine" class="form-label">machine:</label>
                                                    <input type="text" class="form-control" name="machine"
                                                        id="edit-machine" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-npg" class="form-label">npg:</label>
                                                    <input type="text" class="form-control" name="npg" id="edit-npg"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-noproc" class="form-label">noproc:</label>
                                                    <input type="text" class="form-control" name="noproc"
                                                        id="edit-noproc" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-ctrl_no" class="form-label">ctrl_no:</label>
                                                    <input type="text" class="form-control" name="ctrl_no"
                                                        id="edit-ctrl_no" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-kind" class="form-label">kind:</label>
                                                    <input type="text" class="form-control" name="kind" id="edit-kind"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-size" class="form-label">size:</label>
                                                    <input type="text" class="form-control" name="size" id="edit-size1"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-col" class="form-label">col:</label>
                                                    <input type="text" class="form-control" name="col" id="edit-col"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-c_l" class="form-label">c_l:</label>
                                                    <input type="text" class="form-control" name="c_l" id="edit-c_l"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-term_b" class="form-label">term_b:</label>
                                                    <input type="text" class="form-control" name="term_b"
                                                        id="edit-term_b" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-strip_b" class="form-label">strip_b:</label>
                                                    <input type="text" class="form-control" name="strip_b"
                                                        id="edit-strip_b">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-half_strip_b"
                                                        class="form-label">half_strip_b:</label>
                                                    <input type="text" class="form-control" name="half_strip_b"
                                                        id="edit-half_strip_b">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-man_b" class="form-label">man_b:</label>
                                                    <input type="text" class="form-control" name="man_b" id="edit-man_b"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-acc_b1" class="form-label">acc_b1:</label>
                                                    <input type="text" class="form-control" name="acc_b1"
                                                        id="edit-acc_b1" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-term_a" class="form-label">term_a:</label>
                                                    <input type="text" class="form-control" name="term_a"
                                                        id="edit-term_a" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-strip_a" class="form-label">strip_a:</label>
                                                    <input type="text" class="form-control" name="strip_a"
                                                        id="edit-strip_a" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-half_strip_a"
                                                        class="form-label">half_strip_a:</label>
                                                    <input type="text" class="form-control" name="half_strip_a"
                                                        id="edit-half_strip_a" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-man_a" class="form-label">man_a:</label>
                                                    <input type="text" class="form-control" name="man_a" id="edit-man_a"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-acc_a1" class="form-label">acc_a1:</label>
                                                    <input type="text" class="form-control" name="acc_a1"
                                                        id="edit-acc_a1" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-qty" class="form-label">qty:</label>
                                                    <input type="text" class="form-control" name="qty" id="edit-qty"
                                                        required>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!---batas-->
                            <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const editButtons = document.querySelectorAll(".edit-btn");

                                editButtons.forEach(button => {
                                    button.addEventListener("click", function() {
                                        // Ambil data dari tombol yang diklik
                                        const id = this.getAttribute("data-id");
                                        const machine = this.getAttribute("data-machine");
                                        const npg = this.getAttribute("data-npg");
                                        const noproc = this.getAttribute("data-noproc");
                                        const ctrl_no = this.getAttribute("data-ctrl_no");
                                        const kind = this.getAttribute("data-kind");
                                        const size = this.getAttribute("data-size");
                                        const col = this.getAttribute("data-col");
                                        const c_l = this.getAttribute("data-c_l");
                                        const term_b = this.getAttribute("data-term_b");
                                        const strip_b = this.getAttribute("data-strip_b");
                                        const half_strip_b = this.getAttribute(
                                            "data-half_strip_b");
                                        const man_b = this.getAttribute("data-man_b");
                                        const acc_b1 = this.getAttribute("data-acc_b1");
                                        const term_a = this.getAttribute("data-term_a");
                                        const strip_a = this.getAttribute("data-strip_a");
                                        const half_strip_a = this.getAttribute(
                                            "data-half_strip_a");
                                        const man_a = this.getAttribute("data-man_a");
                                        const acc_a1 = this.getAttribute("data-acc_a1");
                                        const qty = this.getAttribute("data-qty");

                                        // Masukkan ke dalam form modal
                                        document.getElementById("edit-id").value = id;
                                        document.getElementById("edit-machine").value = machine;
                                        document.getElementById("edit-npg").value = npg;
                                        document.getElementById("edit-noproc").value = noproc;
                                        document.getElementById("edit-ctrl_no").value = ctrl_no;
                                        document.getElementById("edit-kind").value = kind;
                                        document.getElementById("edit-size1").value = size;
                                        document.getElementById("edit-col").value = col;
                                        document.getElementById("edit-c_l").value = c_l;
                                        document.getElementById("edit-term_b").value = term_b;
                                        document.getElementById("edit-strip_b").value = strip_b;
                                        document.getElementById("edit-half_strip_b").value =
                                            half_strip_b;
                                        document.getElementById("edit-man_b").value = man_b;
                                        document.getElementById("edit-acc_b1").value = acc_b1;
                                        document.getElementById("edit-term_a").value = term_a;
                                        document.getElementById("edit-strip_a").value = strip_a;
                                        document.getElementById("edit-half_strip_a").value =
                                            half_strip_a;
                                        document.getElementById("edit-man_a").value = man_a;
                                        document.getElementById("edit-acc_a1").value = acc_a1;
                                        document.getElementById("edit-qty").value = qty;
                                    });
                                });
                            });
                            </script>

                        </tbody>
                    </table>

                </div>
            </div>
            <!-- Add the JavaScript at the end -->
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('terminalSearch');
                    const tableBody = document.querySelector('.table-container tbody');
                    const rows = tableBody.querySelectorAll('tr');
                    
                    searchInput.addEventListener('input', function() {
                        const searchTerm = this.value.toLowerCase();
                        
                        rows.forEach(row => {
                            const cells = row.querySelectorAll('td');
                            let rowMatches = false;
                            
                            // Skip the last cell (action buttons)
                            for (let i = 0; i < cells.length - 1; i++) {
                                const cellText = cells[i].textContent.toLowerCase();
                                if (cellText.includes(searchTerm)) {
                                    rowMatches = true;
                                    break;
                                }
                            }
                            
                            row.style.display = rowMatches ? '' : 'none';
                        });
                    });
                });
                </script>

                

            <div class="card">
                <h2>Data CFM</h2>
                <div class="download-buttons">
                    <a href="../process/export.php?dataType=cfm&format=xlsx" class="btn btn-info">Download (Excel)</a>
                    <a href="../process/export.php?dataType=cfm&format=csv" class="btn btn-outline-primary">Download
                        (CSV)</a>
                    <a href="../process/delete_all.php?table=data_cfm" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete all data?');">
                        Delete All Data
                    </a>
                </div>
                <div class="search-container mb-3">
                    <input type="text" id="cfmSearch" class="form-control" placeholder="Search all fields...">
                </div>
                <div class="table-container1">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Carline</th>
                                <th>Mesin</th>
                                <th>No</th>
                                <th>Applicator</th>
                                <th>Man No</th>
                                <th>Kind</th>
                                <th>Size</th>
                                <th>Knop/Spacer</th>
                                <th>Dial</th>
                                <th>No Prog</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                    $result = $conn->query("SELECT id, carline, mesin, no, applicator, man_no, kind, size, knop_spacer, dial, no_prog FROM data_cfm ORDER BY id ASC");
                    if ($result->num_rows > 0) {
                    
                    
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['carline']) . "</td>
                                    <td>" . htmlspecialchars($row['mesin']) . "</td>
                                    <td>" . htmlspecialchars($row['no']) . "</td>
                                    <td>" . htmlspecialchars($row['applicator']) . "</td>
                                    <td>" . htmlspecialchars($row['man_no']) . "</td>
                                    <td>" . htmlspecialchars($row['kind']) . "</td>
                                    <td>" . htmlspecialchars($row['size']) . "</td>
                                    <td>" . htmlspecialchars($row['knop_spacer']) . "</td>
                                    <td>" . htmlspecialchars($row['dial']) . "</td>
                                    <td>" . htmlspecialchars($row['no_prog']) . "</td>
                                    <td class='action-buttons'>
                                    <button class='edit-btn' 
                                        data-id='" . htmlspecialchars($row['id']) . "' 
                                        data-carline='" . htmlspecialchars($row['carline']) . "' 
                                        data-mesin='" . htmlspecialchars($row['mesin']) . "' 
                                        data-no='" . htmlspecialchars($row['no']) . "' 
                                        data-applicator='" . htmlspecialchars($row['applicator']) . "' 
                                        data-man_no='" . htmlspecialchars($row['man_no']) . "' 
                                        data-kind='" . htmlspecialchars($row['kind']) . "' 
                                        data-size='" . htmlspecialchars($row['size']) . "' 
                                        data-knop_spacer='" . htmlspecialchars($row['knop_spacer']) . "' 
                                        data-dial='" . htmlspecialchars($row['dial']) . "' 
                                        data-no_prog='" . htmlspecialchars($row['no_prog']) . "' 
                                        data-bs-toggle='modal' data-bs-target='#editModalcfm'>Edit</button>
                                
                                            <button class='delete' 
                                            onclick='return confirm(\"Are you sure you want to delete this data?\")'>
                                            <a href='../process/delete.php?table=data_cfm&key=id&value=" . $row['id'] . "' style='color: white; text-decoration: none;'>Delete</a>
                                        </button>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr>
                                <td colspan='11'>No data available.</td>
                              </tr>";
                    }
                    ?>
                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModalcfm" tabindex="-1" aria-labelledby="editModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Edit Data CFM</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="../process/edit_cfm.php" method="POST">
                                                <input type="hidden" name="id" id="edit-id1">

                                                <div class="mb-3">
                                                    <label for="edit-carline" class="form-label">Carline:</label>
                                                    <input type="text" class="form-control" name="carline"
                                                        id="edit-carline" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-mesin" class="form-label">Mesin:</label>
                                                    <input type="text" class="form-control" name="mesin" id="edit-mesin"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-no" class="form-label">No:</label>
                                                    <input type="text" class="form-control" name="no" id="edit-no"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-applicator" class="form-label">Applicator:</label>
                                                    <input type="text" class="form-control" name="applicator"
                                                        id="edit-applicator" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-man_no" class="form-label">Man No:</label>
                                                    <input type="text" class="form-control" name="man_no"
                                                        id="edit-man_no" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-kind" class="form-label">Kind:</label>
                                                    <input type="text" class="form-control" name="kind" id="edit-kind2"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-size" class="form-label">Size:</label>
                                                    <input type="text" class="form-control" name="size" id="edit-size2"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-knop_spacer"
                                                        class="form-label">Knop/Spacer:</label>
                                                    <input type="text" class="form-control" name="knop_spacer"
                                                        id="edit-knop_spacer" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-dial" class="form-label">Dial:</label>
                                                    <input type="text" class="form-control" name="dial" id="edit-dial"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-no_prog" class="form-label">No Prog:</label>
                                                    <input type="text" class="form-control" name="no_prog"
                                                        id="edit-no_prog" required>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const editButtons = document.querySelectorAll(".edit-btn");

                                editButtons.forEach(button => {
                                    button.addEventListener("click", function() {
                                        // Ambil data dari tombol yang diklik
                                        const id = this.getAttribute("data-id");
                                        const carline = this.getAttribute("data-carline");
                                        const mesin = this.getAttribute("data-mesin");
                                        const no = this.getAttribute("data-no");
                                        const applicator = this.getAttribute("data-applicator");
                                        const man_no = this.getAttribute("data-man_no");
                                        const kind = this.getAttribute("data-kind");
                                        const size = this.getAttribute("data-size");
                                        const knop_spacer = this.getAttribute(
                                            "data-knop_spacer");
                                        const dial = this.getAttribute("data-dial");
                                        const no_prog = this.getAttribute("data-no_prog");

                                        // Masukkan ke dalam form modal
                                        document.getElementById("edit-id1").value = id;
                                        document.getElementById("edit-carline").value = carline;
                                        document.getElementById("edit-mesin").value = mesin;
                                        document.getElementById("edit-no").value = no;
                                        document.getElementById("edit-applicator").value =
                                            applicator;
                                        document.getElementById("edit-man_no").value = man_no;
                                        document.getElementById("edit-kind2").value = kind;
                                        document.getElementById("edit-size2").value = size;
                                        document.getElementById("edit-knop_spacer").value =
                                            knop_spacer;
                                        document.getElementById("edit-dial").value = dial;
                                        document.getElementById("edit-no_prog").value = no_prog;
                                    });
                                });
                            });
                            </script>
                        </tbody>
                    </table>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('cfmSearch');
                    const tableBody = document.querySelector('.table-container1 tbody');
                    const rows = tableBody.querySelectorAll('tr');
                    
                    searchInput.addEventListener('input', function() {
                        const searchCfm = this.value.toLowerCase();
                        
                        rows.forEach(row => {
                            const cells = row.querySelectorAll('td');
                            let rowMatches = false;
                            
                            // Skip the last cell (action buttons)
                            for (let i = 0; i < cells.length - 1; i++) {
                                const cellText = cells[i].textContent.toLowerCase();
                                if (cellText.includes(searchCfm)) {
                                    rowMatches = true;
                                    break;
                                }
                            }
                            
                            row.style.display = rowMatches ? '' : 'none';
                        });
                    });
                });
                </script>

            <div class="card">
                <h2>Data Crimping</h2>
                <div class="download-buttons">
                    <a href="../process/export.php?dataType=crimping&format=xlsx" class="btn btn-success">Download
                        (Excel)</a>
                    <a href="../process/export.php?dataType=crimping&format=csv"
                        class="btn btn-outline-primary">Download (CSV)</a>
                    <a href="../process/delete_all.php?table=data_crimping" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete all data?');">
                        Delete All Data
                    </a>
                </div>
                <div class="search-container mb-3">
                    <input type="text" id="crimpingSearch" class="form-control" placeholder="Search all fields...">
                </div>
                <div class="table-container2">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mesin</th>
                                <th>Term</th>
                                <th>Wire</th>
                                <th>Size</th>
                                <th>ACC</th>
                                <th>F C/H</th>
                                <th>Toleransi 1</th>
                                <th>1/2 F C/H</th>
                                <th>R C/H</th>
                                <th>Toleransi 2</th>
                                <th>1/2 R C/H</th>
                                <th>F C/W Min</th>
                                <th>F C/W Max</th>
                                <th>R C/W Min</th>
                                <th>R C/W Max</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                    $result = $conn->query("SELECT no, mesin, term, wire, size, acc, f_c_h, toleransi1, 1_2_f_c_h, r_c_h, toleransi2, 1_2_r_c_h, f_c_w_min, f_c_w_max, r_c_w_min, r_c_w_max FROM data_crimping ORDER BY no ASC");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['mesin']) . "</td>
                                    <td>" . htmlspecialchars($row['term']) . "</td>
                                    <td>" . htmlspecialchars($row['wire']) . "</td>
                                    <td>" . htmlspecialchars($row['size']) . "</td>
                                    <td>" . htmlspecialchars($row['acc']) . "</td>
                                    <td>" . htmlspecialchars($row['f_c_h']) . "</td>
                                    <td>" . htmlspecialchars($row['toleransi1']) . "</td>
                                    <td>" . htmlspecialchars($row['1_2_f_c_h']) . "</td>
                                    <td>" . htmlspecialchars($row['r_c_h']) . "</td>
                                    <td>" . htmlspecialchars($row['toleransi2']) . "</td>
                                    <td>" . htmlspecialchars($row['1_2_r_c_h']) . "</td>
                                    <td>" . htmlspecialchars($row['f_c_w_min']) . "</td>
                                    <td>" . htmlspecialchars($row['f_c_w_max']) . "</td>
                                    <td>" . htmlspecialchars($row['r_c_w_min']) . "</td>
                                    <td>" . htmlspecialchars($row['r_c_w_max']) . "</td>
                                    <td class='action-buttons'>
                                        <button class='edit-btn' 
                                        data-no='" . htmlspecialchars($row['no']) . "' 
                                        data-mesin='" . htmlspecialchars($row['mesin']) . "' 
                                        data-term='" . htmlspecialchars($row['term']) . "' 
                                        data-wire='" . htmlspecialchars($row['wire']) . "' 
                                        data-size='" . htmlspecialchars($row['size']) . "' 
                                        data-acc='" . htmlspecialchars($row['acc']) . "' 
                                        data-f_c_h='" . htmlspecialchars($row['f_c_h']) . "'
                                        data-toleransi1='" . htmlspecialchars($row['toleransi1']) . "'
                                        data-1_2_f_c_h='" . htmlspecialchars($row['1_2_f_c_h']) . "'
                                        data-r_c_h='" . htmlspecialchars($row['r_c_h']) . "'
                                        data-toleransi2='" . htmlspecialchars($row['toleransi2']) . "'
                                        data-1_2_r_c_h='" . htmlspecialchars($row['1_2_r_c_h']) . "'
                                        data-f_c_w_min='" . htmlspecialchars($row["f_c_w_min"]) . "'
                                        data-f_c_w_max='" . htmlspecialchars($row['f_c_w_max']) . "'
                                        data-r_c_w_min='" . htmlspecialchars($row['r_c_w_min']) . "'
                                        data-r_c_w_max='" . htmlspecialchars($row['r_c_w_max']) . "'
                                        data-bs-toggle='modal' data-bs-target='#editModalcrimping'>Edit</button>

                                         <button class='delete' 
                                            onclick='return confirm(\"Are you sure you want to delete this data?\")'>
                                            <a href='../process/delete.php?table=data_crimping&key=no&value=" . $row['no'] . "' style='color: white; text-decoration: none;'>Delete</a>
                                        </button>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='16'>No Data Available.</td></tr>";
                    }
                    ?>
                            <!-- Modal Edit Data Crimping -->
                            <div class="modal fade" id="editModalcrimping" tabindex="-1"
                                aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <!-- modal-lg untuk ukuran yang lebih besar -->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Edit Data Crimping</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="../process/edit_crimping.php" method="POST">
                                                <!-- Input hidden untuk menyimpan nomor data -->
                                                <input type="hidden" name="no" id="edit-no-crimping">
                                                <!-- Form fields untuk data crimping -->


                                                <div class="mb-3">
                                                    <label for="edit-mesin" class="form-label">Mesin:</label>
                                                    <input type="text" class="form-control" name="mesin"
                                                        id="edit-mesin1" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit-term" class="form-label">Term:</label>
                                                    <input type="text" class="form-control" name="term" id="edit-term"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit-wire" class="form-label">Wire:</label>
                                                    <input type="text" class="form-control" name="wire" id="edit-wire"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit-size" class="form-label">Size:</label>
                                                    <input type="text" class="form-control" name="size" id="edit-size"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit-acc" class="form-label">ACC:</label>
                                                    <input type="text" class="form-control" name="acc" id="edit-acc"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit-f_c_h" class="form-label">F C/H:</label>
                                                    <input type="text" class="form-control" name="f_c_h" id="edit-f_c_h"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit-toleransi1" class="form-label">Toleransi 1:</label>
                                                    <input type="text" class="form-control" name="toleransi1"
                                                        id="edit-toleransi1" required>
                                                </div>


                                                <div class="mb-3">
                                                    <label for="edit-1_2_f_c_h" class="form-label">1/2 F C/H:</label>
                                                    <input type="text" class="form-control" name="1_2_f_c_h"
                                                        id="edit-1_2_f_c_h" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit-r_c_h" class="form-label">R C/H:</label>
                                                    <input type="text" class="form-control" name="r_c_h" id="edit-r_c_h"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit-toleransi2" class="form-label">Toleransi 2:</label>
                                                    <input type="text" class="form-control" name="toleransi2"
                                                        id="edit-toleransi2" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit-1_2_r_c_h" class="form-label">1/2 R C/H:</label>
                                                    <input type="text" class="form-control" name="1_2_r_c_h"
                                                        id="edit-1_2_r_c_h" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit-f_c_w_min" class="form-label">F C/W Min:</label>
                                                    <input type="text" class="form-control" name="f_c_w_min"
                                                        id="edit-f_c_w_min" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit-f_c_w_max" class="form-label">F C/W Max:</label>
                                                    <input type="text" class="form-control" name="f_c_w_max"
                                                        id="edit-f_c_w_max" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit-r_c_w_min" class="form-label">R C/W Min:</label>
                                                    <input type="text" class="form-control" name="r_c_w_min"
                                                        id="edit-r_c_w_min" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit-r_c_w_max" class="form-label">R C/W Max:</label>
                                                    <input type="text" class="form-control" name="r_c_w_max"
                                                        id="edit-r_c_w_max" required>
                                                </div>



                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const editButtons = document.querySelectorAll(".edit-btn");

                                editButtons.forEach(button => {
                                    button.addEventListener("click", function() {


                                        // Ambil data dari atribut data-*
                                        const no = this.getAttribute("data-no");
                                        const mesin = this.getAttribute("data-mesin");
                                        const term = this.getAttribute("data-term");
                                        const wire = this.getAttribute("data-wire");
                                        const size = this.getAttribute("data-size");
                                        const acc = this.getAttribute("data-acc");
                                        const f_c_h = this.getAttribute("data-f_c_h");
                                        const toleransi1 = this.getAttribute("data-toleransi1");
                                        const _1_2_f_c_h = this.getAttribute("data-1_2_f_c_h");
                                        const r_c_h = this.getAttribute("data-r_c_h");
                                        const toleransi2 = this.getAttribute("data-toleransi2");
                                        const _1_2_r_c_h = this.getAttribute("data-1_2_r_c_h");
                                        const f_c_w_min = this.getAttribute("data-f_c_w_min");
                                        const f_c_w_max = this.getAttribute("data-f_c_w_max");
                                        const r_c_w_min = this.getAttribute("data-r_c_w_min");
                                        const r_c_w_max = this.getAttribute("data-r_c_w_max");

                                        // Masukkan data ke dalam form modal
                                        document.getElementById("edit-no-crimping").value = no;
                                        document.getElementById("edit-mesin1").value = mesin;
                                        document.getElementById("edit-term").value = term;
                                        document.getElementById("edit-wire").value = wire;
                                        document.getElementById("edit-size").value = size;
                                        document.getElementById("edit-acc").value = acc;
                                        document.getElementById("edit-f_c_h").value = f_c_h;
                                        document.getElementById("edit-toleransi1").value =
                                            toleransi1;
                                        document.getElementById("edit-1_2_f_c_h").value =
                                            _1_2_f_c_h;
                                        document.getElementById("edit-r_c_h").value = r_c_h;
                                        document.getElementById("edit-toleransi2").value =
                                            toleransi2;
                                        document.getElementById("edit-1_2_r_c_h").value =
                                            _1_2_r_c_h;
                                        document.getElementById("edit-f_c_w_min").value =
                                            f_c_w_min;
                                        document.getElementById("edit-f_c_w_max").value =
                                            f_c_w_max;
                                        document.getElementById("edit-r_c_w_min").value =
                                            r_c_w_min;
                                        document.getElementById("edit-r_c_w_max").value =
                                            r_c_w_max;


                                    });
                                });
                            });
                            </script>
                        </tbody>
                    </table>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('crimpingSearch');
                    const tableBody = document.querySelector('.table-container2 tbody');
                    const rows = tableBody.querySelectorAll('tr');
                    
                    searchInput.addEventListener('input', function() {
                        const searchCrm = this.value.toLowerCase();
                        
                        rows.forEach(row => {
                            const cells = row.querySelectorAll('td');
                            let rowMatches = false;
                            
                            // Skip the last cell (action buttons)
                            for (let i = 0; i < cells.length - 1; i++) {
                                const cellText = cells[i].textContent.toLowerCase();
                                if (cellText.includes(searchCrm)) {
                                    rowMatches = true;
                                    break;
                                }
                            }
                            
                            row.style.display = rowMatches ? '' : 'none';
                        });
                    });
                });
                </script>

            <div class="card">
                <h2>Data Stroke</h2>
                <div class="download-buttons">
                    <a href="../process/export.php?dataType=stroke&format=xlsx" class="btn btn-warning">Download
                        (Excel)</a>
                    <a href="../process/export.php?dataType=stroke&format=csv" class="btn btn-outline-primary">Download
                        (CSV)</a>
                    <a href="../process/delete_all.php?table=data_stroke" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete all data?');">
                        Delete All Data
                    </a>
                </div>
                <div class="search-container mb-3">
                    <input type="text" id="strokeSearch" class="form-control" placeholder="Search all fields...">
                </div>
                <div class="table-container3">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Carline</th>
                                <th>Mesin</th>
                                <th>Applicator</th>
                                <th>Max Stroke</th>
                                <th>Current Stroke</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                    $result = $conn->query("SELECT no, carline, mesin, applicator, max_stroke, current_stroke FROM data_stroke ORDER BY no ASC");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['carline']) . "</td>
                                    <td>" . htmlspecialchars($row['mesin']) . "</td>
                                    <td>" . htmlspecialchars($row['applicator']) . "</td>
                                    <td>" . htmlspecialchars($row['max_stroke']) . "</td>
                                    <td>" . htmlspecialchars($row['current_stroke']) . "</td>
                                    <td class='action-buttons'>
                                        <button class='edit-btn' 
                                        data-no='" . htmlspecialchars($row['no']) . "' 
                                        data-carline='" . htmlspecialchars($row['carline']) . "' 
                                        data-mesin='" . htmlspecialchars($row['mesin']) . "' 
                                        data-applicator='" . htmlspecialchars($row['applicator']) . "' 
                                        data-max_stroke='" . htmlspecialchars($row['max_stroke']) . "' 
                                        data-current_stroke='" . htmlspecialchars($row['current_stroke']) . "' 
                                        data-bs-toggle='modal' data-bs-target='#editModalstroke'>Edit</button>

                                         <button class='delete' 
                                            onclick='return confirm(\"Are you sure you want to delete this data?\")'>
                                            <a href='../process/delete.php?table=data_stroke&key=no&value=" . $row['no'] . "' style='color: white; text-decoration: none;'>Delete</a>
                                        </button>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No data available.</td></tr>";
                    }
                    ?>

                            <!-- Modal -->
                            <div class="modal fade" id="editModalstroke" tabindex="-1" aria-labelledby="editModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Edit Data Stroke</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="../process/edit_stroke.php" method="POST">
                                                <input type="hidden" name="no" id="edit-no1">

                                                <div class="mb-3">
                                                    <label for="edit-carline" class="form-label">Carline:</label>
                                                    <input type="text" class="form-control" name="carline"
                                                        id="edit-carline2" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-mesin" class="form-label">Mesin:</label>
                                                    <input type="text" class="form-control" name="mesin"
                                                        id="edit-mesin2" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-applicator" class="form-label">Applicator:</label>
                                                    <input type="text" class="form-control" name="applicator"
                                                        id="edit-applicator2" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-max_stroke" class="form-label">Max Stroke:</label>
                                                    <input type="text" class="form-control" name="max_stroke"
                                                        id="edit-max_stroke" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-current_stroke" class="form-label">Current
                                                        Stroke:</label>
                                                    <input type="text" class="form-control" name="current_stroke"
                                                        id="edit-current_stroke" required>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!---batas-->
                            <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const editButtons = document.querySelectorAll(".edit-btn");

                                editButtons.forEach(button => {
                                    button.addEventListener("click", function() {
                                        // Ambil data dari tombol yang diklik
                                        const no = this.getAttribute("data-no");
                                        const carline = this.getAttribute("data-carline");
                                        const mesin = this.getAttribute("data-mesin");
                                        const applicator = this.getAttribute("data-applicator");
                                        const maxStroke = this.getAttribute("data-max_stroke");
                                        const currentStroke = this.getAttribute(
                                            "data-current_stroke");

                                        // Masukkan ke dalam form modal
                                        document.getElementById("edit-no1").value = no;
                                        document.getElementById("edit-carline2").value =
                                            carline;
                                        document.getElementById("edit-mesin2").value = mesin;
                                        document.getElementById("edit-applicator2").value =
                                            applicator;
                                        document.getElementById("edit-max_stroke").value =
                                            maxStroke;
                                        document.getElementById("edit-current_stroke").value =
                                            currentStroke;
                                    });
                                });
                            });
                            </script>

                        </tbody>
                    </table>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('strokeSearch');
                    const tableBody = document.querySelector('.table-container3 tbody');
                    const rows = tableBody.querySelectorAll('tr');
                    
                    searchInput.addEventListener('input', function() {
                        const searchStroke = this.value.toLowerCase();
                        
                        rows.forEach(row => {
                            const cells = row.querySelectorAll('td');
                            let rowMatches = false;
                            
                            // Skip the last cell (action buttons)
                            for (let i = 0; i < cells.length - 1; i++) {
                                const cellText = cells[i].textContent.toLowerCase();
                                if (cellText.includes(searchStroke)) {
                                    rowMatches = true;
                                    break;
                                }
                            }
                            
                            row.style.display = rowMatches ? '' : 'none';
                        });
                    });
                });
                </script>

            <div class="card">
                <h1>Data LKO</h1>
                <div class="download-buttons">
                    <a href="../process/export.php?dataType=lko&format=xlsx" class="btn btn-warning">Download
                        (Excel)</a>
                    <a href="../process/export.php?dataType=lko&format=csv" class="btn btn-outline-primary">Download
                        (CSV)</a>
                    <a href="../process/delete_all.php?table=data_lko" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete all data?');">
                        Delete All Data
                    </a>
                </div>
                <div class="search-container mb-3">
                    <input type="text" id="lkoSearch" class="form-control" placeholder="Search all fields...">
                </div>
                <div class="table-container4">
                    <table border="1" cellspacing="0" cellpadding="5" class="table table-striped">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Carline</th>
                                <th>Mesin</th>
                                <th>Time</th>
                                <th>Shift</th>
                                <th>No. Control</th>
                                <th>No Issue</th>
                                <th>Scan Kanban</th>
                                <th>Quantity</th>
                                <th>Kind</th>
                                <th>Size</th>
                                <th>COL</th>
                                <th>Terminal</th>
                                <th>Lot Terminal</th>
                                <th>F C/H</th>
                                <th>R C/H</th>
                                <th>F C/W</th>
                                <th>R C/W</th>
                                <th>C/L</th>
                                <th>Kode Defect</th>
                                <th>Qty M</th>
                                <th>Code Error</th>
                                <th>Downtime</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                             $sql = "SELECT * FROM data_lko ORDER BY created_at ASC"; // Ambil semua data
                             $result = $conn->query($sql); while ($row = $result->fetch_assoc()) :
                            ?>
                            <tr>
                                <td><?= $row['user']; ?></td>
                                <td><?= htmlspecialchars($row['carline']) ?></td>
                                <td><?= htmlspecialchars($row['mesin']) ?></td>
                                <td><?= htmlspecialchars($row['time']) ?></td>
                                <td><?= htmlspecialchars($row['shift']) ?></td>
                                <td><?= htmlspecialchars($row['ctrl_no']) ?></td>
                                <td><?= htmlspecialchars($row['noIssue']) ?></td>
                                <td><?= htmlspecialchars($row['scanKanban']) ?></td>
                                <td><?= htmlspecialchars($row['qty']) ?></td>
                                <td><?= htmlspecialchars($row['kind']) ?></td>
                                <td><?= htmlspecialchars($row['size']) ?></td>
                                <td><?= htmlspecialchars($row['col']) ?></td>
                                <td><?= htmlspecialchars($row['terminal']) ?></td>
                                <td><?= htmlspecialchars($row['lotTerminal']) ?></td>
                                <td><?= htmlspecialchars($row['f_c_h']) ?></td>
                                <td><?= htmlspecialchars($row['r_c_h']) ?></td>
                                <td><?= htmlspecialchars($row['f_c_w']) ?></td>
                                <td><?= htmlspecialchars($row['r_c_w']) ?></td>
                                <td><?= htmlspecialchars($row['c_l']) ?></td>
                                <td><?= htmlspecialchars($row['kodeDefect']) ?></td>
                                <td><?= htmlspecialchars($row['qtyM']) ?></td>
                                <td><?= htmlspecialchars($row['code_error']) ?></td>
                                <td><?= htmlspecialchars($row['downtime']) ?></td>
                                <td><?= htmlspecialchars($row['created_at']) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('lkoSearch');
                    const tableBody = document.querySelector('.table-container4 tbody');
                    const rows = tableBody.querySelectorAll('tr');
                    
                    searchInput.addEventListener('input', function() {
                        const searchlko = this.value.toLowerCase();
                        
                        rows.forEach(row => {
                            const cells = row.querySelectorAll('td');
                            let rowMatches = false;
                            
                            // Skip the last cell (action buttons)
                            for (let i = 0; i < cells.length - 1; i++) {
                                const cellText = cells[i].textContent.toLowerCase();
                                if (cellText.includes(searchlko)) {
                                    rowMatches = true;
                                    break;
                                }
                            }
                            
                            row.style.display = rowMatches ? '' : 'none';
                        });
                    });
                });
                </script>


            <div class="card">
                <h2>Downtime</h2>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDowntimeModal">
                    Tambah Downtime
                </button>
                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Item</th>
                                <th>Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        $result_user = $conn->query("SELECT id, kode, item FROM downtime ORDER BY id ASC");

                        if ($result_user->num_rows > 0) {
                            while ($row = $result_user->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['kode']) . "</td>
                                        <td>" . htmlspecialchars($row['item']) . "</td>
                                        <td class='action-buttons'>
                                            <button class='delete' 
                                                onclick='return confirm(\"Are you sure you want to delete this data?\")'>
                                                <a href='../process/delete.php?table=downtime&key=id&value=" . $row['id'] . "' style='color: white; text-decoration: none;'>Delete</a>
                                            </button>
                                        </button>
                                        

                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No data available.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                </div>

             <!-- Modal -->
             <div class="modal fade" id="addDowntimeModal" tabindex="-1" aria-labelledby="addUserModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <form action="../process/add_user.php" method="POST">
                            <input type="hidden" name="table" value="downtime">
                            <div class="mb-3">
                                <label for="kode" class="form-label">Kode:</label>
                                <input type="text" class="form-control" name="kode" id="kode" required>
                            </div>
                            <div class="mb-3">
                                <label for="item" class="form-label">Item:</label>
                                <input type="text" class="form-control" name="item" id="item" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah Downtime</button>
                        </form>

                        </div>
                    </div>
                </div>
             </div>


             <div class="card">
                <h2>Defect</h2>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDefectModal">
                    Tambah Defect
                </button>
                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Item Defect</th>
                                <th>status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        $result_user = $conn->query("SELECT no, item_defect FROM defect ORDER BY no ASC");

                        if ($result_user->num_rows > 0) {
                            while ($row = $result_user->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['item_defect']) . "</td> 
                                       <td class='action-buttons'>
                                            <button class='delete' 
                                                onclick='return confirm(\"Are you sure you want to delete this data?\")'>
                                                <a href='../process/delete.php?table=defect&key=no&value=" . $row['no'] . "' style='color: white; text-decoration: none;'>Delete</a>
                                            </button>
                                        </button>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No data available.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                </div>

            <!-- Modal -->
            <div class="modal fade" id="addDefectModal" tabindex="-1" aria-labelledby="addDefectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="addDefectModalLabel">Tambah Defect</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <form action="../process/add_user.php" method="POST">
                    <!-- Hidden field untuk menentukan tabel -->
                    <input type="hidden" name="table" value="defect">
                    
                    <div class="mb-3">
                        <label for="item_defect" class="form-label">Item Defect:</label>
                        <input type="text" class="form-control" name="item_defect" id="item_defect" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Tambah Defect</button>
                    </form>
                </div>

                </div>
            </div>
            </div>






            <div class="card">
                <h2>Data User</h2>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    Tambah User
                </button>
                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>NIK</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        $result_user = $conn->query("SELECT id, name, nik, role FROM users ORDER BY id ASC");

                        if ($result_user->num_rows > 0) {
                            while ($row = $result_user->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['name']) . "</td>
                                        <td>" . htmlspecialchars($row['nik']) . "</td>
                                        <td>" . htmlspecialchars($row['role']) . "</td>
                                       <td class='action-buttons'>
                                            <button class='delete' 
                                                onclick='return confirm(\"Are you sure you want to delete this data?\")'>
                                                <a href='../process/delete.php?table=users&key=id&value=" . $row['id'] . "' style='color: white; text-decoration: none;'>Delete</a>
                                            </button>
                                        </button>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No data available.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                </div>

             <!-- Modal -->
             <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="../process/add_user.php" method="POST">
                                <input type="hidden" name="table" value="users">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name:</label>
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nik" class="form-label">NIK:</label>
                                    <input type="text" class="form-control" name="nik" id="nik" required>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role:</label>
                                    <select class="form-select" name="role" id="role" required>
                                        <option value="operator">Operator</option>
                                        <option value="teknisi">Teknisi</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Add User</button>
                            </form>
                        </div>
                    </div>
                </div>
             </div>


        </div>
    </div>

</body>

</html>
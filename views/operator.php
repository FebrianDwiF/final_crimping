<?php
include '../db/connection.php';
include '../process/auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Montserrat:wght@400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../public/css/operator.css">

</head>

<body>

    <div class="sidebar">
        <h4>Operator</h4>
        <hr>
        <br>
        <?php 
        date_default_timezone_set("Asia/Jakarta");

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

        <h4>Login Time: <?= htmlspecialchars($formattedDate); ?></h4>

        <form action="./system.php" method="POST">
            <button type="submit" class="search">📊 Start Production</button>
        </form>

        <br>

        <form action="../process/logout.php" method="POST">
            <input type="hidden" name="nik" value="<?php echo htmlspecialchars($user['nik']); ?>">
            <button type="submit" class="logout">🚪 Logout</button>
        </form>
    </div>



    <div class="main-content">
        <div class="card">
            <h1>Data LKO</h1>
            <div class="download-buttons">
                <a href="../process/export.php?dataType=lko&format=xlsx" class="btn btn-warning">Download (Excel)</a>
                <a href="../process/export.php?dataType=lko&format=csv" class="btn btn-outline-primary">Download
                    (CSV)</a>
            </div>

            <div class="table-container">
                <table class="table table-striped">
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
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) :
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
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
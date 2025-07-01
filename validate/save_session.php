<?php
session_start(); // Mulai sesi

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['shift'] = $_POST['shift'];
    $_SESSION['mesin'] = $_POST['mesin'];
    $_SESSION['carline'] = $_POST['carline'];
    echo "success"; // Kirim respons ke AJAX
}
?>
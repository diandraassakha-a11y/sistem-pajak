<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_sistem_pajak";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

require_once __DIR__ . '/demo_data.php';
?>
<?php
$servername = 'sql309.ezyro.com';
$username = 'ezyro_37516611';
$password = 'ppsm1919';
$dbname = 'ezyro_37516611_login';

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}
?>

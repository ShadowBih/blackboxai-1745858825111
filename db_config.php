<?php
// Konfigurasi koneksi database
$host = "localhost";
$user = "root";
$password = "";
$database = "ecommerce_aluminium";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>

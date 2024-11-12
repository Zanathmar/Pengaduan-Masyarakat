<?php
$host = "localhost:8889";  // or your specific host
$username = "root";        // or your database username
$password = "root";        // or your database password
$database = "pengaduan_warga";  // your database name

$koneksi = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$koneksi) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
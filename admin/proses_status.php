<?php
include '../config.php';
cek_admin();

if ($_SERVER['REQUEST_METHOD'] ==  'POST') {
    $pengaduan_id = $_POST['pengaduan_id'];
    $status = $_POST['status'];
    
    $query = "UPDATE pengaduan SET status = '$status' WHERE id = '$pengaduan_id'";
    mysqli_query($koneksi, $query);
    
    header("Location: index.php");
    exit();
}
?>
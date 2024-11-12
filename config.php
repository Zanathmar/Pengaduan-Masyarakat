<?php
session_start();
include 'koneksi.php';
include 'security.php';

// Cek session
function cek_session() {
    if (!isset($_SESSION['csrf_token'])) {
        header("Location: login.php");
        exit();
    } else {
        if (!validateCSRFToken($_SESSION['csrf_token'])) {
            header("Location: login.php");
            exit();
        }
    }
}

// Cek session admin
function cek_admin() {
    if ($_SESSION['role'] != 'admin') {
        header("Location: ../login.php");
        exit();
    }
}
?>
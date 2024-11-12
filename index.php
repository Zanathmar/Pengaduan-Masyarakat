<?php
include 'config.php';
// session_start();
// include 'security.php';
cek_session();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Form Pengaduan</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Form Pengaduan</h2>
            <a href="logout.php" class="logout">Logout</a>
        </div>

        <form action="proses_pengaduan.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Judul Pengaduan:</label>
                <input type="text" name="judul" required>
            </div>

            <div class="form-group">
                <label>Isi Pengaduan:</label>
                <textarea name="isi" required></textarea>
            </div>
            <div class="form-group">
                <label>Bukti Foto:</label>
                <input type="file" name="foto" accept="image/*" required>
            </div>
            <button type="submit" name="submit">Kirim Pengaduan</button>
        </form>
    </div>
</body>

</html>
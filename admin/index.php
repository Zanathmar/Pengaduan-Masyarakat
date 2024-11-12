<?php
require_once '../config.php';
require_once '../security.php';

// Cek session admin
cek_session();
cek_admin();

function xss_clean($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Prepared statement untuk mengambil data pengaduan
$query = "SELECT p.*, u.username 
          FROM pengaduan p 
          JOIN users u ON p.user_id = u.id 
          ORDER BY p.created_at DESC";
          
$stmt = $koneksi->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f0f0;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #F8F9FD;
            background: linear-gradient(0deg, rgb(255, 255, 255) 0%, rgb(244, 247, 251) 100%);
            border-radius: 20px;
            padding: 30px;
            border: 5px solid rgb(255, 255, 255);
            box-shadow: rgba(133, 189, 215, 0.8784313725) 0px 30px 30px -20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h2 {
            text-align: center;
            font-weight: 900;
            font-size: 24px;
            color: rgb(16, 137, 211);
            margin: 0;
        }

        .logout {
            color: #cb0d0d;
            text-decoration: none;
            padding: 12px 24px;
            background: white;
            border-radius: 15px;
            box-shadow: #cff0ff 0px 8px 8px -5px;
            font-weight: 500;
        }

        .success, .error {
            padding: 12px 15px;
            border-radius: 15px;
            margin-bottom: 20px;
            font-size: 14px;
            box-shadow: #cff0ff 0px 8px 8px -5px;
        }

        .success {
            background-color: #dcfce7;
            color: #166534;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .pengaduan-item {
            background: white;
            border-radius: 20px;
            margin-bottom: 20px;
            box-shadow: rgba(133, 189, 215, 0.8784313725) 0px 15px 15px -10px;
            overflow: hidden;
        }

        .pengaduan-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .pengaduan-header h3 {
            font-weight: 700;
            font-size: 18px;
            color: rgb(16, 137, 211);
            margin-bottom: 10px;
        }

        .pengaduan-meta {
            font-size: 14px;
            color: #555;
        }

        .pengaduan-content {
            padding: 20px;
        }

        .bukti-foto {
            width: 100%;
            border-radius: 15px;
            margin: 15px 0;
            cursor: zoom-in;
        }

        .filter-switch {
            padding-top: 2px;
            border: 2px solid #186dbc;
            border-radius: 30px;
            position: relative;
            display: flex;
            align-items: center;
            height: 50px;
            width: 100%;
            overflow: hidden;
            margin: 15px 0;
        }

        .filter-switch input {
            display: none;
        }

        .filter-switch label {
            flex: 1;
            text-align: center;
            cursor: pointer;
            border: none;
            border-radius: 30px;
            position: relative;
            overflow: hidden;
            z-index: 1;
            transition: all 0.5s;
            font-weight: 500;
            font-size: 18px;
            color: #8c8c8c;
            margin: 0;
        }

        .filter-switch .background {
            position: absolute;
            width: 32%;
            height: 38px;
            background: linear-gradient(45deg, rgb(16, 137, 211) 0%, rgb(18, 177, 209) 100%);
            top: 4px;
            left: 4px;
            border-radius: 30px;
            transition: left 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .filter-switch input[value="pending"]:checked ~ .background {
            left: 1%;
        }

        .filter-switch input[value="proses"]:checked ~ .background {
            left: 34%;
        }

        .filter-switch input[value="selesai"]:checked ~ .background {
            left: 67%;
        }

        .filter-switch input:checked + label {
            color: #ffffff;
            font-weight: bold;
        }

        button[type="submit"] {
            display: block;
            width: 100%;
            font-weight: bold;
            background: linear-gradient(45deg, rgb(16, 137, 211) 0%, rgb(18, 177, 209) 100%);
            color: white;
            padding: 12px;
            margin-top: 20px;
            border-radius: 15px;
            box-shadow: rgba(133, 189, 215, 0.8784313725) 0px 15px 10px -10px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        button[type="submit"]:hover {
            transform: scale(1.03);
            box-shadow: rgba(133, 189, 215, 0.8784313725) 0px 18px 10px -12px;
        }

        button[type="submit"]:active {
            transform: scale(0.95);
            box-shadow: rgba(133, 189, 215, 0.8784313725) 0px 12px 10px -8px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .filter-switch label {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Dashboard Admin</h2>
            <a href="../logout.php" class="logout">Logout</a>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success"><?= xss_clean($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?= xss_clean($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <div class="pengaduan-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="pengaduan-item">
                    <div class="pengaduan-header">
                        <h3><?= xss_clean($row['judul']) ?></h3>
                        <div class="pengaduan-meta">
                            <p><strong>Pelapor:</strong> <?= xss_clean($row['username']) ?></p>
                            <p><strong>Tanggal:</strong> <?= xss_clean($row['created_at']) ?></p>
                        </div>
                    </div>
                    
                    <div class="pengaduan-content">
                        <p><?= nl2br(xss_clean($row['isi'])) ?></p>
                        
                        <?php if ($row['foto']): ?>
                            <img src="../uploads/<?= xss_clean($row['foto']) ?>" 
                                 alt="Bukti" class="bukti-foto"
                                 loading="lazy">
                        <?php endif; ?>
                        
                        <form action="proses_status.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="pengaduan_id" value="<?= $row['id'] ?>">
                            
                            <div class="filter-switch">
                                <input type="radio" id="pending<?= $row['id'] ?>" 
                                       name="status" value="pending" 
                                       <?= $row['status'] == 'pending' ? 'checked' : '' ?>>
                                <label for="pending<?= $row['id'] ?>">Pending</label>

                                <input type="radio" id="proses<?= $row['id'] ?>" 
                                       name="status" value="proses" 
                                       <?= $row['status'] == 'proses' ? 'checked' : '' ?>>
                                <label for="proses<?= $row['id'] ?>">Proses</label>

                                <input type="radio" id="selesai<?= $row['id'] ?>" 
                                       name="status" value="selesai" 
                                       <?= $row['status'] == 'selesai' ? 'checked' : '' ?>>
                                <label for="selesai<?= $row['id'] ?>">Selesai</label>

                                <div class="background"></div>
                            </div>
                            
                            <button type="submit" name="update">Update Status</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
<?php
// info_board.php
require_once 'koneksi.php';  // Change this from config.php to koneksi.php since that's where your DB connection is

// Check connection
if (!$koneksi) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch pengaduan data
$query = "SELECT p.*, u.username 
          FROM pengaduan p 
          LEFT JOIN users u ON p.user_id = u.id 
          ORDER BY p.created_at DESC";

// Execute query
$result = mysqli_query($koneksi, $query);

// Check if query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Papan Informasi Pengaduan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #333;
        }

        .cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            width: 350px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-image {
            border-radius: 18px;
            padding: 7px;
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-content {
            padding: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .card-details {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #f8f9fa;
            border-top: 1px solid #eee;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #ffd700;
            color: #000;
        }

        .status-proses {
            background-color: #87ceeb;
            color: #fff;
        }

        .status-selesai {
            background-color: #90ee90;
            color: #fff;
        }

        .date {
            color: #999;
            font-size: 12px;
        }

        .nav-buttons {
            display: flex;
            gap: 10px;
        }

        .nav-btn {
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: opacity 0.3s ease;
        }

        .nav-btn:hover {
            opacity: 0.9;
        }

        .login-btn {
            background-color: #2196F3;
        }

        .register-btn {
            background-color: #4CAF50;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        @media (max-width: 768px) {
            .card {
                width: 100%;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Papan Informasi Pengaduan Masyarakat</h1>
            <div class="nav-buttons">
                <a href="login.php" class="nav-btn login-btn">Login</a>
                <a href="register.php" class="nav-btn register-btn">Register</a>
            </div>
        </div>

        <div class="cards-container">
            <?php 
            if (mysqli_num_rows($result) > 0):
                while($row = mysqli_fetch_assoc($result)): 
                    $status_class = 'status-' . strtolower($row['status']);
            ?>
                <div class="card">
                    <img src="uploads/<?php echo htmlspecialchars($row['foto']); ?>" alt="Bukti Pengaduan" class="card-image">
                    <div class="card-content">
                        <h2 class="card-title"><?php echo htmlspecialchars($row['judul']); ?></h2>
                        <p class="card-details"><?php echo nl2br(htmlspecialchars(substr($row['isi'], 0, 150)) . ''); ?></p>
                    </div>
                    <div class="card-footer">
                        <span class="status-badge <?php echo $status_class; ?>">
                            <?php echo htmlspecialchars($row['status']); ?>
                        </span>
                        <span class="date">
                            <?php echo date('d M Y', strtotime($row['created_at'])); ?> 
                        </span>
                    </div>
                </div>
            <?php 
                endwhile;
            else:
            ?>
                <div class="empty-state">
                    <h2>Belum ada pengaduan</h2>
                    <p>Silakan login untuk membuat pengaduan baru</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
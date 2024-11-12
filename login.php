<?php
require_once 'config.php';
// require_once 'security.php';


// Redirect if already logged in
if (isset($_SESSION['csrf_token'])) {
    header("Location: index.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate and sanitize input
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $errors[] = "Username dan password harus diisi!";
    } else {
        try {
            // Prepared statement untuk mencegah SQL injection
            $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
            if (!$stmt) {
                throw new Exception("Database error: " . $koneksi->error);
            }

            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // Generate new CSRF token
                    $token = generateCSRFToken($user['id']);
                    
                    if (!$token) {
                        echo "Error generating CSRF token.";
                        $errors[] = "Terjadi kesalahan sistem. Silakan coba lagi nanti. :)";
                        exit();
                    }
                    // Set session with minimal data
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['csrf_token'] = $token;
                    
                    // Regenerate session ID untuk mencegah session fixation
                    session_regenerate_id(true);
                    
                    // Log login berhasil
                    error_log("User {$user['username']} logged in successfully from IP: " . $_SERVER['REMOTE_ADDR']);
                    
                    // Redirect based on role
                    header("Location: " . ($user['role'] === 'admin' ? 'admin/index.php' : 'index.php'));
                    exit();
                }
            }
            
            // If we reach here, login failed
            $errors[] = "Username atau password salah!";
            error_log("Failed login attempt for username: $username from IP: " . $_SERVER['REMOTE_ADDR']);
            
            // Add delay to prevent brute force
            sleep(1);
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $errors[] = "Terjadi kesalahan sistem. Silakan coba lagi nanti.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex, nofollow">
    <style>
        .button-container {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .dashboard-btn {
            display: inline-block;
            padding: 8px 16px;
            background: linear-gradient(45deg, rgb(16, 137, 211) 0%, rgb(18, 177, 209) 100%);
            color: white;
            text-decoration: none;
            border-radius: 15px;
            transition: background-color 0.3s;
        }
        .dashboard-btn:hover {
            background-color: #45a049;
        }
        .register {
            color: #2196F3;
            text-decoration: none;
        }
        .register:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Sistem Pengaduan</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="error" role="alert">
                <?php foreach($errors as $error): ?>
                    <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" autocomplete="off">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" 
                       id="username"
                       name="username" 
                       required 
                       pattern="[a-zA-Z0-9_]{5,20}"
                       title="Username hanya boleh mengandung huruf, angka, dan underscore. Panjang 5-20 karakter."
                       maxlength="20"
                       value="<?= isset($username) ? htmlspecialchars($username, ENT_QUOTES, 'UTF-8') : '' ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" 
                       id="password"
                       name="password" 
                       required 
                       minlength="8"
                       autocomplete="current-password">
            </div>
            
            <button type="submit" name="login">Login</button>
        </form>
        
        <div class="button-container">
            <p>Belum punya akun? <a href="register.php" class="register">Daftar disini</a></p>
        </div>
        <div class="button-container">
        <button type="submit" name="login" class="dashboard-btn" onclick="window.location.href='info_board.php'">Lihat Info</button>
        </div>
    </div>
    
    <script>
    // Prevent form resubmission
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    
    // Disable form submission if already submitted
    document.querySelector('form').addEventListener('submit', function(e) {
        if (this.submitted) {
            e.preventDefault();
        } else {
            this.submitted = true;
            this.querySelector('button[type="submit"]').disabled = true;
        }
    });
    </script>
</body>
</html>
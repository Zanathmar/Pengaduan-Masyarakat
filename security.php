<?php
require_once 'config.php'; // Untuk mengakses $koneksi

/**
 * Sanitize input data untuk mencegah XSS dan injection
 * @param mixed $data Input yang akan dibersihkan
 * @return mixed Data yang sudah dibersihkan
 */
function sanitize($data)
{
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }

    // Remove whitespace
    $data = trim($data);

    // Remove backslashes
    $data = stripslashes($data);

    // Convert special characters to HTML entities
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

    return $data;
}

/**
 * Generate CSRF token
 * @param int|null $user_id ID user (optional)
 * @return string Generated token
 */
function generateCSRFToken($user_id)
{
    global $koneksi;

    try {
        $token = bin2hex(random_bytes(32));
        $expire = date('Y-m-d H:i:s', time() + (24 * 3600)); // 1 jam
        // Hapus token yang expired (lebih dari 1 jam)
        // $stmt = $koneksi->prepare("DELETE FROM csrf_tokens WHERE user_id = ? OR created_at < DATE_SUB(NOW(), INTERVAL 1 HOUR)");
        // $stmt->bind_param("i", $user_id);
        // $stmt->execute();

        // Simpan token baru
        $stmt = $koneksi->prepare("INSERT INTO csrf_tokens (token, user_id, expire) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $token, $user_id, $expire);
        $stmt->execute();

        return $token;
    } catch (Exception $e) {
        error_log("Error generating CSRF token: " . $e->getMessage());
        return null;
    }
}

/**
 * Validasi CSRF token
 * @param string $token Token yang akan divalidasi
 * @param int|null $user_id ID user (optional)
 * @return bool True jika valid, false jika tidak
 */
function validateCSRFToken($token)
{
    global $koneksi;

    if (empty($token)) {
        return false;
    } else {
        try {
            $stmt = $koneksi->prepare("SELECT * FROM csrf_tokens WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if (strtotime($row['expire']) < time()) {
                // Hapus token yang sudah divalidasi
                $stmt = $koneksi->prepare("DELETE FROM csrf_tokens WHERE token =?");
                $stmt->bind_param("s", $token);
                $stmt->execute();

                return false;
            } else {
                return true;
            }
        } catch (Exception $e) {
            error_log("Error validating CSRF token: ". $e->getMessage());
            return false;
        }
    }
}

/**
 * Escape string untuk keamanan SQL
 * @param string $str String yang akan di-escape
 * @return string String yang sudah di-escape
 */
function escape_sql($str)
{
    global $koneksi;
    if (!is_string($str)) return $str;
    return $koneksi->real_escape_string($str);
}

/**
 * Validasi password strength
 * @param string $password Password yang akan divalidasi
 * @return array Array berisi status valid dan pesan error
 */
function validatePassword($password)
{
    $errors = [];

    if (strlen($password) < 8) {
        $errors[] = "Password harus minimal 8 karakter";
    }

    if (!preg_match("/[A-Z]/", $password)) {
        $errors[] = "Password harus mengandung huruf kapital";
    }

    if (!preg_match("/[a-z]/", $password)) {
        $errors[] = "Password harus mengandung huruf kecil";
    }

    if (!preg_match("/[0-9]/", $password)) {
        $errors[] = "Password harus mengandung angka";
    }

    if (!preg_match("/[!@#$%^&*()\-_=+{};:,<.>]/", $password)) {
        $errors[] = "Password harus mengandung karakter special";
    }

    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

/**
 * Bersihkan filename untuk upload yang aman
 * @param string $filename Nama file yang akan dibersihkan
 * @return string Nama file yang aman
 */
function sanitizeFilename($filename)
{
    // Remove any path info
    $filename = basename($filename);

    // Remove special chars
    $filename = preg_replace("/[^a-zA-Z0-9.-]/", "_", $filename);

    // Remove multiple dots
    $filename = preg_replace("/\.+/", ".", $filename);

    // Ensure safe extension
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_extensions)) {
        $filename .= '.txt';
    }

    return $filename;
}

/**
 * Rate limiting untuk mencegah brute force
 * @param string $key Identifier untuk rate limiting (e.g., IP address atau user_id)
 * @param int $max_attempts Maksimum percobaan yang diizinkan
 * @param int $timeout Timeout dalam detik
 * @return bool True jika masih dalam batas, false jika sudah melebihi
 */
function checkRateLimit($key, $max_attempts = 5, $timeout = 300)
{
    $cache_file = sys_get_temp_dir() . '/rate_limit_' . md5($key);

    $attempts = [];
    if (file_exists($cache_file)) {
        $attempts = unserialize(file_get_contents($cache_file));
    }

    // Hapus percobaan yang sudah timeout
    $attempts = array_filter($attempts, function ($time) use ($timeout) {
        return $time > (time() - $timeout);
    });

    if (count($attempts) >= $max_attempts) {
        return false;
    }

    $attempts[] = time();
    file_put_contents($cache_file, serialize($attempts));

    return true;
}

// Fungsi untuk memastikan koneksi menggunakan HTTPS
function enforceHTTPS()
{
    if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
        $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $location);
        exit();
    }
}

// Set security headers
function setSecurityHeaders()
{
    header("X-Frame-Options: DENY");
    header("X-XSS-Protection: 1; mode=block");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: strict-origin-when-cross-origin");
    header("Content-Security-Policy: default-src 'self'");
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
}

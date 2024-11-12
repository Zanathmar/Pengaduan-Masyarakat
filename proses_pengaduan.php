<?php
include 'config.php';
// session_start();
// include 'security.php';
cek_session();

if(isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id']; // Ambil user_id dari session
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);
    
    // Handle file upload
    $foto = $_FILES['foto'];
    $foto_name = $foto['name'];
    $foto_tmp = $foto['tmp_name'];
    
    // Generate unique filename
    $foto_new_name = time() . '-' . $foto_name;
    $upload_path = 'uploads/' . $foto_new_name;
    
    // Check if uploads directory exists, if not create it
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }
    
    // Validate file type
    $allowed = array('jpg', 'jpeg', 'png', 'gif');
    $file_extension = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
    
    if(!in_array($file_extension, $allowed)) {
        echo "<script>
                alert('Format file tidak didukung! Gunakan format: jpg, jpeg, png, atau gif');
                window.location = 'index.php';
              </script>";
        exit();
    }
    
    // Move uploaded file
    if(move_uploaded_file($foto_tmp, $upload_path)) {
        // Insert into database with user_id
        $query = "INSERT INTO pengaduan (user_id, judul, isi, foto) VALUES ('$user_id', '$judul', '$isi', '$foto_new_name')";
        
        if(mysqli_query($koneksi, $query)) {
            echo "<script>
                    alert('Pengaduan berhasil dikirim!');
                    window.location = 'info_board.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error: " . mysqli_error($koneksi) . "');
                    window.location = 'index.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Gagal mengupload file!');
                window.location = 'index.php';
              </script>";
    }
}
?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_anggota = $_POST['kode_anggota'];
    $password = $_POST['password'];

    // Cek user di database
    $query = "SELECT * FROM anggota WHERE kode_anggota = '$kode_anggota'";
    $result = mysqli_query($koneksi, $query);

    // Jika username ditemukan
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // --- [BARU] CEK STATUS SUSPEND ---
        if ($user['status_akun'] == 'Dibekukan') {
            echo "<script>
                alert('AKUN DIBEKUKAN!\\n\\nAnda memiliki sanksi buku hilang yang belum diselesaikan.\\nSilakan hubungi pustakawan untuk membuka blokir.');
                window.location = 'login.php';
            </script>";
            exit(); // Stop agar tidak lanjut login
        }
        // ---------------------------------

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Jika password benar, buat session
            $_SESSION['id_anggota'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['kode_anggota'] = $user['kode_anggota'];
            
            // Redirect ke dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Password Salah
            header("Location: login.php?error=password");
            exit();
        }
    } else {
        // Username Tidak Ditemukan
        header("Location: login.php?error=username");
        exit();
    }
}
?>
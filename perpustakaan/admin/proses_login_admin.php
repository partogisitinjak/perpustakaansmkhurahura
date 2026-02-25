<?php
session_start();
include '../koneksi.php'; // Path ke koneksi disesuaikan

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_anggota = $_POST['kode_anggota'];
    $password = $_POST['password'];

    $query = "SELECT * FROM anggota WHERE kode_anggota = '$kode_anggota' AND role = 'admin'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        if (password_verify($password, $admin['password'])) {
            $_SESSION['id_admin'] = $admin['id'];
            $_SESSION['nama_admin'] = $admin['nama'];
            header("Location: dashboard.php");
            exit();
        }
    }
    header("Location: index.php?error=1");
    exit();
}
?>
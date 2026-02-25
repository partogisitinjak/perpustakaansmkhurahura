<?php
$host = "localhost";
$user = "user_perpus"; // Gunakan user baru
$pass = "password_kamu"; // Gunakan password yang kamu buat tadi
$db   = "db_perpustakaan"; // Pastikan nama database benar

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>
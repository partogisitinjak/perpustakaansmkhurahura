<?php
include '../koneksi.php';
$id = $_GET['id'];

// Kembalikan status akun jadi Aktif
mysqli_query($koneksi, "UPDATE anggota SET status_akun = 'Aktif' WHERE id = '$id'");

echo "<script>
        alert('Akun berhasil diaktifkan kembali!');
        window.location.href = 'kelola_gamifikasi.php';
      </script>";
?>
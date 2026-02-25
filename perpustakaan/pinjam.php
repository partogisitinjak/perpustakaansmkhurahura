<?php
session_start();
if (!isset($_SESSION['id_anggota'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

$id_buku = $_GET['id_buku'];
$id_anggota = $_SESSION['id_anggota'];
// UBAH VARIABEL DAN FORMAT WAKTU DI SINI
$waktu_pinjam = date('Y-m-d H:i:s'); // Format Y-m-d H:i:s untuk DATETIME

// 1. Tambah record ke tabel peminjaman
// UBAH NAMA KOLOM DI SINI
$query_pinjam = "INSERT INTO peminjaman (id_anggota, id_buku, waktu_pinjam) VALUES ('$id_anggota', '$id_buku', '$waktu_pinjam')";
mysqli_query($koneksi, $query_pinjam);

// 2. Update status buku menjadi 'Dipinjam'
$query_update_buku = "UPDATE buku SET status_ketersediaan = 'Dipinjam' WHERE id = $id_buku";
mysqli_query($koneksi, $query_update_buku);

header("Location: dashboard.php"); // Arahkan ke dashboard
exit();
?>
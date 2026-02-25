<?php
session_start();
if (!isset($_SESSION['id_anggota'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

$id_pinjam = $_GET['id_pinjam'];
// UBAH VARIABEL DAN FORMAT WAKTU DI SINI
$waktu_kembali = date('Y-m-d H:i:s'); // Format Y-m-d H:i:s untuk DATETIME

// Ambil id_buku dari data peminjaman
$result = mysqli_query($koneksi, "SELECT id_buku FROM peminjaman WHERE id = $id_pinjam");
$data_pinjam = mysqli_fetch_assoc($result);
$id_buku = $data_pinjam['id_buku'];

// 1. Update record di tabel peminjaman
// UBAH NAMA KOLOM DI SINI
$query_kembali = "UPDATE peminjaman SET status_peminjaman = 'Kembali', waktu_kembali = '$waktu_kembali' WHERE id = $id_pinjam";
mysqli_query($koneksi, $query_kembali);

// 2. Update status buku menjadi 'Tersedia'
$query_update_buku = "UPDATE buku SET status_ketersediaan = 'Tersedia' WHERE id = $id_buku";
mysqli_query($koneksi, $query_update_buku);

header("Location: dashboard.php");
exit();
?>
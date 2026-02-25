<?php
session_start();
include 'koneksi.php';

// Pastikan user login
if (!isset($_SESSION['id_anggota'])) {
    header("Location: login.php");
    exit();
}

$id_anggota = $_SESSION['id_anggota'];
$tanggal_hari_ini = date('Y-m-d');

// --- LANGSUNG PROSES (Tanpa Cek Apakah Sudah Absen) ---

// A. Catat riwayat kehadiran ke tabel 'presensi'
// Ini akan membuat baris baru setiap kali tombol ditekan
$query_absen = "INSERT INTO presensi (id_anggota, tanggal) VALUES ('$id_anggota', '$tanggal_hari_ini')";
mysqli_query($koneksi, $query_absen);

// B. Tambahkan Poin XP ke tabel 'anggota'
// Poin akan terus bertambah +10 setiap kali klik
$tambah_poin = "UPDATE anggota SET poin = poin + 10 WHERE id='$id_anggota'";
mysqli_query($koneksi, $tambah_poin);

// C. Beri notifikasi sukses
echo "<script>
        alert('✅ Absen Berhasil! Poin Anda bertambah +10 XP.'); 
        window.location='dashboard.php';
      </script>";
?>
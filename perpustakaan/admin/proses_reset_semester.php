<?php
include '../koneksi.php';

// FITUR RESET SEMESTER
// 1. Kosongkan riwayat absensi
mysqli_query($koneksi, "TRUNCATE TABLE presensi");

// 2. Nol-kan poin pelanggaran semua anggota
mysqli_query($koneksi, "UPDATE anggota SET poin_pelanggaran = 0");

// 3. (Opsional) Apakah riwayat peminjaman lama dihapus untuk mulai skor baru?
// Agar skor peminjaman (x / 30 buku) kembali jadi 0, kita harus kosongkan peminjaman juga.
// Jika tidak dihapus, user yang sudah pinjam 30 buku semester lalu akan langsung dapet nilai A di semester baru.
// Untuk studi kasus ini, kita RESET peminjaman juga.
mysqli_query($koneksi, "TRUNCATE TABLE peminjaman");
mysqli_query($koneksi, "UPDATE buku SET status_ketersediaan = 'Tersedia'"); // Reset status buku

echo "<script>
        alert('SEMESTER BARU DIMULAI! \\nSemua data poin, absensi, dan riwayat peminjaman telah direset ke 0.');
        window.location.href = 'kelola_gamifikasi.php';
      </script>";
?>
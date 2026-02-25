<?php
include '../koneksi.php';

// Pastikan ada ID yang dikirim
if (isset($_GET['id'])) {
    $id_buku = $_GET['id'];

    // 1. CEK KEAMANAN: Apakah buku sedang dipinjam?
    $cek_pinjam = mysqli_query($koneksi, "SELECT * FROM buku WHERE id = '$id_buku' AND status_ketersediaan = 'Dipinjam'");
    
    if (mysqli_num_rows($cek_pinjam) > 0) {
        // Jika sedang dipinjam, tolak penghapusan
        echo "<script>
                alert('GAGAL MENGHAPUS! Buku ini sedang dipinjam oleh anggota. Harap tunggu sampai buku dikembalikan.');
                window.location.href = 'kelola_buku.php';
              </script>";
    } else {
        // 2. Jika aman (Tersedia), Lakukan Penghapusan
        // Hapus dulu riwayat peminjaman terkait buku ini (agar tidak error database)
        // Opsional: Jika Anda ingin menyimpan riwayat meski buku dihapus, lewati baris DELETE peminjaman ini.
        // Tapi biasanya di database relasional, kita harus hapus anaknya dulu.
        mysqli_query($koneksi, "DELETE FROM peminjaman WHERE id_buku = '$id_buku'");

        // Hapus bukunya
        $query_hapus = "DELETE FROM buku WHERE id = '$id_buku'";
        
        if (mysqli_query($koneksi, $query_hapus)) {
            echo "<script>
                    alert('Buku berhasil dihapus dari database.');
                    window.location.href = 'kelola_buku.php';
                  </script>";
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    }
} else {
    // Jika tidak ada ID, kembalikan ke daftar
    header("Location: kelola_buku.php");
}
?>
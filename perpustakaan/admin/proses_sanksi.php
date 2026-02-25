<?php
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id_anggota'];
    $jenis = $_POST['jenis_sanksi'];
    $minus = $_POST['poin_minus'];
    
    // 1. Tambah Poin Pelanggaran di Database
    $query = "UPDATE anggota SET poin_pelanggaran = poin_pelanggaran + $minus WHERE id = '$id'";
    mysqli_query($koneksi, $query);

    // 2. Cek Jika Buku Hilang => SUSPEND AKUN
    if ($jenis == 'hilang') {
        mysqli_query($koneksi, "UPDATE anggota SET status_akun = 'Dibekukan' WHERE id = '$id'");
        $pesan = "Sanksi diberikan! Poin -$minus dan AKUN DIBEKUKAN sampai buku diganti.";
    } else {
        $pesan = "Sanksi berhasil dicatat. Poin pelanggaran bertambah -$minus.";
    }

    echo "<script>
            alert('$pesan');
            window.location.href = 'kelola_gamifikasi.php';
          </script>";
}
?>
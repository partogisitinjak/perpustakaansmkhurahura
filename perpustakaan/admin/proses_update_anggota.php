<?php
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $status = $_POST['status'];
    $status_akun = $_POST['status_akun'];

    // Query Update
    $query = "UPDATE anggota SET nama='$nama', status='$status', status_akun='$status_akun' WHERE id='$id'";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('✅ Data anggota berhasil diperbarui!');
                window.location.href = 'kelola_anggota.php';
              </script>";
    } else {
        echo "<script>
                alert('❌ Gagal update: " . mysqli_error($koneksi) . "');
                window.history.back();
              </script>";
    }
}
?>
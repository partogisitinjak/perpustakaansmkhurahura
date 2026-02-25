<?php
include 'header_admin.php';
include '../koneksi.php';

$message = '';
if (isset($_GET['reset_id'])) {
    $id_anggota = $_GET['reset_id'];
    $new_password_plain = 'perpus123'; // Password default baru
    $new_password_hashed = password_hash($new_password_plain, PASSWORD_DEFAULT);
    
    $query = "UPDATE anggota SET password = '$new_password_hashed' WHERE id = $id_anggota";
    if (mysqli_query($koneksi, $query)) {
        $message = "<div style='color:green;'>Password berhasil direset menjadi: <strong>$new_password_plain</strong></div>";
    }
}
?>
<main>
    <div class="container">
        <h2>Reset Password Anggota</h2>
        <?php echo $message; ?>
        <table border="1" width="100%">
            <thead><tr><th>Kode Anggota</th><th>Nama</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php
            $result = mysqli_query($koneksi, "SELECT id, kode_anggota, nama FROM anggota WHERE role = 'anggota'");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['kode_anggota'] . "</td>";
                echo "<td>" . $row['nama'] . "</td>";
                echo "<td><a href='?reset_id=" . $row['id'] . "' onclick='return confirm(\"Yakin reset password anggota ini?\")'>Reset Password</a></td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
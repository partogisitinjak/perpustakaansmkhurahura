<?php
include 'header_admin.php';
include '../koneksi.php';
?>
<main>
    <div class="container">
        <h2>Pantau Peminjaman Anggota</h2>
        <table border="1" width="100%">
            <thead>
                <tr>
                    <th>Kode Anggota</th>
                    <th>Nama Anggota</th>
                    <th>Buku yang Dipinjam</th>
                    <th>Waktu Pinjam</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT a.kode_anggota, a.nama, b.judul, p.waktu_pinjam
                      FROM anggota a
                      LEFT JOIN peminjaman p ON a.id = p.id_anggota AND p.status_peminjaman = 'Dipinjam'
                      LEFT JOIN buku b ON p.id_buku = b.id
                      WHERE a.role = 'anggota'
                      ORDER BY a.nama ASC";
            $result = mysqli_query($koneksi, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['kode_anggota'] . "</td>";
                echo "<td>" . $row['nama'] . "</td>";
                echo "<td>" . ($row['judul'] ? $row['judul'] : '<em>Tidak ada</em>') . "</td>";
                echo "<td>" . ($row['waktu_pinjam'] ? $row['waktu_pinjam'] : '<em>-</em>') . "</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
<?php
include 'header_admin.php';
include '../koneksi.php';

// PROSES TAMBAH BERITA
if (isset($_POST['simpan'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);
    $tanggal = date('Y-m-d');
    
    // Upload Gambar
    $gambar = null;
    if ($_FILES['gambar']['error'] == 0) {
        $target = "../uploads/" . time() . "_" . $_FILES['gambar']['name'];
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
            $gambar = time() . "_" . $_FILES['gambar']['name'];
        }
    }

    $q = "INSERT INTO berita (judul, isi, gambar, tanggal) VALUES ('$judul', '$isi', '$gambar', '$tanggal')";
    if (mysqli_query($koneksi, $q)) {
        echo "<script>alert('Berita berhasil diterbitkan!'); window.location='kelola_berita.php';</script>";
    }
}

// PROSES HAPUS BERITA
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM berita WHERE id='$id'");
    echo "<script>alert('Berita dihapus.'); window.location='kelola_berita.php';</script>";
}
?>

<main>
    <div class="container">
        <h2 class="mb-4">📢 Kelola Berita & Kegiatan</h2>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white fw-bold">Tulis Berita Baru</div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label>Judul Kegiatan</label>
                                <input type="text" name="judul" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Isi Berita</label>
                                <textarea name="isi" class="form-control" rows="5" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Foto Dokumentasi</label>
                                <input type="file" name="gambar" class="form-control" accept="image/*">
                            </div>
                            <button type="submit" name="simpan" class="btn btn-success w-100">🚀 Terbitkan</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white fw-bold">Arsip Berita</div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Judul</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $q = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY tanggal DESC");
                                while ($row = mysqli_fetch_assoc($q)) {
                                    echo "<tr>
                                        <td>" . date('d/m/Y', strtotime($row['tanggal'])) . "</td>
                                        <td>" . htmlspecialchars($row['judul']) . "</td>
                                        <td>
                                            <a href='kelola_berita.php?hapus=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Hapus berita ini?')\">Hapus</a>
                                        </td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>

<?php include 'footer_admin.php'; ?>
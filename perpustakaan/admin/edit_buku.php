<?php
include 'header_admin.php';
include '../koneksi.php';

$id = $_GET['id'];
$message = '';

// 1. AMBIL DATA LAMA
$query = mysqli_query($koneksi, "SELECT * FROM buku WHERE id = '$id'");
$data  = mysqli_fetch_assoc($query);

// 2. PROSES UPDATE DATA
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul    = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis  = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $stok     = (int) $_POST['stok'];
    $status   = $_POST['status_ketersediaan'];

    // LOGIKA GANTI GAMBAR
    $nama_gambar = $data['gambar']; // Default: Pakai gambar lama

    // Jika ada file baru yang diupload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../uploads/";
        $file_name  = time() . "_" . basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        $allowed = array("jpg", "jpeg", "png", "webp");
        if (in_array($imageFileType, $allowed)) {
            // Hapus gambar lama jika ada (biar server tidak penuh)
            if (!empty($data['gambar']) && file_exists("../uploads/" . $data['gambar'])) {
                unlink("../uploads/" . $data['gambar']);
            }

            // Upload gambar baru
            move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file);
            $nama_gambar = $file_name;
        }
    }

    // UPDATE DATABASE
    $query_update = "UPDATE buku SET 
                     judul='$judul', 
                     penulis='$penulis', 
                     penerbit='$penerbit', 
                     stok='$stok', 
                     status_ketersediaan='$status',
                     gambar='$nama_gambar' 
                     WHERE id='$id'";

    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>
                alert('Data buku berhasil diperbarui!');
                window.location.href = 'kelola_buku.php';
              </script>";
    } else {
        $message = "<div class='alert alert-danger'>Gagal update: " . mysqli_error($koneksi) . "</div>";
    }
}
?>

<main>
    <div class="container d-flex justify-content-center py-4">
        <div class="card border-0 shadow-lg rounded-4" style="width: 100%; max-width: 800px;">
            <div class="card-header bg-warning text-dark py-3 rounded-top-4">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square"></i> Edit Data Buku</h5>
            </div>

            <div class="card-body p-4 p-md-5">
                <?php echo $message; ?>

                <form method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                    <div class="row">
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Judul Buku</label>
                                <input type="text" name="judul" class="form-control" value="<?php echo htmlspecialchars($data['judul']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pengarang</label>
                                <input type="text" name="penulis" class="form-control" value="<?php echo htmlspecialchars($data['penulis']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Penerbit</label>
                                <input type="text" name="penerbit" class="form-control" value="<?php echo htmlspecialchars($data['penerbit']); ?>">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Stok</label>
                                    <input type="number" name="stok" class="form-control" min="0" value="<?php echo $data['stok']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <select name="status_ketersediaan" class="form-select">
                                        <option value="Tersedia" <?php if($data['status_ketersediaan']=='Tersedia') echo 'selected'; ?>>✅ Tersedia</option>
                                        <option value="Dipinjam" <?php if($data['status_ketersediaan']=='Dipinjam') echo 'selected'; ?>>⛔ Dipinjam</option>
                                        <option value="Hilang" <?php if($data['status_ketersediaan']=='Hilang') echo 'selected'; ?>>⚠️ Hilang/Rusak</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 text-center">
                            <label class="form-label fw-bold d-block">Cover Saat Ini</label>
                            
                            <?php
                            $img_src = "https://via.placeholder.com/200x300?text=No+Cover";
                            if (!empty($data['gambar']) && file_exists("../uploads/" . $data['gambar'])) {
                                $img_src = "../uploads/" . $data['gambar'];
                            }
                            ?>
                            <img src="<?php echo $img_src; ?>" class="img-thumbnail mb-3 shadow-sm" style="height: 250px; width:auto;">
                            
                            <div class="mb-3 text-start">
                                <label class="form-label small text-muted">Ganti Cover (Opsional)</label>
                                <input type="file" name="gambar" class="form-control form-control-sm" accept="image/*">
                                <div class="form-text x-small">Biarkan kosong jika tidak ingin mengganti gambar.</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                        <a href="kelola_buku.php" class="btn btn-outline-secondary px-4 fw-bold">Batal</a>
                        <button type="submit" class="btn btn-warning px-5 fw-bold shadow-sm">Simpan Perubahan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>

<?php include 'footer_admin.php'; ?>
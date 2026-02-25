<?php
include 'header_admin.php';
include '../koneksi.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul    = mysqli_real_escape_string($koneksi, trim($_POST['judul']));
    $penulis  = mysqli_real_escape_string($koneksi, trim($_POST['penulis']));
    $penerbit = mysqli_real_escape_string($koneksi, trim($_POST['penerbit']));
    $stok     = (int) $_POST['stok'];

    // --- LOGIKA UPLOAD GAMBAR (DIPERBAIKI) ---
    $nama_gambar = null;
    
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        // Folder tujuan (Relative Path dari folder admin)
        $target_dir = "../uploads/";
        
        // Nama file unik
        $file_name  = time() . "_" . basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        $allowed = array("jpg", "jpeg", "png");
        if (in_array($imageFileType, $allowed)) {
            // Pindahkan file ke folder uploads
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                $nama_gambar = $file_name; // HANYA SIMPAN NAMA FILE (misal: 17272_foto.jpg)
            } else {
                $message = "<div class='alert alert-danger'>Gagal upload gambar. Pastikan folder 'uploads' ada.</div>";
            }
        }
    }

    if (empty($message) && !empty($judul) && !empty($stok)) {
        // Cek Duplikat
        $cek = mysqli_query($koneksi, "SELECT id, stok, judul FROM buku WHERE LOWER(judul) = LOWER('$judul') AND LOWER(penulis) = LOWER('$penulis')");
        
        if (mysqli_num_rows($cek) > 0) {
            $data = mysqli_fetch_assoc($cek);
            $new_stok = $data['stok'] + $stok;
            $id_lama = $data['id'];
            
            // Jika ada gambar baru, update gambarnya juga
            $update_img = "";
            if ($nama_gambar) {
                $update_img = ", gambar='$nama_gambar'";
            }

            mysqli_query($koneksi, "UPDATE buku SET stok='$new_stok' $update_img WHERE id='$id_lama'");
            
            $message = "<div class='alert alert-info alert-dismissible fade show'>
                            <i class='bi bi-info-circle-fill'></i> Stok buku <strong>'{$data['judul']}'</strong> bertambah.
                            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                        </div>";
        } else {
            // Insert Baru
            $query = "INSERT INTO buku (judul, penulis, penerbit, stok, gambar, status_ketersediaan) 
                      VALUES ('$judul', '$penulis', '$penerbit', '$stok', '$nama_gambar', 'Tersedia')";
            
            if (mysqli_query($koneksi, $query)) {
                $message = "<div class='alert alert-success alert-dismissible fade show'>
                                <i class='bi bi-check-circle-fill'></i> Buku berhasil ditambahkan!
                                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                            </div>";
            } else {
                $message = "<div class='alert alert-danger'>Error: " . mysqli_error($koneksi) . "</div>";
            }
        }
    }
}
?>

<main>
    <div class="container d-flex justify-content-center py-4">
        <div class="card border-0 shadow-lg rounded-4" style="width: 100%; max-width: 700px;">
            <div class="card-header bg-primary text-white py-3 rounded-top-4">
                <h5 class="mb-0 fw-bold"><i class="bi bi-journal-plus"></i> Input Data Buku</h5>
            </div>
            <div class="card-body p-4 p-md-5">
                <?php echo $message; ?>
                <form method="POST" action="" enctype="multipart/form-data" autocomplete="off">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Judul Buku <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Pengarang</label>
                            <input type="text" name="penulis" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control">
                        </div>
                    </div>
                    <div class="row align-items-center mb-4">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label fw-bold">Stok</label>
                            <input type="number" name="stok" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Upload Cover</label>
                            <input type="file" name="gambar" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="kelola_buku.php" class="btn btn-outline-secondary px-4 fw-bold">Kembali</a>
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?php include 'footer_admin.php'; ?>
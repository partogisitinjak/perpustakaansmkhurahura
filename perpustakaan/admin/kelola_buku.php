<?php
include 'header_admin.php';
include '../koneksi.php';
?>

<main>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary"><i class="bi bi-journal-album"></i> Kelola Koleksi Buku</h2>
            <div>
                <button type="button" class="btn btn-success text-white shadow-sm me-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="bi bi-file-earmark-spreadsheet-fill"></i> Import Excel
                </button>
                
                <a href="tambah_buku.php" class="btn btn-primary shadow-sm fw-bold">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Buku
                </a>
            </div>
        </div>

        <div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-spreadsheet-fill"></i> Import Data Buku</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="proses_import_buku.php" method="POST" enctype="multipart/form-data">
                        <div class="modal-body p-4">
                            <div class="alert alert-info border-0 shadow-sm mb-4">
                                <h6 class="fw-bold text-info"><i class="bi bi-info-circle-fill"></i> Panduan Import:</h6>
                                <ul class="small mb-3 ps-3 text-secondary">
                                    <li>File wajib berformat <strong>.CSV (Comma delimited)</strong>.</li>
                                    <li>Gunakan template di bawah agar urutan kolom sesuai.</li>
                                </ul>
                                <div class="d-grid">
                                    <a href="download_template.php" class="btn btn-light text-primary fw-bold border">
                                        <i class="bi bi-download"></i> Download Format Template .CSV
                                    </a>
                                </div>
                            </div>
                            <div class="mb-1">
                                <label for="file_csv" class="form-label fw-bold text-dark">Upload File CSV</label>
                                <input type="file" name="file_csv" id="file_csv" class="form-control" accept=".csv" required>
                            </div>
                        </div>
                        <div class="modal-footer bg-light border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" name="import" class="btn btn-success fw-bold shadow-sm">
                                <i class="bi bi-cloud-upload-fill"></i> Upload & Proses
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center py-3">No</th>
                                <th class="py-3">Judul Buku</th>
                                <th class="py-3">Pengarang</th>
                                <th class="py-3">Penerbit</th>
                                <th class="text-center py-3">Stok</th>
                                <th class="text-center py-3">Status</th>
                                <th class="text-center py-3" style="width: 180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $query = "SELECT * FROM buku ORDER BY id DESC";
                        $result = mysqli_query($koneksi, $query);
                        $no = 1;

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Badge Status (Hijau jika Tersedia, Merah jika tidak)
                                $badge_class = ($row['status_ketersediaan'] == 'Tersedia') ? 'bg-success' : 'bg-danger';
                                
                                echo "<tr>";
                                echo "<td class='text-center'>" . $no++ . "</td>";
                                echo "<td class='fw-bold'>" . htmlspecialchars($row['judul']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['penulis']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['penerbit']) . "</td>";
                                echo "<td class='text-center fw-bold'>" . $row['stok'] . "</td>";
                                echo "<td class='text-center'><span class='badge $badge_class'>" . $row['status_ketersediaan'] . "</span></td>";
                                
                                echo "<td class='text-center'>";
                                // TOMBOL EDIT (KUNING)
                                echo "<a href='edit_buku.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning text-dark fw-bold me-1 shadow-sm'>
                                        <i class='bi bi-pencil-square'></i> Edit
                                      </a>";
                                
                                // TOMBOL HAPUS (MERAH)
                                echo "<a href='hapus_buku.php?id=" . $row['id'] . "' 
                                         onclick=\"return confirm('Yakin ingin menghapus buku ini?');\" 
                                         class='btn btn-sm btn-danger fw-bold shadow-sm'>
                                         <i class='bi bi-trash'></i>
                                      </a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center py-5 text-muted'>Belum ada data buku.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer_admin.php'; ?>
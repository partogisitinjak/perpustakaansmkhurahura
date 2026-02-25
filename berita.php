<?php 
include 'header.php'; 
// Cek koneksi database (jika belum ada di header)
if (!isset($koneksi)) {
    if (file_exists('koneksi.php')) include 'koneksi.php';
    elseif (file_exists('../koneksi.php')) include '../koneksi.php';
}
?>

<div class="bg-dark text-white py-5 mb-4 text-center" 
     style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
    <h1 class="fw-bold display-5">Kabar Perpustakaan</h1>
    <p class="lead">Update terbaru seputar kegiatan, acara, dan informasi literasi.</p>
</div>

<div class="container mb-5">
    <div class="row g-4">
        <?php
        $query = "SELECT * FROM berita ORDER BY tanggal DESC";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                
                // --- LOGIKA GAMBAR PINTAR ---
                // 1. Default: Pakai gambar buku dari internet (jika tidak ada upload)
                $img_src = "https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&w=600&q=80";
                
                // 2. Cek jika ada file upload di database DAN filenya benar-benar ada di folder
                $file_upload = "uploads/" . $row['gambar'];
                if (!empty($row['gambar']) && file_exists($file_upload)) {
                    $img_src = $file_upload;
                }

                // Potong isi berita biar tidak kepanjangan di kartu depan
                $isi_singkat = substr(strip_tags($row['isi']), 0, 100) . "...";
                $tanggal_indo = date('d F Y', strtotime($row['tanggal']));
        ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 hover-zoom">
                    
                    <div style="height: 200px; overflow: hidden;">
                        <img src="<?php echo $img_src; ?>" 
                             class="card-img-top" 
                             style="height: 100%; width: 100%; object-fit: cover;"
                             alt="Gambar Berita"
                             onerror="this.src='https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&w=600&q=80'">
                    </div>

                    <div class="card-body d-flex flex-column">
                        <small class="text-muted mb-2">
                            <i class="bi bi-calendar3"></i> <?php echo $tanggal_indo; ?>
                        </small>
                        
                        <h5 class="card-title fw-bold text-primary mb-3">
                            <?php echo htmlspecialchars($row['judul']); ?>
                        </h5>
                        
                        <p class="card-text text-secondary small flex-grow-1">
                            <?php echo $isi_singkat; ?>
                        </p>
                        
                        <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-3" data-bs-toggle="modal" data-bs-target="#newsModal<?php echo $row['id']; ?>">
                            Baca Selengkapnya
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="newsModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title fw-bold text-dark"><?php echo htmlspecialchars($row['judul']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <img src="<?php echo $img_src; ?>" 
                                 class="img-fluid rounded mb-4 w-100 shadow-sm" 
                                 alt="Detail Gambar"
                                 onerror="this.src='https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&w=800&q=80'">
                            
                            <div style="white-space: pre-line; line-height: 1.8; color: #333;">
                                <?php echo htmlspecialchars($row['isi']); ?>
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light">
                            <small class="text-muted me-auto">Diposting pada: <?php echo $tanggal_indo; ?></small>
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

        <?php 
            }
        } else {
            // JIKA TIDAK ADA BERITA
            echo "
            <div class='col-12 text-center py-5'>
                <div class='text-muted opacity-50 display-1 mb-3'><i class='bi bi-newspaper'></i></div>
                <h4 class='text-muted'>Belum ada berita terbaru.</h4>
                <p class='text-secondary'>Cek kembali nanti untuk update kegiatan perpustakaan.</p>
            </div>";
        }
        ?>
    </div>
</div>

<style>
    .hover-zoom { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-zoom:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>

<?php include 'footer.php'; ?>
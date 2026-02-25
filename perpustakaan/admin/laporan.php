<?php
include 'header_admin.php';
include '../koneksi.php';

// Hitung data ringkas
$jml_pinjam = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM peminjaman"));
$jml_sanksi = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM anggota WHERE poin_pelanggaran > 0"));
?>

<main>
    <div class="container py-4">
        
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary"><i class="bi bi-file-earmark-text-fill"></i> Pusat Laporan</h2>
            <p class="text-muted">Pilih jenis laporan yang ingin Anda lihat atau cetak.</p>
        </div>

        <div class="row g-4 justify-content-center">
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4 text-center hover-up">
                    <div class="card-body p-5">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="bi bi-journal-text fs-1"></i>
                        </div>
                        <h4 class="card-title fw-bold">Laporan Peminjaman</h4>
                        <p class="card-text text-muted mb-4">
                            Data riwayat peminjaman buku.
                            <br><span class="badge bg-primary mt-2"><?php echo $jml_pinjam; ?> Transaksi</span>
                        </p>
                        <a href="laporan_detail.php?tab=peminjaman" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                            <i class="bi bi-eye-fill me-2"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4 text-center hover-up">
                    <div class="card-body p-5">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                        </div>
                        <h4 class="card-title fw-bold">Laporan Sanksi</h4>
                        <p class="card-text text-muted mb-4">
                            Daftar pelanggaran anggota.
                            <br><span class="badge bg-danger mt-2"><?php echo $jml_sanksi; ?> Pelanggaran</span>
                        </p>
                        <a href="laporan_detail.php?tab=sanksi" class="btn btn-outline-danger w-100 py-2 fw-bold">
                            <i class="bi bi-eye-fill me-2"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4 text-center hover-up">
                    <div class="card-body p-5">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="bi bi-trophy-fill fs-1"></i>
                        </div>
                        <h4 class="card-title fw-bold">Rapor Keaktifan</h4>
                        <p class="card-text text-muted mb-4">
                            Nilai akhir & cetak rapor anggota.
                        </p>
                        <a href="laporan_detail.php?tab=kinerja" class="btn btn-outline-success w-100 py-2 fw-bold">
                            <i class="bi bi-printer-fill me-2"></i> Cetak Rapor
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<style>
    .hover-up { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-up:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
</style>

<?php include 'footer_admin.php'; ?>
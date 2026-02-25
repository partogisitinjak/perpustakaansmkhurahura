<?php
include 'header_admin.php';
include '../koneksi.php'; 

// Hitung Statistik
$q_anggota = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM anggota WHERE role='anggota'");
$d_anggota = mysqli_fetch_assoc($q_anggota);

$q_buku = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku");
$d_buku = mysqli_fetch_assoc($q_buku);

$q_pinjam = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE status_peminjaman='Dipinjam'");
$d_pinjam = mysqli_fetch_assoc($q_pinjam);
?>

<div class="p-4 mb-4 bg-white rounded-3 shadow-sm">
    <div class="container-fluid py-2">
        <h1 class="display-6 fw-bold text-primary">Dashboard Administrator</h1>
        <p class="col-md-8 fs-5 text-muted">Selamat datang, Admin! Berikut adalah ringkasan data perpustakaan Anda hari ini.</p>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card h-100 border-start border-4 border-primary">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-uppercase text-muted fw-bold mb-1">Total Anggota</h6>
                    <h2 class="fw-bold text-dark mb-0"><?php echo $d_anggota['total']; ?></h2>
                </div>
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                    <i class="bi bi-people-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-start border-4 border-success">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-uppercase text-muted fw-bold mb-1">Koleksi Buku</h6>
                    <h2 class="fw-bold text-dark mb-0"><?php echo $d_buku['total']; ?></h2>
                </div>
                <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                    <i class="bi bi-book-half fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-start border-4 border-warning">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-uppercase text-muted fw-bold mb-1">Sedang Dipinjam</h6>
                    <h2 class="fw-bold text-dark mb-0"><?php echo $d_pinjam['total']; ?></h2>
                </div>
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                    <i class="bi bi-journal-bookmark-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<h5 class="fw-bold text-secondary mb-3"><i class="bi bi-lightning-charge-fill text-warning"></i> Akses Cepat</h5>
<div class="row g-3">
    <div class="col-6 col-md-3">
        <a href="kelola_buku.php" class="btn btn-primary w-100 py-3 shadow-sm fw-bold">
            <i class="bi bi-journal-plus d-block fs-2 mb-2"></i> Kelola Buku
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="kelola_anggota.php" class="btn btn-info text-white w-100 py-3 shadow-sm fw-bold">
            <i class="bi bi-person-gear d-block fs-2 mb-2"></i> Kelola Anggota
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="kelola_gamifikasi.php" class="btn btn-purple w-100 py-3 shadow-sm fw-bold text-white" style="background-color: #6f42c1;">
            <i class="bi bi-controller d-block fs-2 mb-2"></i> Sanksi & Poin
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="laporan.php" class="btn btn-warning text-dark w-100 py-3 shadow-sm fw-bold">
            <i class="bi bi-file-earmark-bar-graph d-block fs-2 mb-2"></i> Laporan
        </a>
    </div>
</div>

</div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


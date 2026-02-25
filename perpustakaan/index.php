<?php include 'header.php'; ?>

<div class="p-5 mb-4 bg-dark text-white rounded-3 shadow-sm text-center" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://source.unsplash.com/1200x400/?library,books'); background-size: cover; background-position: center;">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Perpustakaan SMK Hurahura</h1>
        <p class="col-md-8 fs-5 mx-auto">Pusat literasi modern berbasis gamifikasi. Baca buku, kumpulkan poin, dan raih prestasi!</p>
        <a href="#rules" class="btn btn-outline-light btn-lg px-4">Pelajari Aturan</a>
        <a href="katalog.php" class="btn btn-primary btn-lg px-4">Lihat Katalog</a>
    </div>
</div>

<?php
if (isset($_SESSION['id_anggota'])) {
    if (!isset($koneksi)) { include 'koneksi.php'; }
    $id_anggota = $_SESSION['id_anggota'];
    
    // --- LOGIKA GAMIFIKASI (Sama seperti sebelumnya) ---
    $nama_sapaan = explode(' ', $_SESSION['nama'])[0];
    
    // Ambil Data
    $q_saya = mysqli_query($koneksi, "SELECT poin FROM anggota WHERE id='$id_anggota'");
    $d_saya = mysqli_fetch_assoc($q_saya); $poin_saya = $d_saya['poin'];
    
    // Level
    $level_saat_ini = "Pemula"; 
    if ($poin_saya >= 100 && $poin_saya < 300) $level_saat_ini = "Pecinta Buku";
    elseif ($poin_saya >= 300) $level_saat_ini = "Pustakawan Ahli";

    // Ranking
    $q_rank = mysqli_query($koneksi, "SELECT COUNT(*) as rank FROM anggota WHERE role != 'admin' AND poin > $poin_saya");
    $ranking_saya = mysqli_fetch_assoc($q_rank)['rank'] + 1;
    
    $q_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM anggota WHERE role != 'admin'");
    $total_anggota = mysqli_fetch_assoc($q_total)['total'];
?>
    <div class="container mb-5">
        <div class="alert alert-info shadow-sm border-0 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="alert-heading mb-1"><i class="bi bi-person-circle"></i> Halo, <?php echo htmlspecialchars($nama_sapaan); ?>!</h4>
                <p class="mb-0">Jangan lupa catat kehadiranmu hari ini.</p>
            </div>
            <form method="POST" action="proses_absen.php" class="d-flex">
                <button type="submit" class="btn btn-success fw-bold shadow-sm" onclick="return confirm('Absen sekarang?')">
                    <i class="bi bi-geo-alt-fill"></i> Check-In Harian
                </button>
            </form>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted text-uppercase fw-bold" style="font-size:12px;">Ranking Saya</h6>
                        <h2 class="text-warning fw-bold">#<?php echo $ranking_saya; ?></h2>
                        <small class="text-muted">dari <?php echo $total_anggota; ?> Anggota</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted text-uppercase fw-bold" style="font-size:12px;">Total XP</h6>
                        <h2 class="text-primary fw-bold"><?php echo $poin_saya; ?></h2>
                        <span class="badge bg-primary bg-opacity-10 text-primary"><?php echo $level_saat_ini; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted text-uppercase fw-bold" style="font-size:12px;">Leaderboard</h6>
                        <a href="gamifikasi.php" class="btn btn-outline-primary btn-sm mt-2">Lihat Top 5 Global <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="my-5">
<?php } ?>

<section class="container mb-5">
    <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
            <img src="image/home.jpg" 
                 class="img-fluid rounded-4 shadow" 
                 alt="Tentang Kami">
        </div>
        <div class="col-md-6">
            <h6 class="text-primary fw-bold text-uppercase ls-2">Tentang Kami</h6>
            <h2 class="fw-bold mb-3">Transformasi Digital Perpustakaan</h2>
            <p class="text-muted lead">
                Perpustakaan SMK Hurahura hadir dengan wajah baru. Kami tidak hanya menyediakan buku, tetapi juga pengalaman belajar yang menyenangkan.
            </p>
            <p class="text-secondary">
                Mengusung konsep <strong>Keaktifan Anggota</strong>, kami mengajak seluruh siswa berpartisipasi aktif. Setiap aktivitas literasi Anda dihargai dengan poin, dan poin akan digunakan untuk tambahan nilai mata pembelajaran terkait. Kami percaya bahwa membaca seharusnya tidak membosankan, tetapi menantang dan bermanfaat.
            </p>
            <ul class="list-unstyled mt-3">
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Dapat tracking koleksi buku dimana saja</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Sistem Poin untuk menunjang penilaian mata pembelajaran</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Sistem Peminjaman yang menyenangkan</li>
            </ul>
        </div>
    </div>
</section>

<section id="rules" class="py-5 bg-light rounded-4">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase">Tata Tertib</h6>
            <h2 class="fw-bold">Aturan Main & Gamifikasi</h2>
            <p class="text-muted">Pahami cara mendapatkan poin dan hindari sanksi.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="display-5 text-primary mb-3"><i class="bi bi-calculator"></i></div>
                        <h4 class="card-title fw-bold">Sistem Penilaian</h4>
                        <p class="card-text text-muted small">Nilai keaktifan semester dihitung otomatis oleh sistem:</p>
                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item px-0 border-0"><i class="bi bi-dot"></i> <strong>70%</strong> dari Peminjaman Buku (Target 30 Buku)</li>
                            <li class="list-group-item px-0 border-0"><i class="bi bi-dot"></i> <strong>30%</strong> dari Kehadiran (Target 24x Pertemuan)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="display-5 text-warning mb-3"><i class="bi bi-trophy"></i></div>
                        <h4 class="card-title fw-bold">Level & Poin</h4>
                        <p class="card-text text-muted small">Kumpulkan XP sebanyak-banyaknya untuk naik level!</p>
                        <div class="d-flex justify-content-between mt-3 border-bottom pb-2">
                            <span>Pemula</span>
                            <span class="fw-bold">0 - 99 XP</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2 border-bottom pb-2">
                            <span>Pecinta Buku</span>
                            <span class="fw-bold">100 - 299 XP</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>Ahli Pustaka</span>
                            <span class="fw-bold">300+ XP</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="display-5 text-danger mb-3"><i class="bi bi-exclamation-octagon"></i></div>
                        <h4 class="card-title fw-bold">Sanksi & Denda</h4>
                        <p class="card-text text-muted small">Hindari pelanggaran agar akun tidak bermasalah.</p>
                        <ul class="list-unstyled small mt-3">
                            <li class="mb-2 text-danger"><i class="bi bi-x-circle me-2"></i> Buku Rusak: <strong>-10 XP</strong> + Denda</li>
                            <li class="mb-2 text-danger"><i class="bi bi-x-circle me-2"></i> Buku Hilang: <strong>-20 XP</strong> + Akun Dibekukan (Suspend)</li>
                            <li class="mb-2 text-secondary"><i class="bi bi-info-circle me-2"></i> Akun suspend tidak bisa meminjam sampai buku diganti.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div style="margin-bottom: 100px;"></div> <?php include 'footer.php'; ?>
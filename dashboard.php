<?php
session_start();
// Cek jika pengguna belum login, tendang ke halaman login
if (!isset($_SESSION['id_anggota'])) {
    header("Location: login.php");
    exit();
}

// Pastikan koneksi database tersedia
if (!isset($koneksi)) {
    if (file_exists('koneksi.php')) { include 'koneksi.php'; } 
    elseif (file_exists('../koneksi.php')) { include '../koneksi.php'; }
}

$id_anggota = $_SESSION['id_anggota'];
$nama_anggota = $_SESSION['nama'];
$nama_sapaan = explode(' ', $nama_anggota)[0];

// --- LOGIKA HITUNG STATISTIK PRIBADI ---
$q_saya = mysqli_query($koneksi, "SELECT poin FROM anggota WHERE id='$id_anggota'");
$d_saya = mysqli_fetch_assoc($q_saya);
$poin_saya = $d_saya['poin'];

// Hitung Ranking
$q_rank = mysqli_query($koneksi, "SELECT COUNT(*) as rank FROM anggota WHERE role != 'admin' AND poin > $poin_saya");
$d_rank = mysqli_fetch_assoc($q_rank);
$ranking_saya = $d_rank['rank'] + 1;

// Hitung Total Anggota
$q_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM anggota WHERE role != 'admin'");
$d_total = mysqli_fetch_assoc($q_total);
$total_anggota = $d_total['total'];
?>

<?php include 'header.php'; ?>

<main>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-0">Selamat Datang, <?php echo htmlspecialchars($nama_sapaan); ?>!</h2>
                <p class="text-muted">Pantau progres poinmu dan buku yang sedang kamu pinjam di sini.</p>
            </div>
        </div>

        <div class="alert alert-light border shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-3 p-4 rounded-3 mb-4" style="border-left: 5px solid #28a745 !important;">
            <div>
                <h4 class="text-success fw-bold mb-1"><i class="bi bi-geo-alt-fill"></i> Absensi Harian</h4>
                <p class="mb-0 text-muted">Klik tombol di samping untuk mencatat kehadiranmu hari ini dan dapatkan poin.</p>
            </div>
            <form method="POST" action="proses_absen.php">
                <button type="submit" class="btn btn-success btn-lg shadow-sm fw-bold" onclick="return confirm('Catat kehadiran hari ini?')">
                    <i class="bi bi-check-circle"></i> Check-In Kehadiran
                </button>
            </form>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 py-2">
                    <div class="card-body text-center">
                        <h6 class="text-uppercase text-muted fw-bold small ls-1">🏆 Peringkat Saya</h6>
                        <h1 class="text-warning fw-bold display-5 mb-0">#<?php echo $ranking_saya; ?></h1>
                        <small class="text-muted">dari <?php echo $total_anggota; ?> Anggota</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 py-2">
                    <div class="card-body text-center">
                        <h6 class="text-uppercase text-muted fw-bold small ls-1">⭐ Total XP</h6>
                        <h1 class="text-primary fw-bold display-5 mb-0"><?php echo $poin_saya; ?></h1>
                        <small class="text-muted">Poin Terkumpul</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 py-2">
                    <div class="card-body text-center">
                        <h6 class="text-uppercase text-muted fw-bold small ls-1">🚀 Target Level</h6>
                        <?php 
                            $target = ($poin_saya < 100) ? 100 : (($poin_saya < 300) ? 300 : 1000);
                            $sisa = max(0, $target - $poin_saya);
                        ?>
                        <h1 class="text-success fw-bold display-5 mb-0"><?php echo $sisa; ?> XP</h1>
                        <small class="text-muted">lagi naik level</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-5 overflow-hidden">
            <div class="card-header bg-dark text-white py-3 d-flex align-items-center">
                <i class="bi bi-trophy-fill text-warning me-2 fs-5"></i> 
                <h5 class="mb-0 fw-bold">Papan Peringkat Tertinggi</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center ps-4">#</th>
                                <th>Nama Anggota</th>
                                <th class="text-end pe-4">Total Poin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_rank = "SELECT nama, poin FROM anggota WHERE role != 'admin' ORDER BY poin DESC LIMIT 5";
                            $result_rank = mysqli_query($koneksi, $query_rank);
                            $nomor = 1;
                            if (mysqli_num_rows($result_rank) > 0) {
                                while ($row = mysqli_fetch_assoc($result_rank)) {
                                    $trophy = ($nomor == 1) ? "🥇" : (($nomor == 2) ? "🥈" : (($nomor == 3) ? "🥉" : $nomor));
                                    $bg_row = ($nomor == 1) ? "bg-warning bg-opacity-10" : "";
                                    $nama_depan = explode(' ', $row['nama'])[0];
                                    echo "<tr class='$bg_row'>
                                            <td class='text-center fw-bold fs-5 ps-4'>$trophy</td>
                                            <td class='fw-bold'>".htmlspecialchars($nama_depan)."</td>
                                            <td class='text-end pe-4'><span class='badge bg-primary rounded-pill px-3'>".$row['poin']." XP</span></td>
                                          </tr>";
                                    $nomor++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mb-5">
            <h4 class="fw-bold text-dark mb-3">
                <i class="bi bi-journal-bookmark-fill text-primary"></i> Buku yang Sedang Dipinjam
            </h4>

            <div class="row g-3">
                <?php
                // Query Updated: Ambil juga kolom 'gambar' dari tabel buku
                $query_pinjam = "SELECT peminjaman.id, buku.judul, buku.gambar, buku.penulis, peminjaman.waktu_pinjam 
                                 FROM peminjaman 
                                 JOIN buku ON peminjaman.id_buku = buku.id 
                                 WHERE peminjaman.id_anggota = $id_anggota AND peminjaman.status_peminjaman = 'Dipinjam'";
                
                $result_pinjam = mysqli_query($koneksi, $query_pinjam);

                if (mysqli_num_rows($result_pinjam) > 0) {
                    while ($row = mysqli_fetch_assoc($result_pinjam)) {
                        // Cek Gambar
                        $img_src = "https://via.placeholder.com/100x150?text=No+Cover";
                        if (!empty($row['gambar']) && file_exists("uploads/" . $row['gambar'])) {
                            $img_src = "uploads/" . $row['gambar'];
                        }
                        // Format Tanggal
                        $tgl_pinjam = date('d M Y, H:i', strtotime($row['waktu_pinjam']));
                ?>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100 overflow-hidden">
                            <div class="row g-0 align-items-center">
                                <div class="col-4 col-sm-3">
                                    <img src="<?php echo $img_src; ?>" class="img-fluid" alt="Cover" style="width:100%; height:140px; object-fit:cover;">
                                </div>
                                <div class="col-8 col-sm-9">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold mb-1 text-truncate"><?php echo htmlspecialchars($row['judul']); ?></h5>
                                        <p class="card-text text-muted small mb-2"><i class="bi bi-person"></i> <?php echo htmlspecialchars($row['penulis']); ?></p>
                                        <p class="card-text mb-2"><small class="text-success fw-bold"><i class="bi bi-calendar-check"></i> Dipinjam: <?php echo $tgl_pinjam; ?></small></p>
                                        
                                        <a href="kembalikan.php?id_pinjam=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Kembalikan buku ini sekarang?')">
                                            <i class="bi bi-arrow-counterclockwise"></i> Kembalikan Buku
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php 
                    } // End While
                } else { 
                ?>
                    <div class="col-12">
                        <div class="alert alert-secondary d-flex align-items-center border-0" role="alert">
                            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                            <div>
                                <strong>Tidak ada buku yang dipinjam.</strong> Yuk, lihat katalog dan mulai membaca!
                                <a href="katalog.php" class="alert-link ms-2">Lihat Katalog</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

    </div>
</main>

<div style="height: 50px;"></div>

<?php include 'footer.php'; ?>
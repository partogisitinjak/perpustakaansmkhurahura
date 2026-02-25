<?php
include 'header_admin.php';
include '../koneksi.php';

// Ambil tab dari URL, default ke peminjaman
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'peminjaman';
?>

<main>
    <div class="container">
        
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <div class="d-flex align-items-center gap-3">
                <a href="laporan.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
                <div>
                    <h2 class="fw-bold text-primary mb-0">Detail Laporan</h2>
                    <p class="text-muted mb-0">Lihat data lengkap dan cetak laporan.</p>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs mb-4 no-print">
            <li class="nav-item">
                <a class="nav-link <?php echo ($tab == 'peminjaman') ? 'active fw-bold' : ''; ?>" href="?tab=peminjaman">Peminjaman</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($tab == 'sanksi') ? 'active fw-bold' : ''; ?>" href="?tab=sanksi">Sanksi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($tab == 'kinerja') ? 'active fw-bold' : ''; ?>" href="?tab=kinerja">Rapor Nilai</a>
            </li>
        </ul>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <?php if ($tab == 'peminjaman') { ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-primary">📚 Riwayat Peminjaman</h5>
                        <a href="laporan_print.php" target="_blank" class="btn btn-dark btn-sm"><i class="bi bi-printer"></i> Cetak Laporan Resmi</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-dark">
                                <tr><th>No</th><th>Nama</th><th>Judul Buku</th><th>Tgl Pinjam</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                <?php
                                $q = mysqli_query($koneksi, "SELECT p.*, a.nama, b.judul FROM peminjaman p JOIN anggota a ON p.id_anggota = a.id JOIN buku b ON p.id_buku = b.id ORDER BY p.waktu_pinjam DESC");
                                $no = 1;
                                while ($r = mysqli_fetch_assoc($q)) {
                                    $status = ($r['status_peminjaman'] == 'Dipinjam') ? '<span class="badge bg-warning text-dark">Dipinjam</span>' : '<span class="badge bg-success">Kembali</span>';
                                    echo "<tr><td class='text-center'>$no</td><td>{$r['nama']}</td><td>{$r['judul']}</td><td>".date('d/m/Y', strtotime($r['waktu_pinjam']))."</td><td class='text-center'>$status</td></tr>";
                                    $no++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                <?php } elseif ($tab == 'sanksi') { ?>
                    <h5 class="fw-bold text-danger mb-3">⚠️ Daftar Pelanggaran</h5>
                    <table class="table table-bordered align-middle">
                        <thead class="bg-danger text-white">
                            <tr><th>No</th><th>Nama</th><th>Poin Minus</th><th>Status Akun</th></tr>
                        </thead>
                        <tbody>
                            <?php
                            $q = mysqli_query($koneksi, "SELECT * FROM anggota WHERE role='anggota' AND (poin_pelanggaran > 0 OR status_akun='Dibekukan') ORDER BY poin_pelanggaran DESC");
                            $no = 1;
                            if(mysqli_num_rows($q)>0){
                                while ($r = mysqli_fetch_assoc($q)) {
                                    $status = ($r['status_akun'] == 'Dibekukan') ? '<span class="badge bg-danger">DIBEKUKAN</span>' : '<span class="badge bg-success">Aktif</span>';
                                    echo "<tr><td class='text-center'>$no</td><td>{$r['nama']}</td><td class='text-center text-danger fw-bold'>-{$r['poin_pelanggaran']}</td><td class='text-center'>$status</td></tr>";
                                    $no++;
                                }
                            } else { echo "<tr><td colspan='4' class='text-center'>Tidak ada pelanggaran.</td></tr>"; }
                            ?>
                        </tbody>
                    </table>

                <?php } elseif ($tab == 'kinerja') { ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-success">🏆 Rekapitulasi Rapor Anggota</h5>
						<a href="cetak_rekap_keaktifan.php" target="_blank" class="btn btn-dark btn-sm shadow-sm">
							<i class="bi bi-printer-fill"></i> Cetak Laporan Resmi
						</a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle table-hover">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th class="text-center">Rank</th>
                                    <th>Nama Anggota</th>
                                    <th class="text-center">Total XP</th>
                                    <th class="text-center bg-warning text-dark">Nilai Akhir</th>
                                    <th class="text-center" width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $q = mysqli_query($koneksi, "SELECT * FROM anggota WHERE role='anggota' ORDER BY poin DESC");
                                $rank = 1;
                                while ($row = mysqli_fetch_assoc($q)) {
                                    $id = $row['id'];
                                    // Hitung Nilai
                                    $buku = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as t FROM peminjaman WHERE id_anggota='$id'"))['t'];
                                    $hadir = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as t FROM presensi WHERE id_anggota='$id'"))['t'];
                                    
                                    $skor_buku = ($buku / 10) * 70; if($skor_buku>70) $skor_buku=70;
                                    $skor_hadir = ($hadir / 6) * 30;
                                    $bonus = ($hadir > 6) ? min(($skor_hadir-30), 20) : 0;
                                    $nilai = ($skor_buku + $bonus + $skor_hadir) - $row['poin_pelanggaran'];
                                    if($nilai > 100) $nilai = 100;
                                    $nilai_txt = number_format($nilai, 1);

                                    $icon = "#" . $rank;
                                    if ($rank == 1) $icon = "🥇"; elseif ($rank == 2) $icon = "🥈"; elseif ($rank == 3) $icon = "🥉";

                                    echo "<tr>
                                        <td class='text-center fw-bold'>$icon</td>
                                        <td class='fw-bold'>{$row['nama']}</td>
                                        <td class='text-center'>{$row['poin']}</td>
                                        <td class='text-center fw-bold bg-warning bg-opacity-10'>$nilai_txt</td>
                                        
                                        <td class='text-center'>
                                            <a href='cetak_rapor_siswa.php?id=$id' target='_blank' class='btn btn-sm btn-primary shadow-sm fw-bold'>
                                                <i class='bi bi-printer'></i> Cetak Rapor
                                            </a>
                                        </td>
                                    </tr>";
                                    $rank++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</main>

<style>
    @media print {
        .no-print, nav, header, footer, .btn { display: none !important; }
        body { background: white; margin: 0; padding: 0; }
        .card { border: none !important; shadow: none !important; }
        .table { width: 100% !important; border-color: #000 !important; }
        a { text-decoration: none; color: black; }
        /* Sembunyikan kolom aksi saat print tabel global */
        th:last-child, td:last-child { display: none; }
    }
</style>

<?php include 'footer_admin.php'; ?>
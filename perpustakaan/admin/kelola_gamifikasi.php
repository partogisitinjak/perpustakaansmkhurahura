<?php
include 'header_admin.php';
include '../koneksi.php';
?>

<main>
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary"><i class="bi bi-trophy-fill text-warning"></i> Laporan Poin & Nilai Mutu</h2>
            <p class="text-muted">Pantau peringkat XP dan Nilai Akhir Keaktifan anggota secara real-time.</p>
        </div>

        <div class="card border-0 shadow-lg">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-list-ol"></i> Data Nilai Anggota</h5>
                <span class="badge bg-light text-primary">Target: 10 Buku & 6 Absen</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center py-3" width="60">Rank</th>
                                <th>Nama Anggota</th>
                                <th>Level</th>
                                <th class="text-center">Total XP</th>
                                <th class="text-center">Pelanggaran</th>
                                <th class="text-center bg-warning bg-opacity-10">Nilai Akhir (0-100)</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Urutkan berdasarkan Poin Tertinggi
                        $query = "SELECT * FROM anggota WHERE role='anggota' ORDER BY poin DESC";
                        $result = mysqli_query($koneksi, $query);
                        $rank = 1;

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $id_user = $row['id'];

                                // --- 1. LOGIKA HITUNG NILAI (Sama persis dengan User) ---
                                // A. Ambil Data Jumlah
                                $q_b = mysqli_query($koneksi, "SELECT COUNT(*) as t FROM peminjaman WHERE id_anggota='$id_user'");
                                $jml_buku = mysqli_fetch_assoc($q_b)['t'];

                                $q_a = mysqli_query($koneksi, "SELECT COUNT(*) as t FROM presensi WHERE id_anggota='$id_user'");
                                $jml_hadir = mysqli_fetch_assoc($q_a)['t'];

                                $minus = $row['poin_pelanggaran'];

                                // B. Rumus (Target: Buku 10, Absen 6)
                                $skor_buku = ($jml_buku / 10) * 70;
                                if ($skor_buku > 70) $skor_buku = 70;

                                $skor_hadir = ($jml_hadir / 6) * 30;
                                
                                // Bonus
                                $bonus = 0;
                                if ($jml_hadir > 6) {
                                    $lebih = $skor_hadir - 30;
                                    $bonus = ($lebih > 20) ? 20 : $lebih;
                                    $skor_hadir = 30;
                                }
                                $skor_buku_total = $skor_buku + $bonus;
                                if ($skor_buku_total > 90) $skor_buku_total = 90;

                                // Total
                                $nilai_akhir = ($skor_buku_total + $skor_hadir) - $minus;
                                if ($nilai_akhir > 100) $nilai_akhir = 100;
                                $nilai_akhir = number_format($nilai_akhir, 1);

                                // C. Tentukan Predikat/Warna Nilai
                                $badge_nilai = "bg-secondary";
                                if ($nilai_akhir >= 90) $badge_nilai = "bg-success"; // A
                                elseif ($nilai_akhir >= 75) $badge_nilai = "bg-info text-dark"; // B
                                elseif ($nilai_akhir >= 60) $badge_nilai = "bg-warning text-dark"; // C
                                else $badge_nilai = "bg-danger"; // D

                                // --- 2. TAMPILAN TABEL ---
                                // Ikon Rank
                                $icon_rank = "#" . $rank;
                                $bg_row = "";
                                if ($rank == 1) { $icon_rank = "🥇"; $bg_row = "bg-warning bg-opacity-10"; }
                                elseif ($rank == 2) { $icon_rank = "🥈"; }
                                elseif ($rank == 3) { $icon_rank = "🥉"; }

                                // Level
                                $p = $row['poin'];
                                $level = "Pemula";
                                $badge_level = "bg-secondary";
                                if ($p >= 100 && $p < 300) { $level = "Pecinta Buku"; $badge_level = "bg-info text-dark"; } 
                                elseif ($p >= 300) { $level = "Pustakawan Ahli"; $badge_level = "bg-success"; }

                                echo "<tr class='$bg_row'>";
                                echo "<td class='text-center fw-bold fs-5'>$icon_rank</td>";
                                echo "<td class='fw-bold'>" . htmlspecialchars($row['nama']) . " <br><small class='text-muted'>" . $row['kode_anggota'] . "</small></td>";
                                echo "<td><span class='badge $badge_level rounded-pill'>$level</span></td>";
                                echo "<td class='text-center fw-bold text-primary'>$p XP</td>";
                                
                                // Pelanggaran
                                $txt_minus = ($minus > 0) ? "<span class='text-danger fw-bold'>-$minus</span>" : "<span class='text-muted'>-</span>";
                                echo "<td class='text-center'>$txt_minus</td>";

                                // KOLOM BARU: NILAI AKHIR
                                echo "<td class='text-center bg-warning bg-opacity-10'>
                                        <span class='badge $badge_nilai fs-6'>$nilai_akhir</span>
                                      </td>";
                                
                                echo "</tr>";
                                $rank++;
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-5 text-muted'>Belum ada data anggota.</td></tr>";
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
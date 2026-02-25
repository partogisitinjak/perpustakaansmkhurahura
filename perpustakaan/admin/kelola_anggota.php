<?php
include 'header_admin.php';
include '../koneksi.php';
?>

<main>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary"><i class="bi bi-people-fill"></i> Kelola Anggota & Sanksi</h2>
            
            <a href="proses_reset_semester.php" class="btn btn-danger shadow-sm" onclick="return confirm('BAHAYA! \n\nAnda yakin ingin MERESET SEMESTER? \nSemua poin, absensi, dan riwayat akan dihapus menjadi 0. \n\nTindakan ini tidak bisa dibatalkan!')">
                <i class="bi bi-arrow-counterclockwise"></i> Reset Semester Baru
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center py-3">No</th>
                                <th>Kode</th>
                                <th>Nama Lengkap</th>
                                <th>Status Akun</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $query = "SELECT * FROM anggota WHERE role = 'anggota' ORDER BY nama ASC";
                        $result = mysqli_query($koneksi, $query);
                        $no = 1;

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Badge Status Akun
                                if ($row['status_akun'] == 'Aktif') {
                                    $badge_akun = "<span class='badge bg-success'><i class='bi bi-check-circle'></i> Aktif</span>";
                                    $tombol_aksi = "<a href='input_sanksi.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning text-dark fw-bold me-1'><i class='bi bi-exclamation-triangle'></i> Sanksi</a>";
                                } else {
                                    $badge_akun = "<span class='badge bg-danger'><i class='bi bi-lock-fill'></i> Dibekukan</span>";
                                    $tombol_aksi = "<a href='proses_buka_blokir.php?id=" . $row['id'] . "' class='btn btn-sm btn-success fw-bold me-1' onclick=\"return confirm('Buka blokir akun ini?')\"><i class='bi bi-unlock-fill'></i> Buka</a>";
                                }
                                
                                echo "<tr>";
                                echo "<td class='text-center'>" . $no++ . "</td>";
                                echo "<td class='fw-bold text-secondary'>" . $row['kode_anggota'] . "</td>";
                                echo "<td class='fw-bold'>" . htmlspecialchars($row['nama']) . "</td>";
                                echo "<td>" . $badge_akun . "</td>";
                                echo "<td class='text-center'>
                                        $tombol_aksi
                                        <a href='edit_anggota.php?id=" . $row['id'] . "' class='btn btn-sm btn-secondary'><i class='bi bi-pencil-square'></i> Edit</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center py-4 text-muted'>Belum ada data.</td></tr>";
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
<?php
session_start();
if (!isset($_SESSION['id_anggota'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';
$id_anggota = $_SESSION['id_anggota'];

// --- 1. AMBIL DATA ANGGOTA & PELANGGARAN ---
$q_user = mysqli_query($koneksi, "SELECT * FROM anggota WHERE id = '$id_anggota'");
$user = mysqli_fetch_assoc($q_user);
$minus_poin = $user['poin_pelanggaran']; // Poin pengurang (buku rusak/hilang/bolos)

// --- 2. HITUNG PEMINJAMAN (Bobot 70%) ---
// Target: 30 Buku per semester
$target_buku = 10; 
$q_pinjam = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE id_anggota = '$id_anggota'");
$d_pinjam = mysqli_fetch_assoc($q_pinjam);
$jumlah_buku = $d_pinjam['total'];

// Rumus: (Jumlah Buku / 30) * 70
// Jika buku > 30, nilai mentok di 70 (kecuali ada bonus limpahan)
$skor_peminjaman_murni = ($jumlah_buku / $target_buku) * 70;
if ($skor_peminjaman_murni > 70) $skor_peminjaman_murni = 70;


// --- 3. HITUNG KEHADIRAN (Bobot 30%) ---
// Target: 24 Pertemuan (4x sebulan * 6 bulan)
$target_hadir = 6;
$q_hadir = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM presensi WHERE id_anggota = '$id_anggota'");
$d_hadir = mysqli_fetch_assoc($q_hadir);
$jumlah_hadir = $d_hadir['total'];

// Rumus: (Jumlah Hadir / 24) * 30
$skor_kehadiran_murni = ($jumlah_hadir / $target_hadir) * 30;

// --- 4. LOGIKA BONUS LIMPAHAN (Sesuai PDF) ---
// "Jika lebih dari 4x mendapat poin tambahan yang dilimpahkan ke peminjaman maximal 20%"
$bonus_limpahan = 0;
if ($jumlah_hadir > $target_hadir) {
    // Hitung kelebihan skor kehadiran
    $kelebihan_skor = $skor_kehadiran_murni - 30;
    
    // Batasi bonus maksimal 20% (sekitar 20 poin)
    if ($kelebihan_skor > 20) {
        $bonus_limpahan = 20;
    } else {
        $bonus_limpahan = $kelebihan_skor;
    }
    
    // Mentokkan skor kehadiran di 30 (karena kelebihannya sudah jadi bonus)
    $skor_kehadiran_murni = 30;
}

// Tambahkan bonus ke skor peminjaman
$skor_peminjaman_total = $skor_peminjaman_murni + $bonus_limpahan;
// Pastikan tidak lebih dari 100 (secara teori bobot max 70, tapi plus bonus bisa naik)
if ($skor_peminjaman_total > 90) $skor_peminjaman_total = 90; // Cap logis


// --- 5. NILAI AKHIR (TOTAL) ---
// Total = Skor Peminjaman + Skor Kehadiran - Pelanggaran
$nilai_akhir = ($skor_peminjaman_total + $skor_kehadiran_murni) - $minus_poin;

// --- TAMBAHAN BARU: BATASI MAKSIMAL 100 ---
if ($nilai_akhir > 100) {
    $nilai_akhir = 100;
}
// -------------------------------------------

// Format angka agar rapi (maksimal 2 desimal)
$skor_peminjaman_display = number_format($skor_peminjaman_total, 1);
// ... dst

// Format angka agar rapi (maksimal 2 desimal)
$skor_peminjaman_display = number_format($skor_peminjaman_total, 1);
$skor_kehadiran_display = number_format($skor_kehadiran_murni, 1);
$nilai_akhir_display = number_format($nilai_akhir, 1);

// Tentukan Predikat
$predikat = "Cukup";
if ($nilai_akhir >= 90) $predikat = "Sangat Baik (A)";
elseif ($nilai_akhir >= 80) $predikat = "Baik (B)";
elseif ($nilai_akhir >= 70) $predikat = "Cukup (C)";
else $predikat = "Remedial (D)";

?>

<?php include 'header.php'; ?>

<style>
    .score-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        text-align: center;
        margin-bottom: 30px;
    }
    .big-score {
        font-size: 4rem;
        font-weight: bold;
        color: #0779e4;
        margin: 10px 0;
    }
    .progress-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 5px solid #0779e4;
    }
    .progress-bar-bg {
        background: #e9ecef;
        height: 15px;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 5px;
    }
    .progress-bar-fill {
        height: 100%;
        background: #28a745;
        transition: width 1s;
    }
    .penalty-box {
        background: #fff5f5;
        color: #c0392b;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #ffcccc;
        margin-top: 20px;
    }
</style>

<main>
    <div class="container">
        <h1>Laporan Keaktifan Anggota Perpustakaan</h1>
        <p>Evaluasi keaktifan berdasarkan Peraturan Perpustakaan.</p>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            
            <div class="score-card">
                <h3>Nilai Keaktifan Anggota Perpustakaan</h3>
                <div class="big-score"><?php echo $nilai_akhir_display; ?></div>
                <div style="font-size: 1.2rem; font-weight: bold;"><?php echo $predikat; ?></div>
                <p style="color: #666; margin-top: 10px;">
                    (70% Peminjaman + 30% Kehadiran) - Pelanggaran *jika ada
                </p>
            </div>

            <div>
                <div class="progress-box">
                    <div style="display:flex; justify-content:space-between;">
                        <strong>📚 Peminjaman Buku (Bobot 70%)</strong>
                        <span><?php echo $jumlah_buku; ?> / <?php echo $target_buku; ?> Buku</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: <?php echo min(($jumlah_buku/$target_buku)*100, 100); ?>%;"></div>
                    </div>
                    <small>Skor Kontribusi: <strong><?php echo $skor_peminjaman_display; ?>%</strong> 
                    <?php if($bonus_limpahan > 0) echo "(Termasuk Bonus Kehadiran +$bonus_limpahan)"; ?>
                    </small>
                </div>

                <div class="progress-box" style="border-left-color: #ffc107;">
                    <div style="display:flex; justify-content:space-between;">
                        <strong>🏫 Kehadiran Kunjungan (Bobot 30%)</strong>
                        <span><?php echo $jumlah_hadir; ?> / <?php echo $target_hadir; ?> Kunjungan</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: <?php echo min(($jumlah_hadir/$target_hadir)*100, 100); ?>%; background-color: #ffc107;"></div>
                    </div>
                    <small>Skor Kontribusi: <strong><?php echo $skor_kehadiran_display; ?>%</strong></small>
                </div>

                <?php if ($minus_poin > 0) { ?>
                <div class="penalty-box">
                    <strong>⚠️ Catatan Pelanggaran</strong>
                    <p>Nilai Anda dikurangi <strong>-<?php echo $minus_poin; ?></strong> poin karena:</p>
                    <ul>
                        <li>Terlambat/Buku Rusak/Hilang/Bolos Kunjungan</li>
                    </ul>
                </div>
                <?php } else { ?>
                    <div style="color: green; margin-top: 10px;">✅ Tidak ada pelanggaran tercatat.</div>
                <?php } ?>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
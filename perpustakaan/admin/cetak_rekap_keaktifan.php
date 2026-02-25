<?php
include '../koneksi.php';
session_start();
if (!isset($_SESSION['id_admin'])) { header("Location: index.php"); exit(); }

// Atur Waktu
date_default_timezone_set('Asia/Jakarta');

function tanggal_indonesia($tanggal) {
    $bulan = array (1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
$tanggal_cetak = tanggal_indonesia(date('Y-m-d'));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Laporan Nilai Keaktifan</title>
    <style>
        /* CSS CETAK RESMI */
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; margin: 10mm 15mm; }
        
        .header-laporan { text-align: center; margin-bottom: 25px; }
        .header-laporan h2 { margin: 0; font-weight: bold; font-size: 14pt; }
        .header-laporan p { margin: 5px 0 0 0; font-size: 12pt; }
        
        /* TABEL SESUAI REQUEST */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { border: 1px solid black; padding: 8px; text-align: left; font-size: 11pt; }
        table th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        
        /* KOLOM TENGAH */
        table td:nth-child(1), /* No */
        table td:nth-child(3), /* Kode */
        table td:nth-child(4), /* XP */
        table td:nth-child(5), /* Nilai */
        table td:nth-child(6)  /* Predikat */ 
        { text-align: center; }

        /* TANDA TANGAN (RATA KIRI & FLOAT) */
        .signature-container { width: 100%; margin-top: 50px; page-break-inside: avoid; }
        .signature-box { width: 40%; text-align: left; float: left; }
        .signature-box.right { float: right; }
        .space-ttd { height: 80px; }
        .name-ttd { font-weight: bold; text-decoration: underline; }
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body onload="window.print()">

    <div class="header-laporan">
        <h2>LAPORAN NILAI KEAKTIFAN ANGGOTA</h2>
        <p>PERPUSTAKAAN DIGITAL SMK HURAHURA</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">NO</th>
                <th style="width: 30%;">NAMA ANGGOTA</th>
                <th style="width: 20%;">KODE ANGGOTA</th>
                <th style="width: 10%;">XP</th>
                <th style="width: 15%;">NILAI AKHIR</th>
                <th style="width: 20%;">PREDIKAT</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Ambil semua anggota diurutkan dari nilai tertinggi (poin)
            $query = "SELECT * FROM anggota WHERE role='anggota' ORDER BY poin DESC";
            $result = mysqli_query($koneksi, $query);
            $no = 1;

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['id'];

                    // --- HITUNG NILAI (LOGIKA SAMA DENGAN DASHBOARD) ---
                    $buku = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as t FROM peminjaman WHERE id_anggota='$id'"))['t'];
                    $hadir = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as t FROM presensi WHERE id_anggota='$id'"))['t'];
                    
                    // Rumus
                    $skor_buku = ($buku / 10) * 70; if($skor_buku>70) $skor_buku=70;
                    $skor_hadir = ($hadir / 6) * 30;
                    
                    // Bonus
                    $bonus = 0;
                    if ($hadir > 6) {
                        $lebih = $skor_hadir - 30;
                        $bonus = ($lebih > 20) ? 20 : $lebih;
                        $skor_hadir = 30;
                    }
                    $skor_buku_total = $skor_buku + $bonus;
                    if ($skor_buku_total > 90) $skor_buku_total = 90;

                    // Nilai Akhir
                    $nilai = ($skor_buku_total + $skor_hadir) - $row['poin_pelanggaran'];
                    if($nilai > 100) $nilai = 100;
                    $nilai_txt = number_format($nilai, 1);

                    // Predikat
                    if($nilai >= 90) $pred = "A (Sangat Baik)";
                    elseif($nilai >= 75) $pred = "B (Baik)";
                    elseif($nilai >= 60) $pred = "C (Cukup)";
                    else $pred = "D (Kurang)";
                    // ---------------------------------------------------

                    echo "<tr>
                            <td>$no</td>
                            <td style='text-align: left; padding-left: 10px;'>{$row['nama']}</td>
                            <td>{$row['kode_anggota']}</td>
                            <td>{$row['poin']}</td>
                            <td style='font-weight:bold;'>$nilai_txt</td>
                            <td>$pred</td>
                          </tr>";
                    $no++;
                }
            } else {
                echo "<tr><td colspan='6'>Belum ada data anggota.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="signature-container clearfix">
        <div class="signature-box">
            <br>
            Hormat kami,<br>
            Pustakawan SMK Hurahura
            <div class="space-ttd"></div>
            <div class="name-ttd">( Nama Lengkap Pustakawan )</div>
            <div>NIP. ...........................</div>
        </div>

        <div class="signature-box right">
            Jakarta, <?php echo $tanggal_cetak; ?><br>
            Mengetahui,<br>
            Kepala SMK Hurahura
            <div class="space-ttd"></div>
            <div class="name-ttd">( Nama Lengkap Kepala Sekolah )</div>
            <div>NIP. ...........................</div>
        </div>
    </div>

</body>
</html>
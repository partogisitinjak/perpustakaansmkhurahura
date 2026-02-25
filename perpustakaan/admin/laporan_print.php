<?php
// Atur zona waktu agar tanggal cetak akurat
date_default_timezone_set('Asia/Jakarta');

include '../koneksi.php';

// --- FUNGSI FORMAT TANGGAL INDONESIA ---
function tanggal_indonesia($tanggal) {
    $bulan = array (
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

$tanggal_cetak = tanggal_indonesia(date('Y-m-d'));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Cetak Laporan Peminjaman</title>
    <style>
        /* CSS UTAMA */
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            margin: 10mm 20mm;
        }
        
        .header-laporan {
            text-align: center;
            margin-bottom: 30px;
        }
        .header-laporan h2 {
            margin: 0;
            text-transform: uppercase;
            font-size: 16pt;
            font-weight: bold;
        }
        .header-laporan p {
            margin: 5px 0 0 0;
            font-size: 11pt;
        }

        /* TABEL */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            font-size: 11pt;
        }
        table th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }
        table td {
            vertical-align: top;
        }
        table td:first-child {
            text-align: center;
        }

        /* --- CSS TANDA TANGAN (YANG DIUBAH) --- */
        .signature-container {
            width: 100%;
            margin-top: 50px;
            page-break-inside: avoid;
        }
        
        .signature-box {
            width: 40%; /* Lebar area tanda tangan */
            text-align: left; /* <--- INI KUNCINYA: TEKS RATA KIRI */
            float: left;
        }
        
        /* Posisi Kotak Kanan */
        .signature-box.right {
            float: right;
        }
        
        /* Posisi Kotak Kiri */
        .signature-box.left {
            float: left;
        }

        .space-ttd {
            height: 70px; /* Ruang tanda tangan basah */
        }
        
        .name-ttd {
            font-weight: bold;
            text-decoration: underline;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        @media print {
            .no-print { display: none; }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="header-laporan">
        <h2>LAPORAN PEMINJAMAN & PENGEMBALIAN BUKU</h2>
        <p>PERPUSTAKAAN SMK HURAHURA</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Nama Anggota</th>
                <th style="width: 30%;">Judul Buku</th>
                <th style="width: 15%;">Tgl Pinjam</th>
                <th style="width: 15%;">Tgl Kembali</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT p.*, a.nama, b.judul 
                      FROM peminjaman p 
                      JOIN anggota a ON p.id_anggota = a.id 
                      JOIN buku b ON p.id_buku = b.id 
                      ORDER BY p.waktu_pinjam DESC";
            
            $result = mysqli_query($koneksi, $query);
            $no = 1;

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $tgl_pinjam = date('d/m/Y', strtotime($row['waktu_pinjam']));
                    $tgl_kembali = ($row['waktu_kembali']) ? date('d/m/Y', strtotime($row['waktu_kembali'])) : '-';
                    $status = ($row['status_peminjaman'] == 'Dipinjam') ? 'Dipinjam' : 'Kembali';
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td><?php echo htmlspecialchars($row['judul']); ?></td>
                    <td style="text-align: center;"><?php echo $tgl_pinjam; ?></td>
                    <td style="text-align: center;"><?php echo $tgl_kembali; ?></td>
                    <td style="text-align: center;"><?php echo $status; ?></td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>Belum ada data peminjaman.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div style="font-size: 10pt; margin-bottom: 20px;">
        <em>Dicetak pada: <?php echo date('d-m-Y H:i:s'); ?></em>
    </div>

    <div class="signature-container clearfix">
        
        <div class="signature-box left">
            <br> Hormat kami,<br>
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
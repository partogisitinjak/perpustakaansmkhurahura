<?php
include 'koneksi.php'; // Hubungkan ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $status = $_POST['status'];
    $nomor_induk_input = $_POST['nomor_induk'];
    $password = $_POST['password'];

    // --- LOGIKA PEMBUATAN KODE ANGGOTA (SAMA SEPERTI SEBELUMNYA) ---
    $counterFile = 'counter.txt';
    $nomorUrut = (int)trim(file_get_contents($counterFile)) + 1;
    $kodePendaftar = sprintf('%02d', $nomorUrut);
    file_put_contents($counterFile, $nomorUrut);
    $tahunRegistrasi = date('Y');
    $kodeNomorInduk = ($status == 'pegawai') ? substr($nomor_induk_input, -4) : $nomor_induk_input;
    $kodeNomorInduk = sprintf('%04d', $kodeNomorInduk);
    $kodeRandom = rand(100, 999);
    $kodeAnggota = $kodePendaftar . '-' . $tahunRegistrasi . '-' . $kodeNomorInduk . '-' . $kodeRandom;

    // --- PROSES MENYIMPAN KE DATABASE ---
    // Enkripsi password untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Siapkan query SQL untuk menyimpan data
    $query = "INSERT INTO anggota (kode_anggota, nama, password, status) VALUES ('$kodeAnggota', '$nama', '$hashed_password', '$status')";

    $result = mysqli_query($koneksi, $query);

    include 'header.php';
    echo "<main><div class='container'>";
    if ($result) {
        echo "<h2>Pendaftaran Berhasil!</h2>";
        echo "<div class='result-box'>";
        echo "<p>Selamat, <strong>" . htmlspecialchars($nama) . "</strong>! Anda telah terdaftar.</p>";
        echo "<p>Gunakan Kode Anggota berikut untuk login:</p>";
        echo "<h3 class='member-code'>" . $kodeAnggota . "</h3>";
        echo "<p>Password Anda adalah yang baru saja Anda buat.</p>";
        echo "<a href='login.php' class='button'>Login Sekarang</a>";
        echo "</div>";
    } else {
        echo "<h2>Pendaftaran Gagal!</h2>";
        echo "<p>Terjadi kesalahan: " . mysqli_error($koneksi) . "</p>";
    }
    echo "</div></main>";
    include 'footer.php';
}
?>
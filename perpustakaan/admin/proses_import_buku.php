<?php
include '../koneksi.php';

// Fungsi membersihkan karakter aneh di awal file (BOM)
function remove_utf8_bom($text) {
    $bom = pack('H*','EFBBBF');
    $text = preg_replace("/^$bom/", '', $text);
    return $text;
}

if (isset($_POST['import'])) {
    
    if (isset($_FILES['file_csv']) && $_FILES['file_csv']['error'] == 0) {
        
        $filename = $_FILES['file_csv']['tmp_name'];
        
        // Baca file sebagai array baris per baris
        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        if ($lines) {
            $sukses = 0;
            $update = 0;
            $gagal  = 0;
            $baris  = 1;
            $error_log = "";

            foreach ($lines as $key => $line) {
                // 1. Bersihkan BOM (Karakter hantu Excel)
                if ($key == 0) $line = remove_utf8_bom($line);

                // 2. LOGIKA PINTAR: COBA DUA JENIS PEMISAH
                // Coba pakai Titik Koma (;) dulu (Format Excel Indo)
                $data = str_getcsv($line, ";");
                
                // Jika ternyata cuma terbaca 1 kolom, coba pakai Koma (,)
                if (count($data) < 2) {
                    $data = str_getcsv($line, ",");
                }

                // Lewati Header (Baris Judul)
                if ($key == 0) continue; 

                // 3. Validasi Jumlah Kolom (Harus ada 4: Judul, Penulis, Penerbit, Stok)
                if (count($data) >= 4) {
                    
                    // Ambil Data & Rapikan
                    $judul    = mysqli_real_escape_string($koneksi, trim($data[0]));
                    $penulis  = mysqli_real_escape_string($koneksi, trim($data[1]));
                    $penerbit = mysqli_real_escape_string($koneksi, trim($data[2]));
                    
                    // Bersihkan Stok (Hanya ambil angka)
                    $stok_raw = $data[3];
                    $stok     = (int) preg_replace('/[^0-9]/', '', $stok_raw);

                    if (!empty($judul) && $stok > 0) {
                        
                        // CEK DUPLIKAT (Case Insensitive)
                        $cek = mysqli_query($koneksi, "SELECT id, stok FROM buku 
                                                       WHERE LOWER(judul) = LOWER('$judul') 
                                                       AND LOWER(penulis) = LOWER('$penulis')");
                        
                        if (mysqli_num_rows($cek) > 0) {
                            // UPDATE STOK
                            $d = mysqli_fetch_assoc($cek);
                            $id_lama = $d['id'];
                            $stok_baru = $d['stok'] + $stok;
                            mysqli_query($koneksi, "UPDATE buku SET stok='$stok_baru' WHERE id='$id_lama'");
                            $update++;
                        } else {
                            // INSERT BARU
                            $q = "INSERT INTO buku (judul, penulis, penerbit, stok, status_ketersediaan) 
                                  VALUES ('$judul', '$penulis', '$penerbit', '$stok', 'Tersedia')";
                            if(mysqli_query($koneksi, $q)) {
                                $sukses++;
                            } else {
                                $gagal++;
                                $error_log .= "Baris $baris: Gagal Insert Database.\\n";
                            }
                        }
                    } else {
                        $gagal++; // Judul kosong atau Stok 0
                        $error_log .= "Baris $baris: Judul kosong atau Stok 0 (Stok terbaca: $stok_raw).\\n";
                    }
                } else {
                    $gagal++;
                    $error_log .= "Baris $baris: Format Salah. Hanya terbaca " . count($data) . " kolom.\\n";
                }
                $baris++;
            }

            // TAMPILKAN HASIL
            if ($gagal > 0) {
                echo "<script>
                        alert('HASIL IMPORT:\\n✅ Masuk: $sukses\\n🔄 Update: $update\\n❌ Gagal: $gagal\\n\\nLOG ERROR:\\n$error_log');
                        window.location.href = 'kelola_buku.php';
                      </script>";
            } else {
                echo "<script>
                        alert('SUKSES FULL! \\n✅ Data Masuk: $sukses\\n🔄 Data Update: $update');
                        window.location.href = 'kelola_buku.php';
                      </script>";
            }

        } else {
            echo "<script>alert('File kosong.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Upload error.'); window.history.back();</script>";
    }
}
?>
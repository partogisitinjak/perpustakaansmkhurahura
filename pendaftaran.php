<?php include 'header.php'; ?>

<div class="container d-flex justify-content-center align-items-center py-5" style="min-height: 85vh;">
    <div class="card shadow-lg border-0 rounded-4" style="width: 100%; max-width: 600px;">
        
        <div class="card-header bg-primary text-white text-center py-4 rounded-top-4">
            <h3 class="fw-bold mb-0"><i class="bi bi-person-plus-fill me-2"></i> Registrasi Anggota</h3>
            <p class="mb-0 opacity-75 small">Bergabunglah sekarang dan nikmati ribuan koleksi buku.</p>
        </div>

        <div class="card-body p-4 p-md-5">
            
            <form action="proses_pendaftaran.php" method="POST">
                
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap" required>
                    <label for="nama"><i class="bi bi-person"></i> Nama Lengkap</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select" id="status" name="status" required>
                        <option value="" selected disabled>-- Pilih Status --</option>
                        <option value="siswa">Siswa</option>
                        <option value="pegawai">Guru / Pegawai Sekolah</option>
                    </select>
                    <label for="status"><i class="bi bi-mortarboard"></i> Status Keanggotaan</label>
                </div>

                <div class="form-floating mb-1">
                    <input type="number" class="form-control" id="nomor_induk" name="nomor_induk" placeholder="NIS / NUPTK" required>
                    <label for="nomor_induk"><i class="bi bi-card-text"></i> NIS / NUPTK</label>
                </div>
                <div class="form-text mb-3 text-muted small ms-1">
                    <i class="bi bi-info-circle"></i> Untuk Guru/Pegawai, cukup masukkan 4 angka terakhir NUPTK.
                </div>

                <div class="form-floating mb-4">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password"><i class="bi bi-lock"></i> Buat Password</label>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm">
                        <i class="bi bi-check-circle-fill me-2"></i> Daftar Sekarang
                    </button>
                    <a href="login.php" class="btn btn-outline-secondary">
                        Sudah punya akun? Login di sini
                    </a>
                </div>

            </form>
        </div>
        
        <div class="card-footer text-center py-3 bg-light rounded-bottom-4 text-muted small">
            &copy; 2025 Perpustakaan Digital SMK Hurahura
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
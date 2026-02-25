<?php include 'header.php'; ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg border-0 rounded-4" style="width: 100%; max-width: 450px;">
        <div class="card-body p-5">
            
            <div class="text-center mb-4">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 70px; height: 70px; font-size: 30px;">
                    <i class="bi bi-person-lock"></i>
                </div>
                <h3 class="fw-bold text-primary">Login Anggota</h3>
                <p class="text-muted small">Silakan masuk untuk mengakses layanan perpustakaan.</p>
            </div>

            <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        <?php
                        if ($_GET['error'] == 'password') echo "Password salah!";
                        elseif ($_GET['error'] == 'username') echo "Kode Anggota tidak ditemukan!";
                        else echo "Login gagal.";
                        ?>
                    </div>
                </div>
            <?php } ?>

            <form action="proses_login.php" method="POST">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="kode_anggota" name="kode_anggota" placeholder="XX-YYYY-XXXX" required>
                    <label for="kode_anggota"><i class="bi bi-card-heading"></i> Kode Anggota</label>
                </div>
                
                <div class="form-floating mb-4">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password"><i class="bi bi-key"></i> Password</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm mb-3">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk Sekarang
                </button>
            </form>

            <div class="text-center mt-4 pt-3 border-top">
                <p class="text-muted mb-2">Belum punya akun anggota?</p>
                <a href="pendaftaran.php" class="btn btn-outline-success w-100 fw-bold">
                    <i class="bi bi-person-plus-fill"></i> Daftar Anggota Baru
                </a>
            </div>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
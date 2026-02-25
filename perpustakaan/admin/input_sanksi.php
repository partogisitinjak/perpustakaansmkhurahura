<?php
include 'header_admin.php';
include '../koneksi.php';

// Ambil ID Anggota
$id = $_GET['id'];
$q = mysqli_query($koneksi, "SELECT * FROM anggota WHERE id='$id'");
$data = mysqli_fetch_assoc($q);
?>

<main>
    <div class="container d-flex justify-content-center py-4">
        
        <div class="card border-0 shadow-lg rounded-4" style="width: 100%; max-width: 700px;">
            
            <div class="card-header bg-danger text-white py-3 rounded-top-4">
                <h5 class="mb-0 fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Beri Sanksi & Pengurangan Poin</h5>
            </div>

            <div class="card-body p-4 p-md-5">
                
                <div class="alert alert-light border d-flex align-items-center mb-4">
                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger me-3">
                        <i class="bi bi-person-x-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-bold">Target Sanksi</small>
                        <h5 class="mb-0 fw-bold text-dark"><?php echo htmlspecialchars($data['nama']); ?></h5>
                        <small class="text-secondary">Kode: <?php echo $data['kode_anggota']; ?></small>
                    </div>
                </div>

                <form method="POST" action="proses_sanksi.php">
                    <input type="hidden" name="id_anggota" value="<?php echo $id; ?>">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Jenis Pelanggaran</label>
                        <select name="jenis_sanksi" id="jenis_sanksi" class="form-select form-select-lg" required onchange="updatePoin()">
                            <option value="" selected disabled>-- Pilih Kasus --</option>
                            <option value="rusak">📚 Buku Rusak / Robek (-10 XP)</option>
                            <option value="hilang">🚫 Buku Hilang (-20 XP + Suspend)</option>
                            <option value="bolos">🏃 Bolos Kunjungan (-5 XP)</option>
                            <option value="lainnya">✏️ Pelanggaran Lain (Manual)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-danger">Poin yang Dikurangi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-danger text-white fw-bold">-</span>
                            <input type="number" name="poin_minus" id="poin_minus" class="form-control fw-bold text-danger" value="0" required>
                            <span class="input-group-text">XP</span>
                        </div>
                        <div class="form-text text-muted">Masukkan angka positif, sistem otomatis menguranginya.</div>
                    </div>

                    <div id="alert_suspend" class="alert alert-warning d-none border-warning">
                        <i class="bi bi-lock-fill"></i> <strong>PERHATIAN:</strong> Akun anggota ini akan otomatis <strong>DIBEKUKAN (Suspend)</strong> dan tidak bisa meminjam buku sampai sanksi dicabut.
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Catatan / Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Buku Laskar Pelangi halaman 10 sobek terkena air..." required></textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end pt-3 border-top">
                        <a href="kelola_anggota.php" class="btn btn-light border fw-bold px-4">Batal</a>
                        <button type="submit" class="btn btn-danger fw-bold px-4 shadow-sm">
                            <i class="bi bi-gavel"></i> Konfirmasi Sanksi
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>

<script>
function updatePoin() {
    var jenis = document.getElementById('jenis_sanksi').value;
    var inputPoin = document.getElementById('poin_minus');
    var alertBox = document.getElementById('alert_suspend');

    // Reset Alert
    alertBox.classList.add('d-none');

    if (jenis == 'rusak') {
        inputPoin.value = 10;
        inputPoin.readOnly = true;
    } 
    else if (jenis == 'hilang') {
        inputPoin.value = 20;
        inputPoin.readOnly = true;
        alertBox.classList.remove('d-none'); // Tampilkan peringatan suspend
    } 
    else if (jenis == 'bolos') {
        inputPoin.value = 5;
        inputPoin.readOnly = true;
    } 
    else {
        inputPoin.value = 0;
        inputPoin.readOnly = false; // Boleh isi manual
        inputPoin.focus();
    }
}
</script>

<?php include 'footer_admin.php'; ?>
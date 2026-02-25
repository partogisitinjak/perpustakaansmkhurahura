<?php
include 'header_admin.php';
include '../koneksi.php';

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM anggota WHERE id='$id'");
$data = mysqli_fetch_assoc($query);
?>

<main>
    <div class="container d-flex justify-content-center">
        <div class="card border-0 shadow-lg" style="width: 100%; max-width: 600px;">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-person-gear"></i> Edit Data Anggota</h5>
            </div>
            <div class="card-body p-4">
                
                <form action="proses_update_anggota.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Kode Anggota</label>
                        <input type="text" class="form-control bg-light" value="<?php echo $data['kode_anggota']; ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="siswa" <?php if($data['status'] == 'siswa') echo 'selected'; ?>>Siswa</option>
                            <option value="pegawai" <?php if($data['status'] == 'pegawai') echo 'selected'; ?>>Pegawai / Guru</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Status Akun</label>
                        <div class="p-3 border rounded bg-light">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="status_akun" id="aktif" value="Aktif" <?php if($data['status_akun'] == 'Aktif') echo 'checked'; ?>>
                                <label class="form-check-label text-success fw-bold" for="aktif">
                                    <i class="bi bi-check-circle-fill"></i> Aktif (Bisa Login & Pinjam)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status_akun" id="beku" value="Dibekukan" <?php if($data['status_akun'] == 'Dibekukan') echo 'checked'; ?>>
                                <label class="form-check-label text-danger fw-bold" for="beku">
                                    <i class="bi bi-lock-fill"></i> Dibekukan (Suspend / Sanksi)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-bold px-4"><i class="bi bi-save"></i> Simpan Perubahan</button>
                        <a href="kelola_anggota.php" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</main>

<?php include 'footer_admin.php'; ?>
<?php
// Hapus session_start() karena sudah ada di header.php
// session_start(); 

include 'header.php';
include 'koneksi.php';

// --- FITUR PENCARIAN SEDERHANA ---
$search_keyword = "";
$query_str = "SELECT * FROM buku";

if (isset($_GET['q'])) {
    $search_keyword = mysqli_real_escape_string($koneksi, $_GET['q']);
    $query_str .= " WHERE judul LIKE '%$search_keyword%' OR penulis LIKE '%$search_keyword%'";
}

$query_str .= " ORDER BY id DESC";
$result_buku = mysqli_query($koneksi, $query_str);
?>

<div class="container py-5">
    
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-primary"><i class="bi bi-journal-bookmark-fill"></i> Katalog Buku</h2>
            <p class="text-muted">Jelajahi koleksi pengetahuan kami.</p>
        </div>
        <div class="col-md-6">
            <form action="" method="GET" class="d-flex gap-2">
                <input type="text" name="q" class="form-control" placeholder="Cari judul atau penulis..." value="<?php echo htmlspecialchars($search_keyword); ?>">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
            </form>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php
        if (mysqli_num_rows($result_buku) > 0) {
            while ($buku = mysqli_fetch_assoc($result_buku)) {
                
				// 1. Logika Gambar
                $gambar_src = "https://via.placeholder.com/300x400?text=No+Cover"; 
                
                // Cek apakah di database ada nama file, dan file itu ada di folder uploads
                if (!empty($buku['gambar']) && file_exists("uploads/" . $buku['gambar'])) {
                    $gambar_src = "uploads/" . $buku['gambar']; // <--- PENTING: Tidak ada C:/
                }

                // 2. Logika Stok & Status
                $stok = $buku['stok'];
                $is_available = ($stok > 0 && $buku['status_ketersediaan'] == 'Tersedia');
                $badge_class = $is_available ? "bg-success" : "bg-danger";
                $status_text = $is_available ? "Tersedia ($stok)" : "Habis / Dipinjam";
        ?>
            
            <div class="col">
                <div class="card h-100 shadow-sm border-0 hover-effect">
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge <?php echo $badge_class; ?>"><?php echo $status_text; ?></span>
                    </div>

                   <div style="height: 250px; overflow: hidden; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                        <img src="<?php echo $gambar_src; ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($buku['judul']); ?>" 
                             style="height: 100%; width: auto; object-fit: cover;"
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/300x400?text=No+Image';">
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-dark mb-1 text-truncate" title="<?php echo htmlspecialchars($buku['judul']); ?>">
                            <?php echo htmlspecialchars($buku['judul']); ?>
                        </h5>
                        <p class="card-text text-muted small mb-2">
                            <i class="bi bi-person"></i> <?php echo htmlspecialchars($buku['penulis']); ?>
                        </p>
                        
                        <?php if (!empty($buku['penerbit'])) { ?>
                            <p class="card-text text-secondary x-small mb-3" style="font-size: 12px;">
                                <i class="bi bi-building"></i> <?php echo htmlspecialchars($buku['penerbit']); ?>
                            </p>
                        <?php } ?>

                        <div class="mt-auto">
                            <?php if (isset($_SESSION['id_anggota'])) { ?>
                                <?php if ($is_available) { ?>
                                    <a href="pinjam.php?id_buku=<?php echo $buku['id']; ?>" class="btn btn-primary w-100 btn-sm shadow-sm" onclick="return confirm('Ingin meminjam buku ini?')">
                                        <i class="bi bi-book"></i> Pinjam Buku
                                    </a>
                                <?php } else { ?>
                                    <button class="btn btn-secondary w-100 btn-sm" disabled>Tidak Tersedia</button>
                                <?php } ?>
                            <?php } else { ?>
                                <a href="login.php" class="btn btn-outline-primary w-100 btn-sm">Login untuk Pinjam</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php 
            } 
        } else { 
        ?>
            <div class="col-12">
                <div class="alert alert-warning text-center py-5">
                    <i class="bi bi-search display-4"></i>
                    <h4 class="mt-3">Buku tidak ditemukan</h4>
                    <p>Coba kata kunci lain atau hubungi pustakawan.</p>
                    <a href="katalog.php" class="btn btn-outline-dark">Reset Pencarian</a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<style>
    .hover-effect { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-effect:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .x-small { font-size: 0.85rem; }
</style>

<?php include 'footer.php'; ?>
<?php
include '../koneksi.php';

if (isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_GET['keyword']);
    
    // UPDATE: Tambahkan 'penerbit' dalam pengambilan data
    $query = "SELECT judul, penulis, penerbit FROM buku WHERE judul LIKE '%$keyword%' LIMIT 10";
    $result = mysqli_query($koneksi, $query);
    
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    echo json_encode($data);
}
?>
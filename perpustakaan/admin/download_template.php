<?php
// Mengatur header agar browser mengenali ini sebagai file download CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=template_input_buku.csv');

// Membuka output stream
$output = fopen('php://output', 'w');

// 1. Membuat Baris Header (Judul Kolom)
fputcsv($output, array('Judul Buku', 'Pengarang', 'Penerbit', 'Stok'));

// 2. Membuat Baris Contoh Data (Dummy) agar admin paham cara isinya
fputcsv($output, array('Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', '10'));
fputcsv($output, array('Bumi Manusia', 'Pramoedya Ananta Toer', 'Lentera Dipantara', '5'));

// Menutup stream
fclose($output);
exit();
?>
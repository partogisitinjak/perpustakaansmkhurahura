<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah admin sudah login
if (!isset($_SESSION['id_admin'])) {
    header("Location: index.php"); // Tendang ke login admin jika belum login
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin Perpustakaan</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f3f4f6; /* Abu-abu sangat muda untuk background */
        }
        .navbar-brand {
            font-weight: 800;
            letter-spacing: 1px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .nav-link {
            font-weight: 600;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">
        <i class="bi bi-shield-lock-fill text-warning"></i> AdminPanel
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAdmin">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNavAdmin">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="pantau_peminjaman.php">Peminjaman</a></li>
        
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            Kelola Data
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="kelola_buku.php">📚 Data Buku</a></li>
            <li><a class="dropdown-item" href="kelola_anggota.php">👥 Data Anggota</a></li>
            <li><a class="dropdown-item" href="kelola_berita.php">📰 Berita & Kegiatan</a></li>
          </ul>
        </li>

        <li class="nav-item"><a class="nav-link" href="kelola_gamifikasi.php">Sanksi & Poin</a></li>
        <li class="nav-item"><a class="nav-link" href="laporan.php">Laporan</a></li>
        
        <li class="nav-item ms-lg-3">
            <a href="logout.php" class="btn btn-danger btn-sm px-3 rounded-pill fw-bold">Logout <i class="bi bi-box-arrow-right"></i></a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-4">
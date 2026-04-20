<?php
// sidebar.php - Sidebar konsisten untuk semua halaman admin
// Current page untuk menandai menu aktif
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- SIDEBAR -->
<div class="sidebar">
  <h2>ADMIN PMB</h2>

  <a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
  </a>
  <a href="data-pendaftar.php" class="<?= $current_page == 'data-pendaftar.php' ? 'active' : '' ?>">
    <i class="fas fa-users"></i>
    <span>List Pendaftar</span>
  </a>
  <a href="kelolasoal.php" class="<?= $current_page == 'kelolasoal.php' ? 'active' : '' ?>">
    <i class="fas fa-question-circle"></i>
    <span>Kelola Soal</span>
  </a>
  <a href="data-hasiltes.php" class="<?= $current_page == 'data-hasiltes.php' ? 'active' : '' ?>">
    <i class="fas fa-chart-bar"></i>
    <span>Hasil Tes</span>
  </a>
  <a href="data-mahasiswa.php" class="<?= $current_page == 'data-mahasiswa.php' ? 'active' : '' ?>">
    <i class="fas fa-file-signature"></i>
    <span>Data Mahasiswa</span>
  </a>
  <a href="data-daftarulang.php" class="<?= $current_page == 'data-daftarulang.php' ? 'active' : '' ?>">
    <i class="fas fa-file-signature"></i>
    <span>Daftar Ulang</span>
  </a>
  
  <a href="logout.php" class="logout">
    <i class="fas fa-sign-out-alt"></i>
    <span>Logout</span>
  </a>
</div>
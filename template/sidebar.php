<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= $main_url ?>dashboard.php" class="brand-link">
    <img src="<?= $main_url ?>asset/image/warung.jpg" alt="warung" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Kasir Emak</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <?php
        // 🟢 Aman dari error, fallback kalau userLogin() gagal
        $user = function_exists('userLogin') ? userLogin() : ['foto' => 'default.png', 'fullname' => 'User'];
        ?>
        <img src="<?= $main_url ?>asset/image/<?= htmlspecialchars($user['foto']) ?>" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?= htmlspecialchars($user['fullname']) ?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="<?= $main_url ?>dashboard.php" class="nav-link <?= function_exists('menuHome') ? menuHome() : '' ?>">
            <i class="nav-icon fas fa-tachometer-alt text-sm"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Master Menu -->
        <?php if ($user['level'] != 3) { ?>
        <li class="nav-item <?= function_exists('menuMaster') ? menuMaster() : '' ?>">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-folder text-sm"></i>
            <p>
              Master
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= $main_url ?>supplier/data-supplier.php" class="nav-link <?= function_exists('menuSupplier') ? menuSupplier() : '' ?>">
                <i class="far fa-circle nav-icon text-sm"></i>
                <p>Supplier</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= $main_url ?>customer/data-customer.php" class="nav-link <?= function_exists('menuCustomer') ? menuCustomer() : '' ?>">
                <i class="far fa-circle nav-icon text-sm"></i>
                <p>Customer</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= $main_url ?>barang" class="nav-link <?= function_exists('menuBarang') ? menuBarang() : '' ?>">
                <i class="far fa-circle nav-icon text-sm"></i>
                <p>Barang</p>
              </a>
            </li>
          </ul>
        </li>
        <?php } ?>

        <!-- Transaksi -->
        <li class="nav-header">Transaksi</li>
        <li class="nav-item">
          <a href="<?= $main_url ?>pembelian" class="nav-link <?= function_exists('menuBeli') ? menuBeli() : '' ?>">
            <i class="nav-icon fas fa-shopping-cart text-sm"></i>
            <p>Pembelian</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= $main_url ?>penjualan" class="nav-link <?= function_exists('menuJual') ? menuJual() : '' ?>">
            <i class="nav-icon fas fa-file-invoice text-sm"></i>
            <p>Penjualan</p>
          </a>
        </li>

        <!-- Laporan -->
        <li class="nav-header">Report</li>
        <li class="nav-item">
          <a href="<?= $main_url ?>laporan-pembelian" class="nav-link <?= function_exists('laporanBeli') ? laporanBeli() : '' ?>">
            <i class="nav-icon fas fa-chart-pie text-sm"></i>
            <p>Laporan Pembelian</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= $main_url ?>laporan-penjualan" class="nav-link <?= function_exists('laporanPenjualan') ? laporanPenjualan() : '' ?>">
            <i class="nav-icon fas fa-chart-line text-sm"></i>
            <p>Laporan Penjualan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= $main_url ?>stock" class="nav-link <?= function_exists('laporanStock') ? laporanStock() : '' ?>">
            <i class="nav-icon fas fa-warehouse text-sm"></i>
            <p>Laporan Stock</p>
          </a>
        </li>

        <!-- Pengaturan -->
        <?php if ($user['level'] == 1) { ?>
        <li class="nav-item <?= function_exists('menuSetting') ? menuSetting() : '' ?>">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cog text-sm"></i>
            <p>
              Pengaturan
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= $main_url ?>user/data-user.php" class="nav-link <?= function_exists('menuUser') ? menuUser() : '' ?>">
                <i class="far fa-circle nav-icon text-sm"></i>
                <p>Users</p>
              </a>
            </li>
          </ul>
        </li>
        <?php } ?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>

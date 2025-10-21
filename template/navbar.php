<?php  
if (!isset($_SESSION["ssLoginPOS"])) {
  header("location: " . $main_url . "auth/login.php");
  exit();
}
?>

   <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <div class="overlay">
      <i class="fas fa-2x fa-spinner fa-spin">
      </i>
    </div>
  </div>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-info navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <div class="form-group nav-item ml-3">
      <div class="custom-control custom-switch custom-switch on-success nav-link">
        <input type="checkbox" class="custom-control-input" id="cekDark">
        <label for="cekDark" class="custom-control-label">Dark Mode</label>
      </div>
    </div>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="<?= $main_url ?>dashboard.php" class="nav-link"></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- User Dropdown -->
    <li class="nav-item dropdown">
      <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        <i class="fas fa-user-circle mr-1"></i>
        <?= $_SESSION['username']; ?> <!-- pakai username dari login -->
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <span class="dropdown-item text-center">
          <strong><?= $_SESSION['username']; ?></strong>
        </span>
        <div class="dropdown-divider"></div>
        <a href="<?= $main_url ?>auth/change-password.php" class="dropdown-item">
          <i class="fas fa-key mr-2"></i> Change Password
        </a>
        <div class="dropdown-divider"></div>
        <a href="<?= $main_url ?>auth/logout.php" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> Log Out
        </a>
      </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->

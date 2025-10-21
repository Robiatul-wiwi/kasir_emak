<?php  
session_start();

if (!isset($_SESSION["ssLoginPOS"])) {
  header("location: ../auth/login.php");
  exit();
}

require "../config/config.php"; 
require "../config/functions.php"; 
require "../module/mode-barang.php";  

$title = "Form Barang - kasir_emak"; 
require "../template/header.php"; 
require "../template/navbar.php"; 
require "../template/sidebar.php"; 

// Mode Edit
if (isset($_GET['msg']) && $_GET['msg'] == 'editing') {
  $msg = $_GET['msg'];
  $id  = $_GET['id'];
  $sqlEdit = "SELECT * FROM table_barang WHERE id_barang = '$id'";
  $data    = getData($sqlEdit);
  $barang  = !empty($data) ? $data[0] : null;
} else {
  $msg = "";
  $barang = null;
}

$alert = '';

if (isset($_POST['simpan'])) {
  if ($msg != '') { 
    // Mode update
    if (update($_POST) > 0) {
      echo "<script>document.location.href ='index.php?msg=update';</script>";
      exit();
    } else {
      $alert = '<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-ban"></i> Gagal!</h5>
                  Data barang gagal diperbarui.
                </div>';
    }
  } else {
    // Mode tambah baru
    if (insert($_POST) > 0) {
      echo "<script>document.location.href ='index.php?msg=added';</script>";
      exit();
    } else {
      $alert = '<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-ban"></i> Gagal!</h5>
                  Data barang gagal disimpan.
                </div>';
    }
  }
}
?>

<div class="content-wrapper">   
  <div class="content-header">     
    <div class="container-fluid">       
      <div class="row mb-2">         
        <div class="col-sm-6"><h1 class="m-0">Barang</h1></div>        
        <div class="col-sm-6">           
          <ol class="breadcrumb float-sm-right">             
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>             
            <li class="breadcrumb-item"><a href="<?= $main_url ?>barang/index.php">Barang</a></li>             
            <li class="breadcrumb-item active"><?= $msg != '' ? 'Edit Barang' : 'Add Barang' ?></li>           
          </ol>         
        </div>           
      </div>     
    </div>   
  </div>    

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <form action="" method="post" enctype="multipart/form-data">
          <?php if ($alert != '') echo $alert; ?>
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-pen fa-sm"></i> <?= $msg != '' ? 'Edit Barang' : 'Input Barang' ?> 
            </h3>
            <button type="submit" name="simpan" class="btn btn-primary btn-sm float-right d-block mb-2">
              <i class="fas fa-save"></i> Simpan
            </button>
            <button type="reset" class="btn btn-danger btn-sm float-right mr-1 d-block mb-2">
              <i class="fas fa-times"></i> Reset
            </button>
          </div>

          <div class="card-body">
            <div class="row">
              <!-- Kolom Kiri -->
              <div class="col-lg-8 mb-3 pr-3">
                <div class="form-group">
                  <label for="kode">Kode Barang</label>
                  <input type="text" name="kode" class="form-control" id="kode" 
                    value="<?= $msg != '' && $barang ? $barang['id_barang'] : generateId() ?>" readonly>
                </div>
                <div class="form-group">
                  <label for="barcode">Barcode *</label>
                  <input type="text" name="barcode" class="form-control" id="barcode" 
                    value="<?= $msg != '' && $barang ? $barang['barcode'] : '' ?>" placeholder="barcode" required>
                </div>
                <div class="form-group">
                  <label for="name">Nama *</label>
                  <input type="text" name="name" class="form-control" 
                    value="<?= $msg != '' && $barang ? $barang['nama_barang'] : '' ?>" 
                    placeholder="nama barang" required>
                </div>
                <div class="form-group">
                  <label for="satuan">Satuan *</label>
                  <select name="satuan" class="form-control" required>
                    <option value="">-- Satuan Barang --</option>
                    <?php 
                      $satuan = ["piece", "botol", "kaleng", "pouch"];
                      foreach ($satuan as $sat) {
                        $selected = ($msg != '' && $barang && $barang['satuan'] == $sat) ? 'selected' : '';
                        echo "<option value='$sat' $selected>$sat</option>";
                      }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="harga_beli">Harga Beli *</label>
                  <input type="number" name="harga_beli" class="form-control" 
                    value="<?= $msg != '' && $barang ? $barang['harga_beli'] : '' ?>" 
                    placeholder="Rp 0" required>
                </div>
                <div class="form-group">
                  <label for="harga_jual">Harga Jual *</label>
                  <input type="number" name="harga_jual" class="form-control" 
                    value="<?= $msg != '' && $barang ? $barang['harga_jual'] : '' ?>" 
                    placeholder="Rp 0" required>
                </div>
                <div class="form-group">
                  <label for="stock"> Stock *</label>
                  <input type="number" name="stock" class="form-control" 
                    value="<?= $msg != '' && $barang ? $barang['stock'] : '' ?>" 
                    placeholder="0" required>
                </div>
                <div class="form-group">
                  <label for="stock_minimal">Stock Minimal *</label>
                  <input type="number" name="stock_minimal" class="form-control" 
                    value="<?= $msg != '' && $barang ? $barang['stock_minimal'] : '' ?>" 
                    placeholder="0" required>
                </div>
              </div>

              <!-- Kolom Kanan (Gambar) -->
              <div class="col-lg-4 mb-3 text-center px-3">
                <input type="hidden" name="oldImg" value="<?= $msg != '' && $barang ? $barang['gambar'] : '' ?>">
                <label>Gambar Barang</label>
                <div style="border:1px solid #ddd; padding:10px; border-radius:5px;">
                  <img src="<?= $main_url ?>asset/image/barang/<?= ($msg != '' && $barang && $barang['gambar'] != '') ? $barang['gambar'] : 'default-brg.jpg' ?>" 
                   class="img-fluid mb-2 mt-2" 
                   id="previewImg"
                   alt="preview" 
                   style="max-width:150px; border:1px solid #ccc; padding:5px;">
                  <input type="file" class="form-control mt-2" name="image" onchange="previewFile(this);">
                  <small class="text-muted">Type file gambar: JPG | PNG | GIF</small>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>

<script>
function previewFile(input) {
  var file = input.files[0];
  if (file) {
    var reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById("previewImg").src = e.target.result;
    }
    reader.readAsDataURL(file);
  }
}
</script>

<?php require "../template/footer.php"; ?>

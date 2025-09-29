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

$alert = '';

if (isset($_POST['simpan'])) {
  if (insert($_POST)) {
      $alert = '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Alert!</h5>
                  Barang berhasil ditambahkan..
                </div>';
  }
}

// proses simpan barang
if (isset($_POST['simpan'])) {
  $kode          = mysqli_real_escape_string($koneksi, $_POST['kode']);
  $barcode       = mysqli_real_escape_string($koneksi, $_POST['barcode']);
  $name          = mysqli_real_escape_string($koneksi, $_POST['name']);
  $satuan        = mysqli_real_escape_string($koneksi, $_POST['satuan']);
  $harga_beli    = mysqli_real_escape_string($koneksi, $_POST['harga_beli']);
  $harga_jual    = mysqli_real_escape_string($koneksi, $_POST['harga_jual']);
  $stock_minimal = mysqli_real_escape_string($koneksi, $_POST['stock_minimal']);

  // Upload Gambar
  $gambar = "default-brg.jpg"; // default
  if ($_FILES['image']['name'] != '') {
    $extValid = ['jpg', 'jpeg', 'png', 'gif'];
    $extFile  = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (in_array($extFile, $extValid)) {
      $newName = time() . "-" . rand(1000,9999) . "." . $extFile;
      $tujuan  = "../asset/image/barang/" . $newName;
      if (move_uploaded_file($_FILES['image']['tmp_name'], $tujuan)) {
        $gambar = $newName;
      }
    }
  }

  // insert ke database
  $query = "INSERT INTO tbl_barang 
            (id_barang, barcode, nama_barang, satuan, harga_beli, harga_jual, stock_minimal, foto) 
            VALUES 
            ('$kode','$barcode','$name','$satuan','$harga_beli','$harga_jual','$stock_minimal','$gambar')";
  
  if (mysqli_query($koneksi, $query)) {
    echo "<script>alert('Data barang berhasil disimpan');window.location='data-barang.php';</script>";
  } else {
    echo "<script>alert('Data barang gagal disimpan: " . mysqli_error($koneksi) . "');</script>";
  }
}
?>

<div class="content-wrapper">   
  <!-- Content Header (Page header) -->   
  <div class="content-header">     
    <div class="container-fluid">       
      <div class="row mb-2">         
        <div class="col-sm-6">           
          <h1 class="m-0">Barang</h1>         
        </div>        
        <div class="col-sm-6 text-right">  
          <!-- Tombol Reset & Simpan di kanan atas -->
          <a href="<?= $main_url ?>barang/data-barang.php" class="btn btn-danger btn-sm">
            <i class="fas fa-times"></i> Reset
          </a>
          <button type="submit" form="formBarang" name="simpan" class="btn btn-primary btn-sm">
            <i class="fas fa-save"></i> Simpan
          </button>
        </div>       
      </div>     
    </div>   
  </div>    

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <!-- FORM MULAI -->
        <form action="" method="post" id="formBarang" enctype="multipart/form-data">
          <?php if ($alert != '') {
            echo $alert;
          } ?>
          <div class="card-body">
            <div class="row">
              <!-- Kolom Kiri -->
              <div class="col-lg-8 mb-3 pr-3">
                <div class="form-group">
                  <label for="kode">Kode Barang</label>
                  <input type="text" name="kode" class="form-control" id="kode" value="<?= generateId(); ?>" readonly>
                </div>
                <div class="form-group">
                  <label for="barcode">Barcode *</label>
                  <input type="text" name="barcode" class="form-control" id="barcode" placeholder="barcode" autocomplete="off" required>
                </div>
                <div class="form-group">
                  <label for="name">Nama *</label>
                  <input type="text" name="name" class="form-control" id="name" placeholder="nama barang" autocomplete="off" required>
                </div>
                <div class="form-group">
                  <label for="satuan">Satuan *</label>
                  <select name="satuan" id="satuan" class="form-control" required>
                    <option value="">-- Satuan Barang --</option>
                    <option value="piece">Piece</option>
                    <option value="botol">Botol</option>
                    <option value="kaleng">Kaleng</option>
                    <option value="pouch">Pouch</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="harga_beli">Harga Beli *</label>
                  <input type="number" name="harga_beli" class="form-control" id="harga_beli" placeholder="Rp 0" autocomplete="off" required>
                </div>
                <div class="form-group">
                  <label for="harga_jual">Harga Jual *</label>
                  <input type="number" name="harga_jual" class="form-control" id="harga_jual" placeholder="Rp 0" autocomplete="off" required>
                </div>
                <div class="form-group">
                  <label for="stock_minimal">Stock Minimal *</label>
                  <input type="number" name="stock_minimal" class="form-control" id="stock_minimal" placeholder="0" autocomplete="off" required>
                </div>
              </div>

              <!-- Kolom Kanan (Gambar) -->
              <div class="col-lg-4 mb-3 text-center px-3">
                <label>Foto Barang</label>
                <div style="border:1px solid #ddd; padding:10px; border-radius:5px;">
                  <img src="<?= $main_url ?>asset/image/default-brg.jpg" 
                       class="img-fluid mb-2 mt-2" 
                       id="previewImg"
                       alt="preview" 
                       style="max-width:150px; border:1px solid #ccc; padding:5px;">
                  <input type="file" class="form-control mt-2" name="image" id="image" onchange="previewFile(this);">
                  <small class="text-muted">Type file gambar: JPG | PNG | GIF</small>
                </div>
              </div>
            </div>
          </div>
        </form>
        <!-- FORM SELESAI -->
      </div>
    </div>
  </section>
</div>

<!-- Script preview gambar -->
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

<?php 
require "../template/footer.php";
?>

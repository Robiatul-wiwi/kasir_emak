<?php  
session_start();

if (!isset($_SESSION["ssLoginPOS"])) {
  header("location: ../auth/login.php");
  exit();
}

require "../config/config.php"; 
require "../config/functions.php"; 
require "../module/mode-supplier.php";  

$title = "Edit Supplier - kasir_emak"; 
require "../template/header.php"; 
require "../template/navbar.php"; 
require "../template/sidebar.php"; 

// jalankan fungsi update data
if (isset($_POST['update'])) {
    if (update($_POST)) {
        echo "<script>
                document.location.href = 'data-supplier.php?msg=updated';
        </script>";
    }
}

$id = $_GET['id'];
$sqlEdit = "SELECT * FROM tbl_supplier WHERE id_supplier = $id";
$supplier = getData($sqlEdit)[0];
?>  

<div class="content-wrapper">   
  <!-- Content Header (Page header) -->   
  <div class="content-header">     
    <div class="container-fluid">       
      <div class="row mb-2">         
        <div class="col-sm-6">           
          <h1 class="m-0">Supplier</h1>         
        </div>        
        <div class="col-sm-6">           
          <ol class="breadcrumb float-sm-right">             
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>             
            <li class="breadcrumb-item"><a href="<?= $main_url ?>supplier/data-suppliner.php">Supplier</a></li>             
            <li class="breadcrumb-item active">Edit Supplier</li>           
          </ol>         
        </div>       
      </div>     
    </div>   
  </div>    

  <!-- Main content -->
  <section class="content">     
    <div class="container-fluid">       
      <div class="row">
        <div class="col-12"> <!-- FULL WIDTH -->
          <div class="card">         
            <form action="" method="post">
              <div class="card-header">           
                <h3 class="card-title"><i class="fas fa-edit fa-sm"></i> Edit Supplier</h3>
                <div class="card-tools">
                  <button type="submit" name="update" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Update
                  </button>
                  <button type="reset" class="btn btn-danger btn-sm mr-1">
                    <i class="fas fa-times"></i> Reset
                  </button>
                </div>
              </div>
              <div class="card-body">
                <input type="hidden" name="id" value="<?= $supplier['id_supplier'] ?>">
                
                <div class="form-group">
                  <label for="nama">Nama</label>
                  <input type="text" name="nama" class="form-control" id="nama" 
                         placeholder="nama supplier" autofocus 
                         value="<?= $supplier['nama'] ?>" required>
                </div>
                
                <div class="form-group">
                  <label for="telpon">Telpon</label>
                  <input type="text" name="telpon" class="form-control" id="telpon" 
                         placeholder="telpon supplier" pattern="[0-9]{5,}" 
                         title="minimal 5 angka" value="<?= $supplier['telpon'] ?>" required>
                </div>
                
                <div class="form-group">
                  <label for="ketr">Deskripsi</label>
                  <textarea name="ketr" id="ketr" rows="1" class="form-control" 
                            placeholder="keterangan supplier" required><?= $supplier['deskripsi'] ?></textarea>
                </div>
                
                <div class="form-group">
                  <label for="alamat">Alamat</label>
                  <textarea name="alamat" id="alamat" rows="3" class="form-control" 
                            placeholder="Alamat supplier" required><?= $supplier['alamat'] ?></textarea>
                </div>
              </div>
            </form>
          </div>         
        </div> <!-- end col -->
      </div> <!-- end row -->
    </div>
  </section>
</div>

<?php
require "../template/footer.php";
?>

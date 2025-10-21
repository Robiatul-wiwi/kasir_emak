<?php  
session_start();

if (!isset($_SESSION["ssLoginPOS"])) {
  header("location: ../auth/login.php");
  exit();
}

require "../config/config.php"; 
require "../config/functions.php"; 
require "../module/mode-customer.php";  

$title = "Add Customer - kasir_emak"; 
require "../template/header.php"; 
require "../template/navbar.php"; 
require "../template/sidebar.php"; 

// Proses tambah data customer
if (isset($_POST['simpan'])) {
    if (insert($_POST) > 0) {
        echo "<script>
                document.location.href = 'data-customer.php?msg=added';
              </script>";
        exit();
    } else {
        echo "<script>alert('Gagal menambahkan customer');</script>";
    }
}
?>  

<div class="content-wrapper">   
  <div class="content-header">     
    <div class="container-fluid">       
      <div class="row mb-2">         
        <div class="col-sm-6">           
          <h1 class="m-0">Customer</h1>         
        </div>        
        <div class="col-sm-6">           
          <ol class="breadcrumb float-sm-right">             
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>             
            <li class="breadcrumb-item"><a href="<?= $main_url ?>customer/data-customer.php">Customer</a></li>             
            <li class="breadcrumb-item active">Add Customer</li>           
          </ol>         
        </div>       
      </div>     
    </div>   
  </div>    

  <section class="content">     
    <div class="container-fluid">       
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="" method="post">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-plus fa-sm"></i> Tambah Customer</h3>
                <div class="card-tools">
                  <button type="submit" name="simpan" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Simpan
                  </button>
                  <button type="reset" class="btn btn-danger btn-sm">
                    <i class="fas fa-times"></i> Reset
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="nama">Nama</label>
                  <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama customer" required>
                </div>
                <div class="form-group">
                  <label for="telpon">Telpon</label>
                  <input type="text" name="telpon" class="form-control" id="telpon" placeholder="Nomor telpon" pattern="[0-9]{5,}" title="Minimal 5 angka" required>
                </div>
                <div class="form-group">
                  <label for="deskripsi">Deskripsi</label>
                  <textarea name="deskripsi" id="deskripsi" rows="2" class="form-control" placeholder="Keterangan customer"></textarea>
                </div>
                <div class="form-group">
                  <label for="alamat">Alamat</label>
                  <textarea name="alamat" id="alamat" rows="3" class="form-control" placeholder="Alamat customer"></textarea>
                </div>
              </div>
            </form>
          </div>         
        </div>
      </div>
    </div>
  </section>
</div>

<?php require "../template/footer.php"; ?>
  
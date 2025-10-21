<?php  

session_start();

if (!isset($_SESSION["ssLoginPOS"])) {
  header("location: ../auth/login.php");
  exit();
}

require "../config/config.php"; 
require "../config/functions.php"; 
require "../module/module-user.php";  

$title = "Tambah User - kasir_emak"; 
require "../template/header.php"; 
require "../template/navbar.php"; 
require "../template/sidebar.php";   

if (isset($_POST['simpan'])) {   
  if (insert($_POST) > 0) {      
    echo "<script>
            alert('User baru berhasil diregistrasi..');   
            window.location='" . $main_url . "user/data-user.php';
          </script>";   
  } else {
    echo "<script>alert('User gagal diregistrasi!');</script>";
  }
}
?>  

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">   
  <!-- Content Header (Page header) -->   
  <div class="content-header">     
    <div class="container-fluid">       
      <div class="row mb-2">         
        <div class="col-sm-6">           
          <h1 class="m-0">Users</h1>         
        </div>        
        <div class="col-sm-6">           
          <ol class="breadcrumb float-sm-right">             
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>             
            <li class="breadcrumb-item"><a href="<?= $main_url ?>user/data-user.php">Users</a></li>             
            <li class="breadcrumb-item active">Add User</li>           
          </ol>         
        </div>       
      </div>     
    </div>   
  </div>    

  <section class="content">     
    <div class="container-fluid">       
      <div class="card">         
        <form action="" method="post" enctype="multipart/form-data">
          <div class="card-header">           
            <h3 class="card-title"><i class="fas fa-plus fa-sm"></i> Add User</h3>
            <div class="card-tools">
              <button type="submit" name="simpan" class="btn btn-primary btn-sm">
                <i class="fas fa-save"></i> Simpan
              </button>
              <button type="reset" class="btn btn-danger btn-sm mr-1">
                <i class="fas fa-times"></i> Reset
              </button>
            </div>
          </div>         

          <div class="card-body">           
            <div class="row">             
              <div class="col-lg-8 mb-3">               
                <div class="form-group">                 
                  <label for="username">Username</label>                 
                  <input type="text" name="username" class="form-control" id="username" placeholder="Masukkan username" required>               
                </div>                

                <div class="form-group">                 
                  <label for="fullname">Fullname</label>                 
                  <input type="text" name="fullname" class="form-control" id="fullname" placeholder="Masukkan nama lengkap" required>               
                </div>               

                <div class="form-group">                 
                  <label for="password">Password</label>                 
                  <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan password" required>               
                </div>                 

                <div class="form-group">                 
                  <label for="password2">Konfirmasi Password</label>                 
                  <input type="password" name="password2" class="form-control" id="password2" placeholder="Masukkan kembali password" required>               
                </div>                

                <div class="form-group">                 
                  <label for="level">Level</label>                 
                  <select name="level" id="level" class="form-control" required>                   
                    <option value="">-- Pilih Level User --</option>                   
                    <option value="1">Administrator</option>                   
                    <option value="2">Supervisor</option>                   
                    <option value="3">Operator</option>                 
                  </select>               
                </div>               

                <div class="form-group">                 
                  <label for="address">Address</label>                 
                  <textarea name="address" id="address" rows="3" class="form-control" placeholder="Masukkan alamat user" required></textarea>               
                </div>             
              </div>             

              <div class="col-lg-4 text-center">             
                <img src="<?= $main_url ?>asset/image/default.webp" class="profile-user-img img-circle mb-3" alt="default user">             
                <input type="file" class="form-control" name="image">             
                <span class="text-sm">Tipe file: JPG | PNG | GIF | WEBP</span><br>             
                <span class="text-sm">Disarankan Width = Height</span>             
              </div>           
            </div>           
          </div> <!-- /.card-body -->
        </form>
      </div>     
    </div>   
  </section> 
</div>  

<?php require "../template/footer.php"; ?>  

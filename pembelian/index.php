<?php  

session_start();

if (!isset($_SESSION["ssLoginPOS"])) {
  header("location: ../auth/login.php");
  exit();
}

require "../config/config.php"; 
require "../config/functions.php"; 
require "../module/mode-beli.php";  

$title  = "Transaksi - kasir_emak"; 
require "../template/header.php"; 
require "../template/navbar.php"; 
require "../template/sidebar.php"; 

// ===============================
// Inisialisasi variabel & session
// ===============================
$tgl = isset($_GET['tgl']) ? $_GET['tgl'] : date('Y-m-d');

if (!isset($_SESSION['keranjang_beli'])) {
  $_SESSION['keranjang_beli'] = [];
}

// ===============================
// Hapus barang
// ===============================
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  unset($_SESSION['keranjang_beli'][$id]);
  $_SESSION['keranjang_beli'] = array_values($_SESSION['keranjang_beli']);
  echo "<script>document.location='?tgl=$tgl';</script>";
  exit;
}

// ===============================
// Pilih barang
// ===============================
$kode = isset($_GET['pilihbrg']) ? $_GET['pilihbrg'] : '';
if ($kode) {
  $selectBrg = getData("SELECT * FROM table_barang WHERE id_barang = '$kode'")[0];
}

// ===============================
// Generate nomor pembelian
// ===============================
$no_beli = generateNo();

// ===============================
// Tambah ke keranjang
// ===============================
if (isset($_POST['addbrg'])) {
  $_POST['tgl_beli'] = $_POST['tglNota'];

  if (empty($_POST['jml_harga']) && isset($_POST['harga_beli'], $_POST['qty'])) {
    $_POST['jml_harga'] = $_POST['harga_beli'] * $_POST['qty'];
  }

  if (insert($_POST)) {
    $idBrg = $_POST['kode_brg'];
    $qty   = $_POST['qty'];
    $barang = getData("SELECT * FROM table_barang WHERE id_barang='$idBrg'")[0];
    $jml_harga = $barang['harga_beli'] * $qty;

    $_SESSION['keranjang_beli'][] = [
      'id_barang'   => $barang['id_barang'],
      'nama_barang' => $barang['nama_barang'],
      'harga_beli'  => $barang['harga_beli'],
      'qty'         => $qty,
      'jml_harga'   => $jml_harga
    ];
    echo "<script>document.location='?tgl=" . $_POST['tglNota'] . "';</script>";
  }
}

// ===============================
// Simpan transaksi pembelian
// ===============================
if (isset($_POST['simpan'])) {
  $_POST['tgl_beli'] = $_POST['tglNota'];
  if (simpan($_POST)) {
    echo "<script>
            alert('Data pembelian berhasil disimpan');
            document.location='index.php?msg=sukses';
          </script>";
  }
}
?>

<!-- CSS Spinner -->
<style>
input.form-control[type=number]::-webkit-inner-spin-button,
input.form-control[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: auto !important;
  margin: 0 !important;
}
input.form-control[type=number] {
  -moz-appearance: number-input !important;
}
</style>

<div class="content-wrapper">   
  <div class="content-header">     
    <div class="container-fluid">       
      <div class="row mb-2">         
        <div class="col-sm-6"><h1 class="m-0">Pembelian Barang</h1></div>        
        <div class="col-sm-6">           
          <ol class="breadcrumb float-sm-right">             
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>             
            <li class="breadcrumb-item active">Tambah Pembelian</li>           
          </ol>         
        </div>           
      </div>     
    </div>   
  </div>  

  <section>
    <div class="container-fluid">
        <form action="" method="post">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-outline card-warning p-3">
                        <div class="form-group row mb-2">
                            <label for="noNota" class="col-sm-2 col-form-label">No Nota</label>
                            <div class="col-sm-4">
                                <input type="text" name="no_beli" class="form-control" id="noNota" value="<?= $no_beli ?>" readonly>
                            </div>
                            <label for="tglNota" class="col-sm-2 col-form-label">Tgl Nota</label>
                            <div class="col-sm-4">
                                <input type="date" name="tglNota" class="form-control" id="tglNota" value="<?= $tgl ?>" required>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                          <label for="kode_brg" class="col-sm-2 col-form-label">SKU</label>
                          <div class="col-sm-10">
                            <select name="pilihbrg" id="kode_brg" class="form-control">
                              <option value="">-- Pilih kode barang --</option>
                              <?php 
                              $barang = getData("SELECT * FROM table_barang");
                              foreach($barang as $brg){ ?>
                                  <option value="<?= $brg['id_barang'] ?>" <?= @$_GET['pilihbrg'] == $brg['id_barang'] ? 'selected' : '' ?>>
                                    <?= $brg['id_barang'] . " | " . $brg['nama_barang'] ?>
                                  </option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                  <div class="card card-outline card-danger pt-3 px-3 pb-2">
                    <h6 class="font-weight-bold text-right">Total Pembelian</h6>
                    <h1 class="font-weight-bold text-right" style="font-size:40px;">
                      <?= isset($_SESSION['keranjang_beli']) ? number_format(array_sum(array_column($_SESSION['keranjang_beli'], 'jml_harga')),0,',','.') : 0 ?>
                    </h1>
                  </div>
                </div>
            </div>

            <!-- Form Tambah Barang -->
            <div class="card pt-1 pb-2 px-3">
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">
                    <input type="hidden" name="kode_brg" value="<?= @$_GET['pilihbrg'] ? $selectBrg['id_barang'] : '' ?>">
                    <label for="nama_brg">Nama Barang</label>
                    <input type="text" name="nama_brg" class="form-control form-control-sm" id="nama_brg" 
                      value="<?= @$_GET['pilihbrg'] ? $selectBrg['nama_barang'] : '' ?>" readonly>
                  </div>
                </div>
                <div class="col-lg-1">
                  <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" name="stok" class="form-control form-control-sm" id="stok" 
                      value="<?= @$_GET['pilihbrg'] ? $selectBrg['stock'] : '' ?>" readonly>
                  </div>
                </div>
                <div class="col-lg-1">
                  <div class="form-group">
                    <label for="satuan">Satuan</label>
                    <input type="text" name="satuan" class="form-control form-control-sm" id="satuan" 
                      value="<?= @$_GET['pilihbrg'] ? $selectBrg['satuan'] : '' ?>" readonly>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="form-group">
                    <label for="harga_beli">Harga</label>
                    <input type="number" name="harga_beli" class="form-control form-control-sm" id="harga_beli" 
                      value="<?= @$_GET['pilihbrg'] ? $selectBrg['harga_beli'] : '' ?>" readonly>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="form-group">
                    <label for="qty">Qty</label>
                    <input type="number" name="qty" class="form-control form-control-sm" id="qty" value="<?= @$_GET['pilihbrg'] ? 1 : '' ?>">
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="form-group">
                    <label for="jml_harga">Jumlah Harga</label>
                    <input type="number" name="jml_harga" class="form-control form-control-sm" id="jml_harga" readonly>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-sm btn-info btn-block" name="addbrg">
                <i class="fas fa-cart-plus fa-sm"></i> Tambah Barang
              </button>
            </div>

            <!-- Tabel Keranjang -->
            <div class="card card-outline card-success table-responsive px-2">
              <table class="table table-sm table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Jumlah Harga</th>
                    <th class="text-center" width="10%">Operasi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  if (count($_SESSION['keranjang_beli']) > 0) {
                    $no = 1;
                    foreach ($_SESSION['keranjang_beli'] as $key => $detail) { ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $detail['id_barang'] ?></td>
                        <td><?= $detail['nama_barang'] ?></td>
                        <td class="text-right"><?= number_format($detail['harga_beli'], 0, ',', '.') ?></td>
                        <td class="text-right"><?= $detail['qty'] ?></td>
                        <td class="text-right"><?= number_format($detail['jml_harga'], 0, ',', '.') ?></td>
                        <td class="text-center">
                          <a href="?hapus=<?= $key ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus barang ini?')">
                            <i class="fas fa-trash"></i>
                          </a>
                        </td>
                      </tr>
                  <?php } } else { ?>
                      <tr><td colspan="7" class="text-center text-muted">Keranjang kosong</td></tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>

            <!-- Form Simpan -->
            <div class="row">
              <div class="col-lg-6 p-2">
                <div class="form-group row mb-2">
                  <label for="suplier" class="col-sm-3 col-form-label col-form-label-sm">Suplier</label>
                  <div class="col-sm-9">
                    <select name="suplier" id="suplier" class="form-control form-control-sm">
                      <option value="">-- Pilih Suplier --</option>
                      <?php 
                      $suppliers = getData("SELECT * FROM tbl_supplier");
                      foreach($suppliers as $supplier){ ?>
                        <option value="<?= htmlspecialchars($supplier['nama']) ?>"><?= htmlspecialchars($supplier['nama']) ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-2">
                  <label for="ketr" class="col-sm-3 col-form-label">Keterangan</label>
                  <div class="col-sm-9">
                    <textarea name="ketr" id="ketr" class="form-control form-control-sm"></textarea>
                  </div>
                </div>
              </div>

              <div class="col-lg-6 p-2">
                <button type="submit" name="simpan" id="simpan" class="btn btn-primary btn-sm btn-block">
                  <i class="fa fa-save"></i> Simpan
                </button>
              </div>
            </div>
        </form>
    </div>
  </section>

  <!-- JS Hitung Otomatis Jumlah Harga -->
  <script>
    const qty = document.getElementById('qty');
    const harga_beli = document.getElementById('harga_beli');
    const jml_harga = document.getElementById('jml_harga');

    function hitungJumlah() {
      const q = parseFloat(qty.value) || 0;
      const h = parseFloat(harga_beli.value) || 0;
      jml_harga.value = q * h;
    }

    qty.addEventListener('input', hitungJumlah);
    harga_beli.addEventListener('input', hitungJumlah);
    document.addEventListener('DOMContentLoaded', hitungJumlah);

    // Ganti barang otomatis
    const pilihbrg = document.getElementById('kode_brg');
    const tgl = document.getElementById('tglNota');
    pilihbrg.addEventListener('change', function() {
      if (this.value !== '') {
        document.location.href = "?pilihbrg=" + this.value + '&tgl=' + tgl.value;
      }
    });
  </script>

<?php 
require "../template/footer.php";
?>

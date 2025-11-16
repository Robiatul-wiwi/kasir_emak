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
// Inisialisasi token anti double submit
// ===============================
if (!isset($_SESSION['token_pembelian'])) {
    $_SESSION['token_pembelian'] = bin2hex(random_bytes(16));
}
$token = $_SESSION['token_pembelian'];

// ===============================
// Inisialisasi variabel & session
// ===============================
$tgl = isset($_GET['tgl']) ? $_GET['tgl'] : date('Y-m-d');

if (!isset($_SESSION['keranjang_beli'])) {
  $_SESSION['keranjang_beli'] = [];
}

// ===============================
// Hapus barang dari keranjang
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
// Tambah barang ke keranjang (bukan insert DB)
// ===============================
if (isset($_POST['addbrg'])) {

  $idBrg = $_POST['kode_brg'];
  $qty   = $_POST['qty'];

  if ($idBrg == "" || $qty <= 0) {
    echo "<script>alert('Barang & Qty wajib diisi');</script>";
  } else {
    $barang = getData("SELECT * FROM table_barang WHERE id_barang='$idBrg'")[0];
    $jml_harga = $barang['harga_beli'] * $qty;

    $_SESSION['keranjang_beli'][] = [
      'id_barang'   => $barang['id_barang'],
      'nama_barang' => $barang['nama_barang'],
      'harga_beli'  => $barang['harga_beli'],
      'qty'         => $qty,
      'jml_harga'   => $jml_harga
    ];
  }

  echo "<script>document.location='?tgl=" . $_POST['tglNota'] . "';</script>";
  exit;
}

// ===============================
// Simpan transaksi ke database (insert hanya sekali)
// ===============================
if (isset($_POST['simpan'])) {

  // Anti double click
  if ($_POST['token'] !== $_SESSION['token_pembelian']) {
      die("<script>alert('Duplikasi transaksi dicegah!'); document.location='index.php';</script>");
  }

  $_POST['tgl_beli'] = $_POST['tglNota'];

  if (simpan($_POST)) {

    unset($_SESSION['keranjang_beli']);
    $_SESSION['token_pembelian'] = bin2hex(random_bytes(16));

    echo "<script>
            alert('Data pembelian berhasil disimpan');
            document.location='index.php?msg=sukses';
          </script>";
    exit;
  }
}

?>

<!-- ========================= CSS ========================= -->
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
      </div>
    </div>
  </div>

  <section>
    <div class="container-fluid">
      <form action="" method="post">

        <input type="hidden" name="token" value="<?= $token ?>">

        <div class="row">
          <div class="col-lg-6">
            <div class="card card-outline card-warning p-3">
              <div class="form-group row mb-2">
                <label class="col-sm-2 col-form-label">No Nota</label>
                <div class="col-sm-4">
                  <input type="text" name="no_beli" class="form-control" value="<?= $no_beli ?>" readonly>
                </div>

                <label class="col-sm-2 col-form-label">Tgl Nota</label>
                <div class="col-sm-4">
                  <input type="date" name="tglNota" id="tglNota" class="form-control" value="<?= $tgl ?>" required>
                </div>
              </div>

              <div class="form-group row mb-2">
                <label class="col-sm-2 col-form-label">SKU</label>
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
                <?= number_format(array_sum(array_column($_SESSION['keranjang_beli'], 'jml_harga')),0,',','.') ?>
              </h1>
            </div>
          </div>
        </div>

        <!-- Form Tambah Barang -->
        <div class="card pt-1 pb-2 px-3">
          <div class="row">
            <input type="hidden" name="kode_brg" value="<?= @$_GET['pilihbrg'] ? $selectBrg['id_barang'] : '' ?>">

            <div class="col-lg-4">
              <label>Nama Barang</label>
              <input type="text" name="nama_brg" class="form-control form-control-sm"
                value="<?= @$_GET['pilihbrg'] ? $selectBrg['nama_barang'] : '' ?>" readonly>
            </div>

            <div class="col-lg-1">
              <label>Stok</label>
              <input type="number" class="form-control form-control-sm"
                value="<?= @$_GET['pilihbrg'] ? $selectBrg['stock'] : '' ?>" readonly>
            </div>

            <div class="col-lg-1">
              <label>Satuan</label>
              <input type="text" class="form-control form-control-sm"
                value="<?= @$_GET['pilihbrg'] ? $selectBrg['satuan'] : '' ?>" readonly>
            </div>

            <div class="col-lg-2">
              <label>Harga</label>
              <input type="number" id="harga_beli" class="form-control form-control-sm"
                value="<?= @$_GET['pilihbrg'] ? $selectBrg['harga_beli'] : '' ?>" readonly>
            </div>

            <div class="col-lg-2">
              <label>Qty</label>
              <input type="number" name="qty" id="qty" class="form-control form-control-sm" 
                value="<?= @$_GET['pilihbrg'] ? 1 : '' ?>">
            </div>

            <div class="col-lg-2">
              <label>Jumlah Harga</label>
              <input type="number" name="jml_harga" id="jml_harga" class="form-control form-control-sm" readonly>
            </div>
          </div>

          <button type="submit" class="btn btn-info btn-block btn-sm" name="addbrg">
            <i class="fas fa-cart-plus"></i> Tambah Barang
          </button>
        </div>

        <!-- Keranjang -->
        <div class="card card-outline card-success table-responsive px-2">
          <table class="table table-sm table-hover text-nowrap">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Jumlah</th>
                <th class="text-center">Hapus</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              if (count($_SESSION['keranjang_beli']) > 0) {
                $no = 1;
                foreach ($_SESSION['keranjang_beli'] as $key => $d) { ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $d['id_barang'] ?></td>
                    <td><?= $d['nama_barang'] ?></td>
                    <td class="text-right"><?= number_format($d['harga_beli'],0,',','.') ?></td>
                    <td class="text-right"><?= $d['qty'] ?></td>
                    <td class="text-right"><?= number_format($d['jml_harga'],0,',','.') ?></td>
                    <td class="text-center">
                      <a href="?hapus=<?= $key ?>" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i>
                      </a>
                    </td>
                  </tr>
              <?php }} else { ?>
                <tr>
                  <td colspan="7" class="text-center text-muted">Keranjang kosong</td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>

        <!-- Simpan -->
        <div class="row">
          <div class="col-lg-6 p-2">
            <label>Suplier</label>
            <select name="suplier" class="form-control form-control-sm">
              <option value="">-- Pilih Suplier --</option>
              <?php 
              $suppliers = getData("SELECT * FROM tbl_supplier");
              foreach($suppliers as $sp){ ?>
                <option value="<?= $sp['nama'] ?>"><?= $sp['nama'] ?></option>
              <?php } ?>
            </select>

            <label class="mt-2">Keterangan</label>
            <textarea name="ketr" class="form-control form-control-sm"></textarea>
          </div>

          <div class="col-lg-6 p-2">
            <button type="submit" name="simpan" class="btn btn-primary btn-sm btn-block">
              <i class="fa fa-save"></i> Simpan Pembelian
            </button>
          </div>
        </div>

      </form>
    </div>
  </section>

<script>
// Hitung subtotal otomatis
const qty = document.getElementById('qty');
const harga = document.getElementById('harga_beli');
const jml = document.getElementById('jml_harga');

function hitung() {
  const q = parseFloat(qty.value) || 0;
  const h = parseFloat(harga.value) || 0;
  jml.value = q * h;
}
qty.addEventListener('input', hitung);
document.addEventListener('DOMContentLoaded', hitung);

// Ganti barang
const pilih = document.getElementById('kode_brg');
const tgl = document.getElementById('tglNota');

pilih.addEventListener('change', function() {
  if (this.value !== '') {
    document.location.href = "?pilihbrg=" + this.value + "&tgl=" + tgl.value;
  }
});
</script>

<?php require "../template/footer.php"; ?>
